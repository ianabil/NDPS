<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Narcotic;
use App\Narcotic_unit;
use App\NdpsCourtDetail;
use App\Unit;
use App\Agency_detail;
use App\CertifyingCourtDetail;
use App\Seizure;
use App\Storage_detail;
use App\Ps_detail;
use App\User;
use Carbon\Carbon;
use DB;
use Auth;



class MagistrateController extends Controller
{
    /* For Judicial Magistrate */

    // Show index page for Magistrate's end
    public function show_magistrate_index(Request $request){

        $data = array();
        
        $data['ps'] = Ps_detail::join('certifying_court_details','ps_details.district_id','certifying_court_details.district_id')
                                ->where('certifying_court_details.court_id',Auth::user()->certifying_court_id)
                                ->select('ps_id','ps_name')
                                ->orderBy('ps_name')
                                ->get();
        
        return view('magistrate_entry_form', compact('data'));
    }


    //Fetch case details of a specific case no.
    public function fetch_case_details(Request $request){
        $court_id =Auth::user()->certifying_court_id;
        $case_no_string = $request->input('case_no_string');

        
        $data['case_details'] = Seizure::leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                                ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                                ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')                        
                                ->leftjoin('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                ->join('certifying_court_details','seizures.certification_court_id','=','certifying_court_details.court_id')
                                ->join('ndps_court_details','seizures.ndps_court_id','=','ndps_court_details.ndps_court_id')
                                ->where([
                                    ['seizures.case_no_string','ilike',$case_no_string],
                                    ['seizures.certification_court_id',$court_id]   
                                ])                        
                                ->select('case_no_string','seizures.ps_id','agency_name','drug_name','narcotics.display','narcotics.drug_id','quantity_of_drug','seizure_quantity_weighing_unit_id',
                                        'u1.unit_name AS seizure_unit','date_of_seizure','storage_name',
                                        'court_name','ndps_court_details.ndps_court_id','ndps_court_name','date_of_certification',
                                        'certification_flag','quantity_of_sample','u2.unit_name AS sample_unit',
                                        'remarks','magistrate_remarks')
                                ->get();
        

        foreach($data['case_details'] as $case_details){
            $case_details->date_of_seizure = Carbon::parse($case_details->date_of_seizure)->format('d-m-Y');
            if($case_details->certification_flag=='Y')
                $case_details->date_of_certification = Carbon::parse($case_details->date_of_certification)->format('d-m-Y');           
            if($case_details->magistrate_remarks==null)
                $case_details->magistrate_remarks = "";
        }

        echo json_encode($data);
    }


    // Narcotic wise unit fetching
    public function narcotic_units(Request $request){

        $narcotic = $request->input('narcotic'); 
        $display = $request->input('display'); 

        if($display=='Y'){
            $data['units']=Narcotic_unit::join('units',"narcotic_units.unit_id","=","units.unit_id")
                                    ->select('units.unit_id','unit_name')
                                    ->where('narcotic_id','=', $narcotic )
                                    ->get();
        }
        else if($display=='N'){
            $data['units']= Unit::get();
        }

        echo json_encode($data);

    }

    // Do certification
    public function certify(Request $request){
        $this->validate ( $request, [ 
            'case_no_string' => 'required',
            'narcotic_type' => 'required|integer',
            'sample_quantity' => 'required|numeric',
            'sample_weighing_unit' => 'required|integer',
            'certification_date' => 'required|date',
            'magistrate_remarks' => 'nullable|max:255'
        ] ); 

        $case_no_string = $request->input('case_no_string');
        $narcotic_type = $request->input('narcotic_type');
        $sample_quantity = $request->input('sample_quantity'); 
        $sample_weighing_unit = $request->input('sample_weighing_unit');         
        $certification_date = Carbon::parse($request->input('certification_date'))->format('Y-m-d');
        $magistrate_remarks = $request->input('magistrate_remarks');

        $data = [
            'certification_flag'=>'Y',
            'quantity_of_sample'=>$sample_quantity,
            'sample_quantity_weighing_unit_id'=>$sample_weighing_unit,
            'date_of_certification'=>$certification_date,
            'magistrate_remarks'=>$magistrate_remarks,
            'updated_at'=>Carbon::today()
        ];

        Seizure::where([
            ['case_no_string',$case_no_string],
            ['drug_id',$narcotic_type]
        ])->update($data);
        
        return 1;
        
    }

    public function monthly_report_status(Request $request){
        $court_id =Auth::user()->certifying_court_id;
        $start_date = date('Y-m-d', strtotime('01-'.$request->input('month')));
        $end_date = date('Y-m-d', strtotime($start_date. ' +30 days'));
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'CaseNo',
            1=>'More Details',
            2=>'Sl No',
            3=>'Stakeholder Name',
            4 =>'Case_No',
            5 =>'Narcotic Type',
            6 =>'Certification Status',
            7 =>'Disposal Status'
        );

        // Fetching unique Case No. As Multiple Row May Exist For A Single Case No.
        $cases = Seizure::leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                        ->where([
                            ['seizures.created_at','>=',$start_date],
                            ['seizures.created_at','<=',$end_date],
                            ['certification_court_id',$court_id]
                        ])
                        ->orWhere([
                            ['seizures.updated_at','>=',$start_date],
                            ['seizures.updated_at','<=',$end_date],
                            ['certification_court_id',$court_id]
                        ])
                        ->select('seizures.ps_id','seizures.agency_id','case_no_string','seizures.created_at','ps_name','agency_name')
                        ->orderBy('seizures.created_at','DESC')
                        ->distinct()
                        ->get();
        
        $record = array();

        $report['Sl No'] = 0;
        
        foreach($cases as $case){ 
            //Case No
            $report['CaseNo'] = $case->case_no_string;

            //More Details
            $report['More Details'] = '<img src="images/details_open.png" style="cursor:pointer" class="more_details" alt="More Details">';

            // Serial Number incrementing for every row
            $report['Sl No'] +=1;

            //If Case Initiated By Any Agency
            if($case->ps_id!=null && $case->agency_id!=null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Stakeholder Name'] = "<strong>".$case->ps_name."</strong><br>(Case Initiated By: ".$case->agency_name.")<small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Stakeholder Name'] = "<strong>".$case->ps_name."</strong><br>(Case Initiated By: ".$case->agency_name.")";
            }
            //If Case Initiated By Any PS
            else if($case->ps_id!=null && $case->agency_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Stakeholder Name'] = "<strong>".$case->ps_name."</strong><small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Stakeholder Name'] = "<strong>".$case->ps_name."</strong>";
            }
            //If Case Initiated By Agency
            else if($case->ps_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong>";
            }

            //Case No.         
            $report['Case_No'] = "<strong>".$case->case_no_string."</strong>"; 

            // Fetching details of respective Case No.
            $seizure_details = Seizure::join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                        ->join('units','seizures.seizure_quantity_weighing_unit_id','=','units.unit_id')                                        
                                        ->where('case_no_string',$case->case_no_string)                                        
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

    public function fetch_case_details_for_report(Request $request){
        $case_no_string = $request->input('case_no_string');
        
        $case_details = Seizure::leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                                ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                                ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                                ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                                ->leftjoin('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                ->join('certifying_court_details','seizures.certification_court_id','=','certifying_court_details.court_id')
                                ->where('case_no_string',$case_no_string)  
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
    
}
