<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Narcotic;
use App\District;
use App\Unit;
use App\Agency_detail;
use App\Court_detail;
use App\Seizure;
use App\Storage_detail;
use App\Ps_detail;
use App\User;
use Carbon\Carbon;
use DB;
use Auth;


class MonthlyReportController extends Controller
{

    public function index_dashboard(){
        $data['total_seizure'] = Seizure::count();
        $data['total_disposed'] = Seizure::where('disposal_flag','Y')
                                            ->count();
        
        $data['total_undisposed'] = $data['total_seizure'] - $data['total_disposed'];

        return view('dashboard',compact('data'));

    }


    public function monthly_report_status(Request $request){
        $start_date = date('Y-m-d', strtotime('01-'.$request->input('month')));
        $end_date = date('Y-m-d', strtotime($start_date. ' +30 days'));
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'PS ID',
            1=>'Case No',
            2=>'Case Year',
            3=>'More Details',
            4=>'Sl No',
            5=>'Stakeholder Name',
            6 =>'Case_No',
            7 =>'Narcotic Type',
            8 =>'Certification Status',
            9 =>'Disposal Status'
        );

        $seizure_details = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                    ->join('agency_details','seizures.stakeholder_id','=','agency_details.agency_id')
                                    ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                    ->join('units','seizures.seizure_quantity_weighing_unit_id','=','units.unit_id')
                                    ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                    ->where([
                                        ['date_of_seizure','>=',$start_date],
                                        ['date_of_seizure','<=',$end_date]
                                    ])
                                    ->orWhere([
                                        ['date_of_certification','>=',$start_date],
                                        ['date_of_certification','<=',$end_date]
                                    ])
                                    ->orWhere([
                                        ['date_of_disposal','>=',$start_date],
                                        ['date_of_disposal','<=',$end_date]
                                    ])
                                    ->get();

        $record = array();

        $report['Sl No'] = 0;

        foreach($seizure_details as $data){
            //PS ID
            $report['PS ID'] = $data->ps_id;

            //Case No
            $report['Case No'] = $data->case_no;

            //PS ID
            $report['Case Year'] = $data->case_year;

            //More Details
            $report['More Details'] = '+';

            // Serial Number incrementing for every row
            $report['Sl No'] +=1;

            //If submitted date is within 10 days of present date, a new marker will be shown
            if(((strtotime(date('Y-m-d')) - strtotime($data->updated_at)) / (60*60*24) <=10))
                $report['Stakeholder Name'] = "<strong>".$data->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
            else
                $report['Stakeholder Name'] = "<strong>".$data->agency_name."</strong>";

            //Case_No
            $report['Case_No'] = $data->ps_name." PS / ".$data->case_no." / ".$data->case_year;

            //Narcotic Type
            $report['Narcotic Type'] = $data->drug_name;

            //Certification Status
            if($data->certification_flag=='Y')
                $report['Certification Status'] = 'DONE';
            else if ($data->certification_flag=='N')
                $report['Certification Status'] = 'PENDING';


            //Disposal Status
            if($data->disposal_flag=='Y')
                $report['Disposal Status'] = 'DONE';
            else if ($data->disposal_flag=='N')
                $report['Disposal Status'] = 'NOT DISPOSED';

            $record[] = $report;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval(sizeof($record)),
            "recordsFiltered" =>intval(sizeof($record)),
            "data" => $record
        );
        
