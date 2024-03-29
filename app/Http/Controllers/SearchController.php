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


class SearchController extends Controller
{
    /* Searching Mechanism For High Court End :: STARTS */

    public function show_highcourt_search_index(){
        $data = array();
        
        $data['ps'] = Ps_detail::select('ps_id','ps_name')
                                ->orderBy('ps_name')
                                ->get();

        $data['stakeholders'] = Agency_detail::select('agency_id','agency_name')
                                             ->orderBy('agency_name')
                                             ->get();

        $data['narcotics'] = Narcotic::where('display','Y')
                                        ->select('drug_id','drug_name')
                                        ->orderBy('drug_name')
                                        ->get();

        $data['ndps_courts'] = NdpsCourtDetail::select('ndps_court_id','ndps_court_name')
                                        ->orderBy('ndps_court_name')
                                        ->get();

        $data['courts'] = CertifyingCourtDetail::select('court_id','court_name')
                                        ->orderBy('court_name')
                                        ->get();

        $data['storages'] = Storage_detail::where('display','Y')
                                            ->select('storage_id','storage_name')
                                            ->orderBy('storage_name')
                                            ->get(); 

        return view('search_highcourt',compact('data'));
    }

    public function show_highcourt_search_result(Request $request){

        $this->validate( $request, [ 
            'ps' => 'nullable|integer|max:2000|exists:ps_details,ps_id',
            'case_no' => 'nullable|integer|max:2000',
            'case_year' => 'nullable|integer|min:1970|max:'.date('Y'),
            'case_no_initial' => 'nullable|string|max:255',
            'stakeholder' => 'nullable|integer|max:2000|exists:agency_details,agency_id',
            'certifying_court' => 'nullable|integer|max:2000|exists:certifying_court_details,court_id',
            'ndps_court' => 'nullable|integer|max:2000|exists:ndps_court_details,ndps_court_id',
            'narcotic_type' => 'nullable|integer|min:1|max:999',
            'storage' => 'nullable|integer|max:999',
            'certified_cases' => 'nullable|alpha|max:255|in:true,false',
            'disposed_cases' => 'nullable|alpha|max:255|in:true,false',
            'seizure_from_date' => 'nullable|date_format:Y-m-d',
            'seizure_to_date' => 'nullable|date_format:Y-m-d',
            'certification_from_date' => 'nullable|date_format:Y-m-d',
            'certification_to_date' => 'nullable|date_format:Y-m-d',
            'disposal_from_date' => 'nullable|date_format:Y-m-d',
            'disposal_to_date' => 'nullable|date_format:Y-m-d',
        ]);

        /* Fetching values from view :: STARTS */
        $ps = $request->input('ps');
        $case_no = trim($request->input('case_no'));
        $case_year = $request->input('case_year');
        $case_no_initial = trim($request->input('case_no_initial'));
        $agency = $request->input('stakeholder');
        $certifying_court = $request->input('certifying_court'); 
        $ndps_court = $request->input('ndps_court');
        $narcotic_type = $request->input('narcotic_type');
        $storage = $request->input('storage');
        $certified_cases = $request->input('certified_cases');
        $disposed_cases = $request->input('disposed_cases'); 
        $seizure_from_date = $request->input('seizure_from_date');
        $seizure_to_date = $request->input('seizure_to_date');
        $certification_from_date = $request->input('certification_from_date'); 
        $certification_to_date = $request->input('certification_to_date');
        $disposal_from_date = $request->input('disposal_from_date');
        $disposal_to_date = $request->input('disposal_to_date');
        /* Fetching values from view :: ENDS */


        // Default SELECT query        
        $select = "SELECT DISTINCT seizures.ps_id, seizures.agency_id, case_no_string, 
        seizures.created_at, ps_name, agency_name, court_name 
        FROM seizures LEFT OUTER JOIN ps_details ON seizures.ps_id = ps_details.ps_id 
        LEFT OUTER JOIN agency_details ON seizures.agency_id = agency_details.agency_id
        JOIN certifying_court_details ON seizures.certification_court_id=certifying_court_details.court_id";

        // Default WHERE condition
        $where = ' WHERE seizures.date_of_seizure IS NOT NULL';

        // Default Order By query
        $orderBy = ' ORDER BY seizures.created_at DESC';
       

        if(!empty($ps))
            $where = $where.' AND seizures.ps_id ='.$ps;

        if(!empty($case_no))
            $where = $where.' AND seizures.case_no ='.$case_no;

        if(!empty($case_year))
           $where = $where.' AND seizures.case_year ='.$case_year;

        if(!empty($case_no_initial))
           $where = $where." AND seizures.case_no_string ilike '%".$case_no_initial."%'";

        if(!empty($agency))
            $where = $where.' AND seizures.agency_id = '.$agency;

        if(!empty($certifying_court))
            $where = $where.' AND seizures.certification_court_id = '.$certifying_court;

        if(!empty($ndps_court))
            $where = $where.' AND seizures.ndps_court_id ='.$ndps_court;

        if(!empty($narcotic_type))
            $where = $where.' AND seizures.drug_id ='.$narcotic_type;

        if(!empty($storage))
            $where = $where.' AND seizures.storage_location_id ='.$storage;

        if(!empty($seizure_from_date) && !empty($seizure_to_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$seizure_from_date'".' AND '."'$seizure_to_date'";
        
        if(!empty($certification_from_date) && !empty($certification_from_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$certification_from_date'".' AND '."'$certification_from_date'";

        if(!empty($disposal_from_date) && !empty($disposal_to_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$disposal_from_date'".' AND '."'$disposal_to_date'";

        if($certified_cases=="true")
            $where = $where." AND seizures.certification_flag ='Y'";

        if($disposed_cases=="true")
            $where = $where." AND seizures.disposal_flag ='Y'";

        
        /* Building SELECT, WHERE clause of the sql query based on the 
        selected different options in the view :: ENDS */
          
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'CaseNo',
            1=>'More Details',
            2=>'Sl No',
            3=>'Stakeholder Name',
            4 =>'Case_No',
            5 =>'Narcotic Type',
            6 =>'Certification Status',
            7 =>'Disposal Status',
            8 =>'Magistrate'
        );


        $limit = $request->input('length'); // For No. of rows per page
        $start = $request->input('start');

        /* Setting the no. of rows returned from every sql query execution and 
           by skipping the no. of rows already displayed
        */
        $limit_data =' LIMIT '.$limit. ' OFFSET '.$start;
        
        if($limit==-1)
            $limit_data='';

        // Data Fetched
        //echo $select.$where.$orderBy;
        $cases = DB::select($select.$where.$orderBy.$limit_data); 
        
        // For getting the total no. of data
        $select = "SELECT COUNT(DISTINCT (seizures.ps_id, case_no, case_year))
        FROM seizures";

        
        // For getting the total no. of data
        $totalData = DB::select($select.$where);

        // For getting the total no. of data which is in this case equal to the total no. of filtered data as here is no search option in the dataTable
        $totalFiltered = $totalData['0']->count;  
                
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
            //If Case Initiated By NCB
            else if($case->ps_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong>";
            }

            //Case_No
                $report['Case_No'] = strtoupper($case->case_no_string);

            // Designated Magistrate
            $report['Magistrate'] = $case->court_name;


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


    public function fetch_case_details(Request $request){
        $this->validate ($request, [ 
            'case_no_string' => 'required|string|max:255|exists:seizures,case_no_string',
        ]); 

        $case_no_string = $request->input('case_no_string');
        
        $case_details = Seizure::leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                                ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                                ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                                ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                                ->leftjoin('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                ->join('certifying_court_details','seizures.certification_court_id','=','certifying_court_details.court_id')
                                ->join('ndps_court_details','seizures.ndps_court_id','=','ndps_court_details.ndps_court_id')
                                ->where('case_no_string',$case_no_string)  
                                ->select('drug_name','quantity_of_drug','u1.unit_name AS seizure_unit','date_of_seizure',
                                'date_of_disposal','disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit',
                                'storage_name','court_name','ndps_court_name','date_of_certification','certification_flag','quantity_of_sample',
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


    /* Searching Mechanism For High Court End :: ENDS */


    /* Searching Mechanism For Stakeholder End :: STARTS */

    public function show_stakeholder_search_index(){
        $data = array();
        
        if(Auth::user()->user_type=="agency"){
            $data['ps'] = Ps_detail::select('ps_id','ps_name')
                                    ->orderBy('ps_name')
                                    ->get();
        }
        else if(Auth::user()->user_type=="ps"){
            $data['ps'] = Ps_detail::select('ps_id','ps_name')
                                    ->where('ps_id',Auth::user()->ps_id)
                                    ->get();
        }

        $data['stakeholders'] = Agency_detail::select('agency_id','agency_name')
                                             ->orderBy('agency_name')                                             
                                             ->get();

        $data['narcotics'] = Narcotic::where('display','Y')
                                        ->select('drug_id','drug_name')
                                        ->orderBy('drug_name')
                                        ->get();
        

        if(Auth::user()->user_type=="agency"){
            $data['courts'] = CertifyingCourtDetail::select('court_id','court_name')
                                            ->orderBy('court_name')
                                            ->get();

            $data['ndps_courts'] = NdpsCourtDetail::select('ndps_court_id','ndps_court_name')
                                            ->orderBy('ndps_court_name')
                                            ->get();

            $data['storages'] = Storage_detail::where('display','Y')
                                                ->select('storage_id','storage_name')
                                                ->orderBy('storage_name')
                                                ->get(); 

        }
        else if(Auth::user()->user_type=="ps"){
            $data['courts'] = CertifyingCourtDetail::join('ps_details','certifying_court_details.district_id','=','ps_details.district_id')
                                            ->where('ps_details.ps_id',Auth::user()->ps_id)
                                            ->select('court_id','court_name')
                                            ->orderBy('court_name')
                                            ->get();

            $data['ndps_courts'] = NdpsCourtDetail::join('ps_details','ndps_court_details.district_id','=','ps_details.district_id')
                                            ->where('ps_details.ps_id',Auth::user()->ps_id)
                                            ->select('ndps_court_id','ndps_court_name')
                                            ->orderBy('ndps_court_name')
                                            ->get();

                                            
            $data['storages'] = Storage_detail::join('ps_details','storage_details.district_id','=','ps_details.district_id')
                                                ->where([
                                                    ['ps_details.ps_id',Auth::user()->ps_id],
                                                    ['display','Y']
                                                ])
                                                ->select('storage_id','storage_name')
                                                ->orderBy('storage_name')
                                                ->get(); 

        }

        
        return view('search_stakeholder',compact('data'));
    }



    public function show_stakeholder_search_result(Request $request){

        $this->validate( $request, [ 
            'ps' => 'nullable|integer|max:2000|exists:ps_details,ps_id',
            'case_no' => 'nullable|integer|max:2000',
            'case_year' => 'nullable|integer|min:1970|max:'.date('Y'),
            'case_no_initial' => 'nullable|string|max:255',
            'agency' => 'nullable|integer|max:2000|exists:agency_details,agency_id',
            'certifying_court' => 'nullable|integer|max:2000|exists:certifying_court_details,court_id',
            'ndps_court' => 'nullable|integer|max:2000|exists:ndps_court_details,ndps_court_id',
            'narcotic_type' => 'nullable|integer|min:1|max:999',
            'storage' => 'nullable|integer|max:999',
            'certified_cases' => 'nullable|alpha|max:255|in:true,false',
            'disposed_cases' => 'nullable|alpha|max:255|in:true,false',
            'seizure_from_date' => 'nullable|date_format:Y-m-d',
            'seizure_to_date' => 'nullable|date_format:Y-m-d',
            'certification_from_date' => 'nullable|date_format:Y-m-d',
            'certification_to_date' => 'nullable|date_format:Y-m-d',
            'disposal_from_date' => 'nullable|date_format:Y-m-d',
            'disposal_to_date' => 'nullable|date_format:Y-m-d',
        ]);
        
        /* Fetching values from view :: STARTS */
        $ps = $request->input('ps');
        $case_no = trim($request->input('case_no'));
        $case_year = $request->input('case_year');
        $case_no_initial = trim($request->input('case_no_initial'));
        $agency = $request->input('agency');
        $certifying_court = $request->input('certifying_court'); 
        $ndps_court = $request->input('ndps_court');
        $narcotic_type = $request->input('narcotic_type');
        $storage = $request->input('storage');
        $certified_cases = $request->input('certified_cases');
        $disposed_cases = $request->input('disposed_cases'); 
        $seizure_from_date = $request->input('seizure_from_date');
        $seizure_to_date = $request->input('seizure_to_date');
        $certification_from_date = $request->input('certification_from_date'); 
        $certification_to_date = $request->input('certification_to_date');
        $disposal_from_date = $request->input('disposal_from_date');
        $disposal_to_date = $request->input('disposal_to_date');
        /* Fetching values from view :: ENDS */


        // Default SELECT query
        $select = "SELECT DISTINCT seizures.ps_id, seizures.agency_id, case_no_string, 
        seizures.created_at, ps_name, agency_name, court_name 
        FROM seizures LEFT OUTER JOIN ps_details ON seizures.ps_id = ps_details.ps_id 
        LEFT OUTER JOIN agency_details ON seizures.agency_id = agency_details.agency_id
        JOIN certifying_court_details ON seizures.certification_court_id=certifying_court_details.court_id";

        // Default WHERE condition
        if(Auth::user()->user_type=="ps")
            $where = ' WHERE seizures.ps_id='.Auth::user()->ps_id.' AND seizures.date_of_seizure IS NOT NULL';
        if(Auth::user()->user_type=="agency")
            $where = ' WHERE seizures.agency_id='.Auth::user()->agency_id.' AND seizures.date_of_seizure IS NOT NULL';

        // Default Order By query
        $orderBy = ' ORDER BY seizures.created_at DESC';
       

        if(!empty($ps))
            $where = $where.' AND seizures.ps_id ='.$ps;

        if(!empty($case_no))
            $where = $where.' AND seizures.case_no ='.$case_no;

        if(!empty($case_year))
           $where = $where.' AND seizures.case_year ='.$case_year;

        if(!empty($case_no_initial))
           $where = $where." AND seizures.case_no_string ilike '%".$case_no_initial."%'";

        if(!empty($agency) && Auth::user()->user_type=="ps")
            $where = $where.' AND seizures.agency_id = '.$agency;

        if(!empty($certifying_court))
            $where = $where.' AND seizures.certification_court_id = '.$certifying_court;

        if(!empty($ndps_court))
            $where = $where.' AND seizures.ndps_court_id ='.$ndps_court;

        if(!empty($narcotic_type))
            $where = $where.' AND seizures.drug_id ='.$narcotic_type;

        if(!empty($storage))
            $where = $where.' AND seizures.storage_location_id ='.$storage;

        if(!empty($seizure_from_date) && !empty($seizure_to_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$seizure_from_date'".' AND '."'$seizure_to_date'";
        
        if(!empty($certification_from_date) && !empty($certification_from_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$certification_from_date'".' AND '."'$certification_from_date'";

        if(!empty($disposal_from_date) && !empty($disposal_to_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$disposal_from_date'".' AND '."'$disposal_to_date'";

        if($certified_cases=="true")
            $where = $where." AND seizures.certification_flag ='Y'";

        if($disposed_cases=="true")
            $where = $where." AND seizures.disposal_flag ='Y'";

        
        /* Building SELECT, WHERE clause of the sql query based on the 
        selected different options in the view :: ENDS */
          
        // For dataTable :: STARTS
        $columns = array(
            0 =>'CaseNo',
            1 =>'More Details',
            2 =>'Sl No',
            3 =>'Stakeholder Name',
            4 =>'Case_No',
            5 =>'Narcotic Type',
            6 =>'Certification Status',
            7 =>'Disposal Status',
            8 =>'Magistrate' 
        );


        $limit = $request->input('length'); // For No. of rows per page
        $start = $request->input('start');

        /* Setting the no. of rows returned from every sql query execution and 
           by skipping the no. of rows already displayed
        */
        $limit_data =' LIMIT '.$limit. ' OFFSET '.$start;
        
        if($limit==-1)
            $limit_data='';

        // Data Fetched
        $cases = DB::select($select.$where.$orderBy.$limit_data);

        // For getting the total no. of data
        $select = "SELECT COUNT(DISTINCT (seizures.ps_id, case_no, case_year))
        FROM seizures";

        
        // For getting the total no. of data
        $totalData = DB::select($select.$where);

        // For getting the total no. of data which is in this case equal to the total no. of filtered data as here is no search option in the dataTable
        $totalFiltered = $totalData['0']->count;  
                
        $record = array();

        $report['Sl No'] = 0;
        
        foreach($cases as $case){
            //Case No
            $report['CaseNo'] = $case->case_no_string;

            
            //More Details
            $report['More Details'] = '<img src="images/details_open.png" style="cursor:pointer" class="more_details" alt="More Details">';

            // Serial Number incrementing for every row
            $report['Sl No'] +=1;

            //Case No. :: If Case Initiated By Any Agency
            if($case->ps_id!=null && $case->agency_id!=null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Case_No'] = "<strong>".strtoupper($case->case_no_string)."</strong><br>(Case Initiated By: ".$case->agency_name.")<small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Case_No'] = "<strong>".strtoupper($case->case_no_string)."</strong><br>(Case Initiated By: ".$case->agency_name.")";
            }
            //If Case Initiated By Any PS
            else if($case->ps_id!=null && $case->agency_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Case_No'] = "<strong>".strtoupper($case->case_no_string)."</strong><small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Case_No'] = "<strong>".strtoupper($case->case_no_string)."</strong>";
            }
            //If Case Initiated By Agency
            else if($case->ps_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Case_No'] = "<strong>".strtoupper($case->case_no_string)."</strong> <small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Case_No'] = "<strong>".strtoupper($case->case_no_string)."</strong>";
            }

            // Designated Magistrate
            $report['Magistrate'] = $case->court_name;


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


    public function fetch_case_details_stakeholder(Request $request){
        $this->validate ($request, [ 
            'case_no_string' => 'required|string|max:255|exists:seizures,case_no_string',
        ]);

        $case_no_string = $request->input('case_no_string');
        
        $case_details = Seizure::leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                                ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                                ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                                ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                                ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                                ->leftjoin('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                                ->join('certifying_court_details','seizures.certification_court_id','=','certifying_court_details.court_id')
                                ->join('ndps_court_details','seizures.ndps_court_id','=','ndps_court_details.ndps_court_id')
                                ->where('case_no_string',$case_no_string)  
                                ->select('drug_name','quantity_of_drug','u1.unit_name AS seizure_unit','date_of_seizure',
                                'date_of_disposal','disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit',
                                'storage_name','court_name','ndps_court_name','date_of_certification','certification_flag','quantity_of_sample',
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

    /* Searching Mechanism For Stakeholder End :: ENDS */


    /* Searching Mechanism For Special Court End :: STARTS */

    public function show_special_court_search_index(){
        $data = array();
        
        $data['ps'] = Ps_detail::join('ndps_court_details','ps_details.district_id','ndps_court_details.district_id')
                                ->where('ndps_court_details.ndps_court_id',Auth::user()->ndps_court_id)
                                ->select('ps_id','ps_name')
                                ->orderBy('ps_name')
                                ->get();

        $data['stakeholders'] = Agency_detail::select('agency_id','agency_name')
                                             ->orderBy('agency_name')
                                             ->get();

        $data['narcotics'] = Narcotic::where('display','Y')
                                        ->select('drug_id','drug_name')
                                        ->orderBy('drug_name')
                                        ->get();

        $data['courts'] = CertifyingCourtDetail::join('ndps_court_details','certifying_court_details.district_id','ndps_court_details.district_id')
                                                ->where('ndps_court_details.ndps_court_id',Auth::user()->ndps_court_id)
                                                ->select('court_id','court_name')
                                                ->orderBy('court_name')
                                                ->get();

        $data['storages'] = Storage_detail::join('ndps_court_details','storage_details.district_id','=','ndps_court_details.district_id')
                                            ->where([
                                                ['ndps_court_details.ndps_court_id',Auth::user()->ndps_court_id],
                                                ['display','Y']
                                            ])
                                            ->select('storage_id','storage_name')
                                            ->orderBy('storage_name')
                                            ->get(); 

        return view('search_specialcourt',compact('data'));
    }

    public function show_special_court_search_result(Request $request){
        
        $this->validate( $request, [ 
            'ps' => 'nullable|integer|max:2000|exists:ps_details,ps_id',
            'case_no' => 'nullable|integer|max:2000',
            'case_year' => 'nullable|integer|min:1970|max:'.date('Y'),
            'case_no_initial' => 'nullable|string|max:255',
            'stakeholder' => 'nullable|integer|max:2000|exists:agency_details,agency_id',
            'certifying_court' => 'nullable|integer|max:2000|exists:certifying_court_details,court_id',            
            'narcotic_type' => 'nullable|integer|min:1|max:999',
            'storage' => 'nullable|integer|max:999',
            'certified_cases' => 'nullable|alpha|max:255|in:true,false',
            'disposed_cases' => 'nullable|alpha|max:255|in:true,false',
            'seizure_from_date' => 'nullable|date_format:Y-m-d',
            'seizure_to_date' => 'nullable|date_format:Y-m-d',
            'certification_from_date' => 'nullable|date_format:Y-m-d',
            'certification_to_date' => 'nullable|date_format:Y-m-d',
            'disposal_from_date' => 'nullable|date_format:Y-m-d',
            'disposal_to_date' => 'nullable|date_format:Y-m-d',
        ]);
        
        /* Fetching values from view :: STARTS */
        $ps = $request->input('ps');
        $case_no = trim($request->input('case_no'));
        $case_year = $request->input('case_year');
        $case_no_initial = trim($request->input('case_no_initial'));
        $agency = $request->input('stakeholder');
        $certifying_court = $request->input('certifying_court');
        $narcotic_type = $request->input('narcotic_type');
        $storage = $request->input('storage');
        $certified_cases = $request->input('certified_cases');
        $disposed_cases = $request->input('disposed_cases'); 
        $seizure_from_date = $request->input('seizure_from_date');
        $seizure_to_date = $request->input('seizure_to_date');
        $certification_from_date = $request->input('certification_from_date'); 
        $certification_to_date = $request->input('certification_to_date');
        $disposal_from_date = $request->input('disposal_from_date');
        $disposal_to_date = $request->input('disposal_to_date');
        /* Fetching values from view :: ENDS */


        // Default SELECT query        
        $select = "SELECT DISTINCT seizures.ps_id, seizures.agency_id, case_no_string, 
        seizures.created_at, ps_name, agency_name, court_name 
        FROM seizures LEFT OUTER JOIN ps_details ON seizures.ps_id = ps_details.ps_id 
        LEFT OUTER JOIN agency_details ON seizures.agency_id = agency_details.agency_id
        JOIN certifying_court_details ON seizures.certification_court_id=certifying_court_details.court_id";

        // Default WHERE condition
        $where = ' WHERE seizures.ndps_court_id='.Auth::user()->ndps_court_id.' AND seizures.date_of_seizure IS NOT NULL';

        // Default Order By query
        $orderBy = ' ORDER BY seizures.created_at DESC';
       

        if(!empty($ps))
            $where = $where.' AND seizures.ps_id ='.$ps;

        if(!empty($case_no))
            $where = $where.' AND seizures.case_no ='.$case_no;

        if(!empty($case_year))
           $where = $where.' AND seizures.case_year ='.$case_year;

        if(!empty($case_no_initial))
           $where = $where." AND seizures.case_no_string ilike '%".$case_no_initial."%'";

        if(!empty($agency))
            $where = $where.' AND seizures.agency_id = '.$agency;

        if(!empty($certifying_court))
            $where = $where.' AND seizures.certification_court_id = '.$certifying_court;

        if(!empty($narcotic_type))
            $where = $where.' AND seizures.drug_id ='.$narcotic_type;

        if(!empty($storage))
            $where = $where.' AND seizures.storage_location_id ='.$storage;

        if(!empty($seizure_from_date) && !empty($seizure_to_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$seizure_from_date'".' AND '."'$seizure_to_date'";
        
        if(!empty($certification_from_date) && !empty($certification_from_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$certification_from_date'".' AND '."'$certification_from_date'";

        if(!empty($disposal_from_date) && !empty($disposal_to_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$disposal_from_date'".' AND '."'$disposal_to_date'";

        if($certified_cases=="true")
            $where = $where." AND seizures.certification_flag ='Y'";

        if($disposed_cases=="true")
            $where = $where." AND seizures.disposal_flag ='Y'";

        
        /* Building SELECT, WHERE clause of the sql query based on the 
        selected different options in the view :: ENDS */
          
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'CaseNo',
            1=>'More Details',
            2=>'Sl No',
            3=>'Stakeholder Name',
            4 =>'Case_No',
            5 =>'Narcotic Type',
            6 =>'Certification Status',
            7 =>'Disposal Status',
            8 =>'Magistrate'
        );


        $limit = $request->input('length'); // For No. of rows per page
        $start = $request->input('start');

        /* Setting the no. of rows returned from every sql query execution and 
           by skipping the no. of rows already displayed
        */
        $limit_data =' LIMIT '.$limit. ' OFFSET '.$start;
        
        if($limit==-1)
            $limit_data='';

        // Data Fetched
        //echo $select.$where.$orderBy;
        $cases = DB::select($select.$where.$orderBy.$limit_data); 
        
        // For getting the total no. of data
        $select = "SELECT COUNT(DISTINCT (seizures.ps_id, case_no, case_year))
        FROM seizures";

        
        // For getting the total no. of data
        $totalData = DB::select($select.$where);

        // For getting the total no. of data which is in this case equal to the total no. of filtered data as here is no search option in the dataTable
        $totalFiltered = $totalData['0']->count;  
                
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
            //If Case Initiated By NCB
            else if($case->ps_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong> <small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Stakeholder Name'] = "<strong>".$case->agency_name."</strong>";
            }

            //Case_No
                $report['Case_No'] = strtoupper($case->case_no_string);

            // Designated Magistrate
            $report['Magistrate'] = $case->court_name;


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


    public function fetch_case_details_special_court(Request $request){
        $this->validate ($request, [ 
            'case_no_string' => 'required|string|max:255|exists:seizures,case_no_string',
        ]); 

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

    /* Searching Mechanism For Special Court End :: ENDS */




    /*Disposed Undisposed Tally :Start */

    public function calhc_ndps_court_report(Request $request){
        $this->validate( $request, [ 
            'from_date' => 'required|date_format:Y-m-d',
            'to_date' => 'required|date_format:Y-m-d',
        ]); 

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'ndps_court_id',
            1=>'More Details',
            2=>'Sl No',
            3=>'NDPS Court Name',
            4 =>'Narcotic Type',
            5 =>'Disposed Quantity',
            6 =>'Undisposed Quantity'
        );

        $limit = $request->input('length'); // For No. of rows per page
        $start = $request->input('start');

        $totalData = NdpsCourtDetail::count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value'))){
            $ndps_courts = NdpsCourtDetail::select('ndps_court_id','ndps_court_name')
                                        ->limit($limit)
                                        ->offset($start)
                                        ->orderBy('ndps_court_name')
                                        ->get();
        }
        else{
            $search = $request->input('search.value');

            $ndps_courts = NdpsCourtDetail::where('ndps_court_name','ilike',"%{$search}%")
                                        ->select('ndps_court_id','ndps_court_name')
                                        ->limit($limit)
                                        ->offset($start)
                                        ->orderBy('ndps_court_name')
                                        ->get();

            $totalFiltered = NdpsCourtDetail::where('ndps_court_name','ilike',"%{$search}%")
                                        ->count();

        }


        $record = array();

        $report['Sl No'] = $start;

        foreach($ndps_courts as $ndps_court){
            //SL No.
            $report['Sl No'] ++; 

            // NDPS Court ID
            $report['ndps_court_id'] = $ndps_court->ndps_court_id;

            // NDPS Court Name
            $report['NDPS Court Name'] = $ndps_court->ndps_court_name;


            $sql = "SELECT drug_name,SUM(A) disposal_quantity,SUM(B) undisposed_quantity,quantity_unit_name as unit_name
                FROM
                (
                    select drug_name, quantity_unit_name,
                            CASE 
                                    WHEN disposal_flag = 'Y' THEN disposal ELSE 0 END as A,
                            CASE
                                    WHEN disposal_flag = 'Y' THEN seizure-sample-disposal 
                                    ELSE seizure-coalesce(sample, 0) END as B
                    FROM
                    (
                        SELECT 
                            drug_name, disposal_flag,seizure,disposal,sample,quantity_unit_name
                            FROM 
                            (
                                select drug_name, disposal_flag, 
                                        CASE    WHEN u1.unit_degree = 2 THEN                   quantity_of_drug/1000 
                                                WHEN u1.unit_degree = 1 THEN quantity_of_drug/1000000
                                                WHEN u1.unit_degree = 3 THEN quantity_of_drug
                                                WHEN u1.unit_degree = 0 THEN quantity_of_drug
                                                END AS seizure,
                
                                        CASE    WHEN u1.unit_name ilike 'g%m'  THEN             'KG'
                                                WHEN u1.unit_name ilike 'l%e' OR u1.unit_name ilike 'm%l'  THEN 'KL'
                                                ELSE u1.unit_name
                                                END AS quantity_unit_name,
                                                
                
                                        CASE 	WHEN u2.unit_degree = 2 THEN                   disposal_quantity/1000
                                                WHEN u2.unit_degree = 1 THEN disposal_quantity/1000000
                                                WHEN u2.unit_degree = 3 THEN disposal_quantity
                                                WHEN u2.unit_degree = 0 THEN disposal_quantity
                                                END AS disposal,
                                        
                
                                        CASE 	WHEN u3.unit_degree = 2 THEN                   quantity_of_sample/1000
                                                WHEN u3.unit_degree = 1 THEN quantity_of_sample/1000000
                                                WHEN u3.unit_degree = 3 THEN quantity_of_sample
                                                WHEN u3.unit_degree = 0 THEN quantity_of_sample
                                                END AS sample
                
                                        
                                                
                                from  seizures inner join narcotics on seizures.drug_id = narcotics.drug_id 
                                inner join units as u1 on seizures.seizure_quantity_weighing_unit_id=u1.unit_id 
                                left join units as u2  on seizures.disposal_quantity_weighing_unit_id=u2.unit_id 
                                left join units as u3  on seizures.sample_quantity_weighing_unit_id=u3.unit_id 
                                where ndps_court_id=".$ndps_court->ndps_court_id." AND seizures.created_at BETWEEN '".$from_date."' AND '".$to_date."'
                            ) a
                    )b
                )c GROUP BY drug_name,quantity_unit_name";

            $data = DB::select($sql);
            

            $report['Narcotic Type'] = "<ul type='square'>";
            $report['Disposed Quantity'] = "<ul type='square'>";
            $report['Undisposed Quantity'] = "<ul type='square'>";

            foreach($data as $seizure){
                $report['Narcotic Type'] .= "<li>".$seizure->drug_name."</li>";
                if($seizure->disposal_quantity==0)
                    $report['Disposed Quantity'] .= "<li>NIL</li>";
                else
                    $report['Disposed Quantity'] .= "<li>".$seizure->disposal_quantity." ".$seizure->unit_name."</li>";
                
                if($seizure->undisposed_quantity==0)
                    $report['Undisposed Quantity'] .= "<li>NIL</li>";
                else
                $report['Undisposed Quantity'] .= "<li>".$seizure->undisposed_quantity." ".$seizure->unit_name."</li>";
            }

            $report['Narcotic Type'] .= "</ul>";
            $report['Disposed Quantity'] .= "</ul>";
            $report['Undisposed Quantity'] .= "</ul>";

            $record[] = $report;

        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" =>intval($totalFiltered),
            "data" => $record
        );

        echo json_encode($json_data);

    }


    public function calhc_ps_report(Request $request){
        $this->validate( $request, [ 
            'from_date' => 'required|date_format:Y-m-d',
            'to_date' => 'required|date_format:Y-m-d',
        ]); 

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'STAKEHOLDER ID',
            1=>'Sl No',
            2=>'Stakeholder Name',
            3 =>'Narcotic Type',
            4 =>'Disposed Quantity',
            5 =>'Undisposed Quantity'
        );

        $limit = $request->input('length'); // For No. of rows per page
        $start = $request->input('start');

        $totalData = Ps_detail::count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value'))){
            $stakeholders = Ps_detail::select('ps_id','ps_name')
                                        ->limit($limit)
                                        ->offset($start)
                                        ->orderBy('ps_name')
                                        ->get();       
        }
        else{
            $search = $request->input('search.value');

            $stakeholders = Ps_detail::where('ps_name','ilike',"%{$search}%")
                                        ->select('ps_id','ps_name')
                                        ->limit($limit)
                                        ->offset($start)
                                        ->orderBy('ps_name')
                                        ->get();   

            $totalFiltered = Ps_detail::where('ps_name','ilike',"%{$search}%")
                                        ->count();

        }


        $record = array();

        $report['Sl No'] = $start;

        foreach($stakeholders as $stakeholder){
            //SL No.
            $report['Sl No'] ++; 

            // District ID
            $report['STAKEHOLDER ID'] = $stakeholder->ps_id;

            // District Name
            $report['Stakeholder Name'] = $stakeholder->ps_name;


            $sql = "SELECT drug_name,SUM(A) disposal_quantity,SUM(B) undisposed_quantity,quantity_unit_name as unit_name
                FROM
                (
                    select drug_name, quantity_unit_name,
                            CASE 
                                    WHEN disposal_flag = 'Y' THEN disposal ELSE 0 END as A,
                            CASE
                                    WHEN disposal_flag = 'Y' THEN seizure-sample-disposal 
                                    ELSE seizure-coalesce(sample, 0) END as B
                    FROM
                    (
                        SELECT 
                            drug_name, disposal_flag,seizure,disposal,sample,quantity_unit_name
                            FROM 
                            (
                                select drug_name, disposal_flag, 
                                        CASE    WHEN u1.unit_degree = 2 THEN                   quantity_of_drug/1000 
                                                WHEN u1.unit_degree = 1 THEN quantity_of_drug/1000000
                                                WHEN u1.unit_degree = 3 THEN quantity_of_drug
                                                WHEN u1.unit_degree = 0 THEN quantity_of_drug
                                                END AS seizure,
                
                                        CASE    WHEN u1.unit_name ilike 'g%m'  THEN             'KG'
                                                WHEN u1.unit_name ilike 'l%e' OR u1.unit_name ilike 'm%l'  THEN 'KL'
                                                ELSE u1.unit_name
                                                END AS quantity_unit_name,
                                                
                
                                        CASE 	WHEN u2.unit_degree = 2 THEN                   disposal_quantity/1000
                                                WHEN u2.unit_degree = 1 THEN disposal_quantity/1000000
                                                WHEN u2.unit_degree = 3 THEN disposal_quantity
                                                WHEN u2.unit_degree = 0 THEN disposal_quantity
                                                END AS disposal,
                                        
                
                                        CASE 	WHEN u3.unit_degree = 2 THEN                   quantity_of_sample/1000
                                                WHEN u3.unit_degree = 1 THEN quantity_of_sample/1000000
                                                WHEN u3.unit_degree = 3 THEN quantity_of_sample
                                                WHEN u3.unit_degree = 0 THEN quantity_of_sample
                                                END AS sample
                
                                        
                                                
                                from  seizures inner join narcotics on seizures.drug_id = narcotics.drug_id 
                                inner join units as u1 on seizures.seizure_quantity_weighing_unit_id=u1.unit_id 
                                left join units as u2  on seizures.disposal_quantity_weighing_unit_id=u2.unit_id 
                                left join units as u3  on seizures.sample_quantity_weighing_unit_id=u3.unit_id 
                                where ps_id=".$stakeholder->ps_id." AND seizures.created_at BETWEEN '".$from_date."' AND '".$to_date."'
                            ) a
                    )b
                )c GROUP BY drug_name,quantity_unit_name";

            $data = DB::select($sql);

            $report['Narcotic Type'] = "<ul type='square'>";
            $report['Disposed Quantity'] = "<ul type='square'>";
            $report['Undisposed Quantity'] = "<ul type='square'>";

            foreach($data as $seizure){
                $report['Narcotic Type'] .= "<li>".$seizure->drug_name."</li>";
                if($seizure->disposal_quantity==0)
                    $report['Disposed Quantity'] .= "<li>NIL</li>";
                else
                    $report['Disposed Quantity'] .= "<li>".$seizure->disposal_quantity." ".$seizure->unit_name."</li>";
                
                if($seizure->undisposed_quantity==0)
                    $report['Undisposed Quantity'] .= "<li>NIL</li>";
                else
                $report['Undisposed Quantity'] .= "<li>".$seizure->undisposed_quantity." ".$seizure->unit_name."</li>";
            }

            $report['Narcotic Type'] .= "</ul>";
            $report['Disposed Quantity'] .= "</ul>";
            $report['Undisposed Quantity'] .= "</ul>";

            $record[] = $report;

        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" =>intval($totalFiltered),
            "data" => $record
        );

        echo json_encode($json_data);

    }


    public function calhc_agency_report(Request $request){
        $this->validate( $request, [ 
            'from_date' => 'required|date_format:Y-m-d',
            'to_date' => 'required|date_format:Y-m-d',
        ]); 

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'STAKEHOLDER ID',
            1=>'Sl No',
            2=>'Stakeholder Name',
            3 =>'Narcotic Type',
            4 =>'Disposed Quantity',
            5 =>'Undisposed Quantity'
        );

        $limit = $request->input('length'); // For No. of rows per page
        $start = $request->input('start');

        $totalData = Agency_detail::count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value'))){
            $agencies = Agency_detail::select('agency_id','agency_name')
                                        ->limit($limit)
                                        ->offset($start)
                                        ->orderBy('agency_name')
                                        ->get();       
        }
        else{
            $search = $request->input('search.value');

            $agencies = Agency_detail::where('agency_name','ilike',"%{$search}%")
                                        ->select('agency_id','agency_name')
                                        ->limit($limit)
                                        ->offset($start)
                                        ->orderBy('agency_name')
                                        ->get();   

            $totalFiltered = Agency_detail::where('agency_name','ilike',"%{$search}%")
                                            ->count();

        }


        $record = array();

        $report['Sl No'] = $start;

        foreach($agencies as $agency){
            //SL No.
            $report['Sl No'] ++; 

            // District ID
            $report['STAKEHOLDER ID'] = $agency->agency_id;

            // District Name
            $report['Stakeholder Name'] = $agency->agency_name;


            $sql = "SELECT drug_name,SUM(A) disposal_quantity,SUM(B) undisposed_quantity,quantity_unit_name as unit_name
                FROM
                (
                    select drug_name, quantity_unit_name,
                            CASE 
                                    WHEN disposal_flag = 'Y' THEN disposal ELSE 0 END as A,
                            CASE
                                    WHEN disposal_flag = 'Y' THEN seizure-sample-disposal 
                                    ELSE seizure-coalesce(sample, 0) END as B
                    FROM
                    (
                        SELECT 
                            drug_name, disposal_flag,seizure,disposal,sample,quantity_unit_name
                            FROM 
                            (
                                select drug_name, disposal_flag, 
                                        CASE    WHEN u1.unit_degree = 2 THEN                   quantity_of_drug/1000 
                                                WHEN u1.unit_degree = 1 THEN quantity_of_drug/1000000
                                                WHEN u1.unit_degree = 3 THEN quantity_of_drug
                                                WHEN u1.unit_degree = 0 THEN quantity_of_drug
                                                END AS seizure,
                
                                        CASE    WHEN u1.unit_name ilike 'g%m'  THEN             'KG'
                                                WHEN u1.unit_name ilike 'l%e' OR u1.unit_name ilike 'm%l'  THEN 'KL'
                                                ELSE u1.unit_name
                                                END AS quantity_unit_name,
                                                
                
                                        CASE 	WHEN u2.unit_degree = 2 THEN                   disposal_quantity/1000
                                                WHEN u2.unit_degree = 1 THEN disposal_quantity/1000000
                                                WHEN u2.unit_degree = 3 THEN disposal_quantity
                                                WHEN u2.unit_degree = 0 THEN disposal_quantity
                                                END AS disposal,
                                        
                
                                        CASE 	WHEN u3.unit_degree = 2 THEN                   quantity_of_sample/1000
                                                WHEN u3.unit_degree = 1 THEN quantity_of_sample/1000000
                                                WHEN u3.unit_degree = 3 THEN quantity_of_sample
                                                WHEN u3.unit_degree = 0 THEN quantity_of_sample
                                                END AS sample
                
                                        
                                                
                                from  seizures inner join narcotics on seizures.drug_id = narcotics.drug_id 
                                inner join units as u1 on seizures.seizure_quantity_weighing_unit_id=u1.unit_id 
                                left join units as u2  on seizures.disposal_quantity_weighing_unit_id=u2.unit_id 
                                left join units as u3  on seizures.sample_quantity_weighing_unit_id=u3.unit_id 
                                where agency_id=".$agency->agency_id." AND seizures.ps_id IS NULL AND seizures.created_at BETWEEN '".$from_date."' AND '".$to_date."'
                            ) a
                    )b
                )c GROUP BY drug_name,quantity_unit_name";

            $data = DB::select($sql);

            $report['Narcotic Type'] = "<ul type='square'>";
            $report['Disposed Quantity'] = "<ul type='square'>";
            $report['Undisposed Quantity'] = "<ul type='square'>";

            foreach($data as $seizure){
                $report['Narcotic Type'] .= "<li>".$seizure->drug_name."</li>";
                if($seizure->disposal_quantity==0)
                    $report['Disposed Quantity'] .= "<li>NIL</li>";
                else
                    $report['Disposed Quantity'] .= "<li>".$seizure->disposal_quantity." ".$seizure->unit_name."</li>";
                
                if($seizure->undisposed_quantity==0)
                    $report['Undisposed Quantity'] .= "<li>NIL</li>";
                else
                $report['Undisposed Quantity'] .= "<li>".$seizure->undisposed_quantity." ".$seizure->unit_name."</li>";
            }

            $report['Narcotic Type'] .= "</ul>";
            $report['Disposed Quantity'] .= "</ul>";
            $report['Undisposed Quantity'] .= "</ul>";

            $record[] = $report;

        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" =>intval($totalFiltered),
            "data" => $record
        );

        echo json_encode($json_data);

    }
    
    public function calhc_narcotic_district_report(Request $request){
        $this->validate( $request, [ 
            'from_date' => 'required|date_format:Y-m-d',
            'to_date' => 'required|date_format:Y-m-d',
        ]); 

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        // fetching all the narcotics
        $narcotics = Narcotic::orderBy('drug_name')->where('display','Y')->get(); 

        $record = array();

        foreach($narcotics as $narcotic){

            $sql = "SELECT district_name, SUM(A) disposal_quantity,SUM(coalesce(B,0)) undisposed_quantity,quantity_unit_name as unit_name
                    FROM
                        (
                            SELECT district_name, quantity_unit_name, drug_name,
                            CASE 
                                    WHEN disposal_flag = 'Y' THEN disposal ELSE 0 END as A,
                            CASE
                                    WHEN disposal_flag = 'Y' THEN seizure-sample-disposal 
                                    ELSE seizure-coalesce(sample, 0) END as B
                            from 
                            (
                                select district_name, drug_name,
                                CASE    WHEN u1.unit_degree = 2 THEN quantity_of_drug/1000 
                                        WHEN u1.unit_degree = 1 THEN quantity_of_drug/1000000
                                        WHEN u1.unit_degree = 3 THEN quantity_of_drug
                                        WHEN u1.unit_degree = 0 THEN quantity_of_drug
                                        END AS seizure,
                                
                                CASE    WHEN u1.unit_name ilike 'g%m'  THEN 'KG'
                                        WHEN u1.unit_name ilike 'l%e' OR u1.unit_name ilike 'm%l'  THEN 'KL'
                                        ELSE u1.unit_name
                                        END AS quantity_unit_name,
                                
                                
                                CASE    WHEN u2.unit_degree = 2 THEN disposal_quantity/1000
                                        WHEN u2.unit_degree = 1 THEN disposal_quantity/1000000
                                        WHEN u2.unit_degree = 3 THEN disposal_quantity
                                        WHEN u2.unit_degree = 0 THEN disposal_quantity
                                        END AS disposal,
                                
                                
                                CASE    WHEN u3.unit_degree = 2 THEN quantity_of_sample/1000
                                        WHEN u3.unit_degree = 1 THEN quantity_of_sample/1000000
                                        WHEN u3.unit_degree = 3 THEN quantity_of_sample
                                        WHEN u3.unit_degree = 0 THEN quantity_of_sample
                                        END AS sample, disposal_flag
                                
                            
                                from districts left join ndps_court_details on districts.district_id = ndps_court_details.district_id
                                left join seizures on ndps_court_details.ndps_court_id = seizures.ndps_court_id and seizures.drug_id = ".$narcotic->drug_id."
                                left join narcotics on seizures.drug_id = narcotics.drug_id
                                left join units as u1 on seizures.seizure_quantity_weighing_unit_id=u1.unit_id 
                                left join units as u2  on seizures.disposal_quantity_weighing_unit_id=u2.unit_id 
                                left join units as u3  on seizures.sample_quantity_weighing_unit_id=u3.unit_id 
                                WHERE seizures.created_at BETWEEN '".$from_date."' AND '".$to_date."' OR seizures.created_at IS NULL
                                ) a
                        )b
                    GROUP BY district_name,quantity_unit_name
                    ORDER BY district_name";

            $sql2 = "SELECT SUM(A) disposal_quantity,SUM(coalesce(B,0)) undisposed_quantity,quantity_unit_name as unit_name
            FROM
                (
                    SELECT quantity_unit_name,
                    CASE 
                            WHEN disposal_flag = 'Y' THEN disposal ELSE 0 END as A,
                    CASE
                            WHEN disposal_flag = 'Y' THEN seizure-sample-disposal 
                            ELSE seizure-coalesce(sample, 0) END as B
                    from 
                    (
                        select 
                        CASE    WHEN u1.unit_degree = 2 THEN quantity_of_drug/1000 
                                WHEN u1.unit_degree = 1 THEN quantity_of_drug/1000000
                                WHEN u1.unit_degree = 3 THEN quantity_of_drug
                                WHEN u1.unit_degree = 0 THEN quantity_of_drug
                                END AS seizure,
                        
                        CASE    WHEN u1.unit_name ilike 'g%m'  THEN 'KG'
                                WHEN u1.unit_name ilike 'l%e' OR u1.unit_name ilike 'm%l'  THEN 'KL'
                                ELSE u1.unit_name
                                END AS quantity_unit_name,
                        
                        
                        CASE    WHEN u2.unit_degree = 2 THEN disposal_quantity/1000
                                WHEN u2.unit_degree = 1 THEN disposal_quantity/1000000
                                WHEN u2.unit_degree = 3 THEN disposal_quantity
                                WHEN u2.unit_degree = 0 THEN disposal_quantity
                                END AS disposal,
                        
                        
                        CASE    WHEN u3.unit_degree = 2 THEN quantity_of_sample/1000
                                WHEN u3.unit_degree = 1 THEN quantity_of_sample/1000000
                                WHEN u3.unit_degree = 3 THEN quantity_of_sample
                                WHEN u3.unit_degree = 0 THEN quantity_of_sample
                                END AS sample, disposal_flag
                        
                    
                        from narcotics left join seizures on seizures.drug_id = narcotics.drug_id 
                        left join units as u1 on seizures.seizure_quantity_weighing_unit_id=u1.unit_id 
                        left join units as u2  on seizures.disposal_quantity_weighing_unit_id=u2.unit_id 
                        left join units as u3  on seizures.sample_quantity_weighing_unit_id=u3.unit_id 
                        WHERE seizures.created_at BETWEEN '".$from_date."' AND '".$to_date."' and seizures.drug_id = ".$narcotic->drug_id."
                        ) a
                )b
            GROUP BY quantity_unit_name";

            $report['data'] = DB::select($sql);
            $report['narcotic_name'] = $narcotic->drug_name;
            $report['total_value'] = DB::select($sql2);
            
            $record[] = $report;            

        }

        echo json_encode($record);

    }


    
    public function calhc_narcotic_malkhana_report(Request $request){
        $this->validate( $request, [ 
            'from_date' => 'required|date_format:Y-m-d',
            'to_date' => 'required|date_format:Y-m-d',
        ]); 

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        // fetching all the narcotics
        $narcotics = Narcotic::orderBy('drug_name')->where('display','Y')->get(); 

        $record = array();

        foreach($narcotics as $narcotic){

            $sql = "SELECT storage_name, SUM(A) disposal_quantity,SUM(coalesce(B,0)) undisposed_quantity,quantity_unit_name as unit_name
                    FROM
                        (
                            SELECT storage_name, quantity_unit_name, drug_name,
                            CASE 
                                    WHEN disposal_flag = 'Y' THEN disposal ELSE 0 END as A,
                            CASE
                                    WHEN disposal_flag = 'Y' THEN seizure-sample-disposal 
                                    ELSE seizure-coalesce(sample, 0) END as B
                            from 
                            (
                                select storage_name, drug_name,
                                CASE    WHEN u1.unit_degree = 2 THEN quantity_of_drug/1000 
                                        WHEN u1.unit_degree = 1 THEN quantity_of_drug/1000000
                                        WHEN u1.unit_degree = 3 THEN quantity_of_drug
                                        WHEN u1.unit_degree = 0 THEN quantity_of_drug
                                        END AS seizure,
                                
                                CASE    WHEN u1.unit_name ilike 'g%m'  THEN 'KG'
                                        WHEN u1.unit_name ilike 'l%e' OR u1.unit_name ilike 'm%l'  THEN 'KL'
                                        ELSE u1.unit_name
                                        END AS quantity_unit_name,
                                
                                
                                CASE    WHEN u2.unit_degree = 2 THEN disposal_quantity/1000
                                        WHEN u2.unit_degree = 1 THEN disposal_quantity/1000000
                                        WHEN u2.unit_degree = 3 THEN disposal_quantity
                                        WHEN u2.unit_degree = 0 THEN disposal_quantity
                                        END AS disposal,
                                
                                
                                CASE    WHEN u3.unit_degree = 2 THEN quantity_of_sample/1000
                                        WHEN u3.unit_degree = 1 THEN quantity_of_sample/1000000
                                        WHEN u3.unit_degree = 3 THEN quantity_of_sample
                                        WHEN u3.unit_degree = 0 THEN quantity_of_sample
                                        END AS sample, disposal_flag
                                
                            
                                from storage_details left join seizures on storage_details.storage_id = seizures.storage_location_id and seizures.drug_id = ".$narcotic->drug_id."
                                left join narcotics on seizures.drug_id = narcotics.drug_id
                                left join units as u1 on seizures.seizure_quantity_weighing_unit_id=u1.unit_id 
                                left join units as u2  on seizures.disposal_quantity_weighing_unit_id=u2.unit_id 
                                left join units as u3  on seizures.sample_quantity_weighing_unit_id=u3.unit_id 
                                WHERE seizures.created_at BETWEEN '".$from_date."' AND '".$to_date."' OR seizures.created_at IS NULL
                                ) a
                        )b 
                    GROUP BY storage_name,quantity_unit_name
                    ORDER BY storage_name";

                $sql2 = "SELECT SUM(A) disposal_quantity,SUM(coalesce(B,0)) undisposed_quantity,quantity_unit_name as unit_name
                    FROM
                        (
                            SELECT quantity_unit_name,
                            CASE 
                                    WHEN disposal_flag = 'Y' THEN disposal ELSE 0 END as A,
                            CASE
                                    WHEN disposal_flag = 'Y' THEN seizure-sample-disposal 
                                    ELSE seizure-coalesce(sample, 0) END as B
                            from 
                            (
                                select 
                                CASE    WHEN u1.unit_degree = 2 THEN quantity_of_drug/1000 
                                        WHEN u1.unit_degree = 1 THEN quantity_of_drug/1000000
                                        WHEN u1.unit_degree = 3 THEN quantity_of_drug
                                        WHEN u1.unit_degree = 0 THEN quantity_of_drug
                                        END AS seizure,
                                
                                CASE    WHEN u1.unit_name ilike 'g%m'  THEN 'KG'
                                        WHEN u1.unit_name ilike 'l%e' OR u1.unit_name ilike 'm%l'  THEN 'KL'
                                        ELSE u1.unit_name
                                        END AS quantity_unit_name,
                                
                                
                                CASE    WHEN u2.unit_degree = 2 THEN disposal_quantity/1000
                                        WHEN u2.unit_degree = 1 THEN disposal_quantity/1000000
                                        WHEN u2.unit_degree = 3 THEN disposal_quantity
                                        WHEN u2.unit_degree = 0 THEN disposal_quantity
                                        END AS disposal,
                                
                                
                                CASE    WHEN u3.unit_degree = 2 THEN quantity_of_sample/1000
                                        WHEN u3.unit_degree = 1 THEN quantity_of_sample/1000000
                                        WHEN u3.unit_degree = 3 THEN quantity_of_sample
                                        WHEN u3.unit_degree = 0 THEN quantity_of_sample
                                        END AS sample, disposal_flag
                                
                            
                                from narcotics left join seizures on seizures.drug_id = narcotics.drug_id 
                                left join units as u1 on seizures.seizure_quantity_weighing_unit_id=u1.unit_id 
                                left join units as u2  on seizures.disposal_quantity_weighing_unit_id=u2.unit_id 
                                left join units as u3  on seizures.sample_quantity_weighing_unit_id=u3.unit_id 
                                WHERE seizures.created_at BETWEEN '".$from_date."' AND '".$to_date."' and seizures.drug_id = ".$narcotic->drug_id."
                                ) a
                        )b
                    GROUP BY quantity_unit_name";
        

            $report['data'] = DB::select($sql);
            $report['narcotic_name'] = $narcotic->drug_name;
            $report['total_value'] = DB::select($sql2);
            
            $record[] = $report;            

        }

        echo json_encode($record);

    }

    /*Disposed Undisposed Tally :End */
}

