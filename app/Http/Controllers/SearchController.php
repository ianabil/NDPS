<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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

        $data['districts'] = District::select('district_id','district_name')
                                        ->orderBy('district_name')
                                        ->get();

        $data['courts'] = Court_detail::select('court_id','court_name')
                                        ->orderBy('court_name')
                                        ->get();

        $data['storages'] = Storage_detail::where('display','Y')
                                            ->select('storage_id','storage_name')
                                            ->orderBy('storage_name')
                                            ->get(); 

        return view('search_highcourt',compact('data'));
    }

    public function show_highcourt_search_result(Request $request){
        
        /* Fetching values from view :: STARTS */
        $ps = $request->input('ps');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');
        $stakeholder = $request->input('stakeholder');
        $court = $request->input('court'); 
        $district = $request->input('district');
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
        $select = "SELECT DISTINCT seizures.ps_id, case_no, case_year, 
        seizures.created_at, ps_name, agency_name FROM ps_details INNER JOIN
        seizures ON ps_details.ps_id = seizures.ps_id INNER JOIN agency_details
        ON seizures.stakeholder_id = agency_details.agency_id";

        // Default WHERE condition
        $where = ' WHERE seizures.date_of_seizure IS NOT NULL';

        // Default Order By query
        $orderBy = ' ORDER BY seizures.created_at DESC';

        /* Building SELECT and WHERE part of the sql query based on the 
        selected different options in the view :: STARTS */
        // if($order_by!="")
        //     $orderBy = ' ORDER BY "books"."'.$order_by.'"';
        // if($order_type!="")
        //     $orderType = ' '.$order_type;

        if(!empty($ps))
            $where = $where.' AND seizures.ps_id ='.$ps;

        if(!empty($case_no))
            $where = $where.' AND seizures.case_no ='.$case_no;

        if(!empty($case_year))
           $where = $where.' AND seizures.case_year ='.$case_year;

        if(!empty($stakeholder))
            $where = $where.' AND seizures.stakeholder_id = '.$stakeholder;

        if(!empty($court))
            $where = $where.' AND seizures.certification_court_id = '.$court;

        if(!empty($district))
            $where = $where.' AND seizures.district_id ='.$district;

        if(!empty($narcotic_type))
            $where = $where.' AND seizures.drug_id ='.$narcotic_type;

        if(!empty($storage))
            $where = $where.' AND seizures.storage_id ='.$storage;

        if(!empty($seizure_from_date) && !empty($seizure_to_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$seizure_from_date'".' AND '."'$seizure_to_date'";
        
        if(!empty($certification_from_date) && !empty($certification_from_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$certification_from_date'".' AND '."'$certification_from_date'";

        if(!empty($disposal_from_date) && !empty($disposal_to_date))
            $where = $where.' AND seizures.date_of_seizure BETWEEN '."'$disposal_from_date'".' AND '."'$disposal_to_date'";

        if($certified_cases=="true")
            $where = $where." AND seizures.certification_flag ='Y'";
        else
            $where = $where." AND seizures.certification_flag ='N'";

        if($disposed_cases=="true")
            $where = $where." AND seizures.disposal_flag ='Y'";
        else
            $where = $where." AND seizures.disposal_flag ='N'";

        
        /* Building SELECT, WHERE clause of the sql query based on the 
        selected different options in the view :: ENDS */
          
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


        $limit = $request->input('length'); // For No. of rows per page
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

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
            "recordsTotal" => intval($totalData['0']->count),
            "recordsFiltered" =>intval($totalFiltered),
            "data" => $record
        );

        echo json_encode($json_data);

    }


    public function fetch_case_details(Request $request){
        $ps_id = $request->input('ps_id');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');

        $case_details = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                                ->join('agency_details','seizures.stakeholder_id','=','agency_details.agency_id')
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


    /* Searching Mechanism For High Court End :: ENDS */


    /*Disposed Undisposed Tally :Start */

       

    /*Disposed Undisposed Tally :End */
}