        echo json_encode($json_data);


    }
    
    // Report For A Specific Stakeholder For A Specific Month
    public function show_monthly_report($agency_id, $month){
        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        from seizures s left join units u1 on cast(s.unit_name as int)=u1.unit_id 
        left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        left join court_details on s.certification_court_id = court_details.court_id
        left join districts on s.district_id = districts.district_id
        where s.submit_flag='S' and s.agency_id= ".$agency_id." and s.month_of_report = '".$month."'
        order by s.seizure_id";

        $data['seizures']=DB::select($sql);
        $data['agency_details'] = Agency_detail::where('agency_id',$agency_id)->get();
                
        foreach($data['seizures'] as $seizures){
            if(empty($seizures->date_of_seizure))
                $seizures->date_of_seizure='';
            else
                $seizures->date_of_seizure = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            
            
            if(empty($seizures->date_of_disposal))
                $seizures->date_of_disposal='';
            else
                $seizures->date_of_disposal = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');


            if(empty($seizures->date_of_certification))
                $seizures->date_of_certification='';
            else
                $seizures->date_of_certification = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
           
                
            $seizures->month_of_report = date('F',strtotime($seizures->month_of_report)).'-'.date('Y',strtotime($seizures->month_of_report));
        }
        
        return view('monthly_report',compact('data'));   

    }

    // Unlock Report Submission
    public function unlock_report_submission(Request $request){
        $agency_id = $request->input('agency_id');
        $month = date('Y-m-d', strtotime('01-'.$request->input('month')));

        Seizure::where([
                    ['agency_id',$agency_id],
                    ['month_of_report',$month]
                ])->update(['submit_flag' => 'N',
                            'updated_at' => Carbon::today()
                            ]);
        
        return 1;
    }


    //for stakeholder's own monthwise previous report

    public function index_previous_report(){
        
        $agency_details = Agency_detail::where('agency_id',Auth::user()->stakeholder_id)
                                            ->get(); 

        return view('previous_report',compact('agency_details'));      
    }

    //Showing submitted report for a specific stakeholder in a specific month
    public function show_previous_report(Request $request){
         
        $month_of_report = date('Y-m-d', strtotime('01-'.$request->input('month_of_report')));
        $stakeholder = Auth::user()->stakeholder_id;

        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'Narcotic Nature',
            1 =>'Seize Quantity',
            2 =>'Seizure Date',
            3 =>'Disposal Date',
            4 =>'Disposal Quantity',
            5 =>'Not Disposed Quantity',
            6 =>'Storage Place',
            7 =>'Case Details',
            8 =>'Where',
            9 =>'Certification Date',
            10 => 'Remarks',
            11 => 'Sl No'
        );

        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        from seizures s left join units u1 on cast(s.unit_name as int)=u1.unit_id 
        left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        left join court_details on s.certification_court_id = court_details.court_id
        left join districts on s.district_id = districts.district_id
        where s.submit_flag='S' and s.agency_id= ".$stakeholder." and 
        s.month_of_report = '".$month_of_report."' order by seizure_id";

        $data['seizures']=DB::select($sql);

        $record = array();

        $report['Sl No'] = 0;
        foreach($data['seizures'] as $seizures){
            $report['Sl No'] = $report['Sl No'] + 1;
            $report['Narcotic Nature'] = $seizures->drug_name;
            $report['Seize Quantity'] = $seizures->quantity_of_drug. " ".$seizures->seizure_unit;
            
            if(empty($seizures->date_of_seizure))
                $report['Seizure Date'] ='';
            else
                $report['Seizure Date'] = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            
            if(empty($seizures->date_of_disposal))
                $report['Disposal Date'] ='';
            else
                $report['Disposal Date'] = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');

            $report['Disposal Quantity'] = $seizures->disposal_quantity. " ".$seizures->disposal_unit;
            $report['Not Disposed Quantity'] = $seizures->undisposed_quantity. " ".$seizures->undisposed_unit_name;
            $report['Storage Place'] = $seizures->storage_location;
            $report['Case Details'] = $seizures->case_details;
            $report['Where'] = $seizures->court_name;

            if(empty($seizures->date_of_certification))
                $report['Certification Date'] = '';
            else
                $report['Certification Date'] = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
            
            
            $report['Remarks'] = $seizures->remarks;

            $record[] = $report;

        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval(sizeof($record)),
            "recordsFiltered" =>intval(sizeof($record)),
            "data" => $record
        );
        
        echo json_encode($json_data);
        

    }

    // Stakeholder wise disposed & undisposed tally
    public function disposed_undisposed_tally(){
        $sql ="select disposal_query.agency_name, disposed+undisposed as seizures, disposal_query.disposed as disposed, undisposal_query.undisposed as undisposed
        from 
        
        (select agency_name, count(disposal.disposal_quantity) as disposed
        from agency_details left join (select * from seizures where disposal_quantity IS NOT NULL and submit_flag='S') as disposal
        on agency_details.agency_id = disposal.agency_id
        group by agency_name) as disposal_query
        
        INNER JOIN 
        
        (select agency_name, count(undisposal.quantity_of_drug) as undisposed
        from agency_details left join (select * from seizures where disposal_quantity IS NULL and submit_flag='S') as undisposal
        on agency_details.agency_id = undisposal.agency_id
        group by agency_name) as undisposal_query
        
        on disposal_query.agency_name = undisposal_query.agency_name
        order by undisposed DESC
        ";

        $tally = DB::select($sql);

        return view('disposed_undisposed_tally',compact('tally'));
    }


    //Composite Report Index Page 
    public function composite_report_index(){
        $agency_details = Agency_detail::get();
        return view('composite_report',compact('agency_details'));
    }

    //Show Composite Report
    public function show_composite_report(Request $request){

        $fromDate = date('Y-m-d', strtotime('01-'.$request->input('fromDate')));
        $toDate = date('Y-m-d', strtotime('01-'.$request->input('toDate')));
        $stakeholder = $request->input('stakeholder');

        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'Narcotic Nature',
            1 =>'Seize Quantity',
            2 =>'Seizure Date',
            3 =>'Disposal Date',
            4 =>'Disposal Quantity',
            5 =>'Not Disposed Quantity',
            6 =>'Storage Place',
            7 =>'Case Details',
            8 =>'Where',
            9 =>'Certification Date',
            10 => 'Remarks',
            11 => 'Sl No'
        );

        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        from seizures s inner join units u1 on cast(s.unit_name as int)=u1.unit_id 
        left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        left join court_details on s.certification_court_id = court_details.court_id
        left join districts on s.district_id = districts.district_id
        where s.submit_flag='S' and s.agency_id= ".$stakeholder." and 
        s.month_of_report between '".$fromDate."' and '".$toDate."' order by seizure_id";

        $data['seizures']=DB::select($sql);

        $record = array();

        $report['Sl No'] = 0;
        foreach($data['seizures'] as $seizures){
            $report['Sl No'] = $report['Sl No'] + 1;
            $report['Narcotic Nature'] = $seizures->drug_name;
            $report['Seize Quantity'] = $seizures->quantity_of_drug. " ".$seizures->seizure_unit;
            $report['Seizure Date'] = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            $report['Disposal Date'] = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');
            $report['Disposal Quantity'] = $seizures->disposal_quantity. " ".$seizures->disposal_unit;
            $report['Not Disposed Quantity'] = $seizures->undisposed_quantity. " ".$seizures->undisposed_unit_name;
            $report['Storage Place'] = $seizures->storage_location;
            $report['Case Details'] = $seizures->case_details;
            $report['Where'] = $seizures->court_name;
            $report['Certification Date'] = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
            $report['Remarks'] = $seizures->remarks;

            $record[] = $report;

        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval(sizeof($record)),
            "recordsFiltered" =>intval(sizeof($record)),
            "data" => $record
        );
        
        echo json_encode($json_data);

    }
  
}
