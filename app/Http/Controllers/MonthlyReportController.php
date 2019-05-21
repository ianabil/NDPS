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

        // Fetching unique Case No. As Multiple Row May Exist For A Single Case No.
        $cases = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->join('agency_details','seizures.agency_id','=','agency_details.agency_id')
                        ->where([
                            ['seizures.created_at','>=',$start_date],
                            ['seizures.created_at','<=',$end_date]
                        ])
                        ->orWhere([
                            ['seizures.updated_at','>=',$start_date],
                            ['seizures.updated_at','<=',$end_date]
                        ])
                        ->select('seizures.ps_id','case_no','case_year','seizures.created_at','ps_name','agency_name')
                        ->distinct()
                        ->get();
        
        $record = array();

        $report['Sl No'] = 0;
        
        foreach($cases as $case){
            //PS ID
            $report['PS ID'] = $case->ps_id;

            //Case No
            $report['Case No'] = $case->case_no;

            //PS ID
            $report['Case Year'] = $case->case_year;

            //More Details
            $report['More Details'] = '<img src="images/details_open.png" style="cursor:pointer" class="more_details" alt="More Details">';

            // Serial Number incrementing for every row
            $report['Sl No'] +=1;

            //If submitted date is within 10 days of present date, a new marker will be shown
            if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
            else
                $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong>";

            //Case_No
            $report['Case_No'] = $case->ps_name." PS / ".$case->case_no." / ".$case->case_year;

            // Fetching details of respective Case No.   
            $seizure_details = Seizure::join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                        ->join('units','seizures.seizure_quantity_weighing_unit_id','=','units.unit_id')                                        
                                        ->where([
                                            ['seizures.ps_id',$case->ps_id],
                                            ['case_no',$case->case_no],
                                            ['case_year',$case->case_year]
                                        ])                                        
                                        ->get();
            
            $certification_done_flag = 0;
            $certification_pending_flag = 0;
            $partial_certification_flag = 0;

            $disposal_done_flag = 0;
            $disposal_pending_flag = 0;
            $partial_disposal_flag = 0;

            $report['Narcotic Type'] = "<ul type='square'>";
            foreach($seizure_details as $key => $seizure){
                //Narcotic Type
                $report['Narcotic Type'] .= "<li>".$seizure->drug_name."</li>";

                 //Certification Status
                if($seizure->certification_flag=='Y'){
                    $certification_done_flag = 1;
                }
                else{
                    $certification_pending_flag = 1;
                }

                if($certification_done_flag == 1 && $certification_pending_flag == 1){
                    $partial_certification_flag = 1;
                }

                //Disposal Status
                if($seizure->disposal_flag=='Y'){
                    $disposal_done_flag = 1;
                }
                else{
                    $disposal_pending_flag = 1;
                }

                if($disposal_done_flag == 1 && $disposal_pending_flag == 1){
                    $partial_disposal_flag = 1;
                }
            }
            $report['Narcotic Type'] .= "</ul>";

            //Certification Status                
            if($partial_certification_flag == 1){
                $report['Certification Status'] = 'PARTIALLY CERTFIED';
            }
            else if($certification_done_flag == 1 && $certification_pending_flag == 0){
                $report['Certification Status'] = 'COMPLETED';
            }
            else if($certification_done_flag == 0 && $certification_pending_flag == 1){
                $report['Certification Status'] = 'PENDING';
            }


            //Disposal Status                
            if($partial_disposal_flag == 1){
                $report['Disposal Status'] = 'PARTIALLY DISPOSED';
            }
            else if($disposal_done_flag == 1 && $disposal_pending_flag == 0){
                $report['Disposal Status'] = 'DISPOSED';
            }
            else if($disposal_done_flag == 0 && $disposal_pending_flag == 1){
                $report['Disposal Status'] = 'PENDING';
            }

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

    public function fetch_case_details(Request $request){
        $ps_id = $request->input('ps_id');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');

        $case_details = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                ->join('agency_details','seizures.agency_id','=','agency_details.agency_id')
                                ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                                ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                                ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                                ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                ->leftjoin('court_details','seizures.certification_court_id','=','court_details.court_id')
                                ->where([
                                    ['seizures.ps_id',$ps_id],
                                    ['case_no',$case_no],
                                    ['case_year',$case_year]
                                ])
                                ->select('drug_name','quantity_of_drug','u1.unit_name AS seizure_unit','date_of_seizure',
                                'date_of_disposal','disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit',
                                'storage_name','court_name','date_of_certification','certification_flag','quantity_of_sample',
                                'u2.unit_name AS sample_unit','remarks','magistrate_remarks')
                                ->get();
                                
        foreach($case_details as $case){
            $case['date_of_seizure'] = Carbon::parse($case['date_of_seizure'])->format('d-m-Y');
            
            if($case['certification_flag']=='Y'){                    
                $case['date_of_certification'] = Carbon::parse($case['date_of_certification'])->format('d-m-Y');
                $case['certification_flag'] = 'Certification Completed';
            }
            else{
                $case['certification_flag'] = 'PENDING';
                $case['date_of_certification'] = 'NA';
                $case['quantity_of_sample'] = 'NA';
                $case['sample_unit'] = '';
                $case['magistrate_remarks'] = 'NA';
            }
            
            if($case['disposal_flag']=='Y'){                    
                $case['date_of_disposal'] = Carbon::parse($case['date_of_disposal'])->format('d-m-Y');
                $case['disposal_flag'] = 'Disposed';
            }
            else{
                $case['date_of_disposal'] = 'NA';
                $case['disposal_quantity'] = 'NA';
                $case['disposal_unit'] = '';
                $case['disposal_flag'] = 'PENDING';
            }

            if($case['remarks']==null)
                $case['remarks']='Nothing Mentioned';

            
            if($case['magistrate_remarks']==null)
                $case['magistrate_remarks']='Nothing Mentioned';
        }
        
        echo json_encode($case_details);

    }
    
    /*
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
        
        $agency_details = Agency_detail::where('agency_id',Auth::user()->agency_id)
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
    */
  
}
