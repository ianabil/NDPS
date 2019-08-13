<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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




class EntryFormController extends Controller
{
    
    public function index()
    {
        $user_type = Auth::user()->user_type;

        $data = array();
        
        $ps_id = Auth::user()->ps_id;
        
        $data['stakeholders'] = Ps_detail::where('ps_id',$ps_id)
                                        ->select('ps_id as stakeholder_id','ps_name as stakeholder_name')
                                        ->get();        

        $data['narcotics'] = Narcotic::where('display','Y')
                                        ->select('drug_id','drug_name')
                                        ->orderBy('drug_name')
                                        ->get();

        $data['ndps_courts'] = NdpsCourtDetail::join('ps_details','ps_details.district_id','=','ndps_court_details.district_id')
                                        ->where('ps_details.ps_id',Auth::user()->ps_id)
                                        ->select('ndps_court_id','ndps_court_name','ndps_court_details.district_id')
                                         ->orderBy('ndps_court_name')
                                        ->get();

        $data['certifying_courts'] = CertifyingCourtDetail::join('ps_details','ps_details.district_id','=','certifying_court_details.district_id')
                                        ->where('ps_details.ps_id',Auth::user()->ps_id)
                                        ->select('court_id','court_name')
                                         ->orderBy('court_name')
                                        ->get();

        
        $data['storages'] = Storage_detail::join('ps_details','storage_details.district_id','=','ps_details.district_id')
                                        ->where([
                                            ['ps_details.ps_id',Auth::user()->ps_id],
                                            ['display','Y']
                                        ])
                                        ->select('storage_id','storage_name')
                                        ->orderBy('storage_name')
                                        ->get(); 

        $data['agencies'] = Agency_detail::select('agency_id','agency_name')
                                            ->orderBy('agency_name')
                                            ->get(); 
    
        return view('entry_form',compact('data'));  
    
    }


    public function index_non_fir_case()
    {
        $user_type = Auth::user()->user_type;

        $data = array();
        

        $data['narcotics'] = Narcotic::where('display','Y')
                                        ->select('drug_id','drug_name')
                                        ->orderBy('drug_name')
                                        ->get();
        $data['ndps_courts'] = NdpsCourtDetail::select('ndps_court_id','ndps_court_name','ndps_court_details.district_id')
                                                ->orderBy('ndps_court_name')
                                                ->get();

        $data['certifying_courts'] = CertifyingCourtDetail::select('court_id','court_name')
                                                        ->orderBy('court_name')
                                                        ->get();

        $data['storages'] = Storage_detail::where('display','Y')
                                            ->select('storage_id','storage_name')
                                            ->orderBy('storage_name')
                                            ->get(); 

        return view('non_fir_case',compact('data'));  
    
    }


    
    public function store(Request $request)
    {
        $this->validate( $request, [ 
            'stakeholder' => 'required|integer',
            'case_no' => 'required|integer',
            'case_year' => 'required|integer',
            'case_no_string' => 'required',
            'case_initiated_by' => 'required',
            'narcotic_type' => 'required|array',
            'narcotic_type.*' => 'required|exists:narcotics,drug_id',
            'seizure_date' => 'required|array',
            'seizure_date.*' => 'required|date',
            'seizure_quantity' => 'required|array',
            'seizure_quantity.*' => 'required',
            'seizure_weighing_unit' => 'required|array',
            'seizure_weighing_unit.*' => 'required|exists:units,unit_id',
            'storage' => 'required|integer',
            'remark' => 'nullable|max:255',
            'district' => 'required|exists:districts,district_id',         
            'ndps_court' => 'required|exists:ndps_court_details,ndps_court_id',
            'certifying_court' => 'required|exists:certifying_court_details,court_id'
        ] ); 
       
        $user_type = Auth::user()->user_type;
        
        if($user_type=="ps"){
            $ps = $request->input('stakeholder');
            $agency_id = null;

            $case_initiated_by = $request->input('case_initiated_by');
            if($case_initiated_by=="agency")
                $agency_id = $request->input('agency_name');
        }
        else if($user_type=="agency"){
            $agency_id = $request->input('stakeholder');
            $ps = null;
        } 

        $case_no = $request->input('case_no'); 
        $case_year = $request->input('case_year');
        $case_no_string = $request->input('case_no_string'); 
        $narcotic_type = $request->input('narcotic_type');         
        $seizure_date = $request->input('seizure_date'); 
        $seizure_quantity = $request->input('seizure_quantity'); 
        $seizure_weighing_unit = $request->input('seizure_weighing_unit');
        $storage = $request->input('storage');
        $remark = $request->input('remark');
        $district = $request->input('district'); 
        $ndps_court = $request->input('ndps_court');
        $certifying_court = $request->input('certifying_court');
        $certification_flag='N';
        $disposal_flag='N';
        $user_name=Auth::user()->user_name;
        $update_date = Carbon::today();  
        $uploaded_date = Carbon::today();  
        $flag_other_narcotic = $request->input('flag_other_narcotic'); 
        $other_narcotic_name = $request->input('other_narcotic_name'); 
        $flag_other_storage = $request->input('flag_other_storage'); 
        $other_storage_name = $request->input('other_storage_name'); 
        
        for($i=0;$i<sizeof($narcotic_type);$i++){
            if($flag_other_narcotic[$i]==1){

                $count = Narcotic::where('drug_name','ILIKE',trim($other_narcotic_name[$i]))
                                    ->count();

                if($count<1){

                    Narcotic::insert([
                        'drug_name' => trim(strtoupper($other_narcotic_name[$i])),
                        'display' => 'N',
                        'created_at'=>Carbon::today(),
                        'updated_at'=>Carbon::today()
                    ]);

                    $max_narcotic_value = Narcotic::max('drug_id');
                    $narcotic_type[$i] = $max_narcotic_value;
                }
                else{
                    $drug_id = Narcotic::where('drug_name','ILIKE',trim($other_narcotic_name[$i]))
                                        ->max('drug_id');
                    $narcotic_type[$i] = $drug_id;
                }
            }


            if($flag_other_storage==1){

                $count = Storage_detail::where('storage_name','ILIKE',trim($other_storage_name))
                                        ->count();

                if($count<1){

                    Storage_detail::insert([
                        'storage_name'=>trim(strtoupper($other_storage_name)),
                        'district_id' => $district,
                        'display' => 'N',
                        'created_at'=>Carbon::today(),
                        'updated_at'=>Carbon::today()
                    ]);

                    $max_storage_value = Storage_detail::max('storage_id');
                    $storage = $max_storage_value;
                }
                else{
                    $storage_id = Storage_detail::where('storage_name','ILIKE',trim($other_storage_name))
                                        ->max('storage_id');
                    $storage = $storage_id;
                }
            }

            seizure::insert(
                    [
                        'ps_id'=>$ps,
                        'case_no'=>$case_no,
                        'case_year'=>$case_year,
                        'case_no_string'=>$case_no_string,
                        'drug_id'=> $narcotic_type[$i],
                        'quantity_of_drug'=>$seizure_quantity[$i],
                        'seizure_quantity_weighing_unit_id'=>$seizure_weighing_unit[$i],
                        'date_of_seizure'=>date('Y-m-d', strtotime($seizure_date[$i])),
                        'storage_location_id'=>$storage,
                        'agency_id'=>$agency_id,
                        'district_id'=>$district,
                        'ndps_court_id'=>$ndps_court,
                        'certification_court_id'=>$certifying_court,
                        'certification_flag'=>$certification_flag,
                        'disposal_flag'=>$disposal_flag,
                        'remarks'=>$remark,
                        'user_name'=>$user_name,
                        'created_at'=>$uploaded_date,
                        'updated_at'=>$update_date
                    ]

                );
                
        }

        return 1;

    }

    

    // District wise NDPS Court fetching
    public function district_wise_court(Request $request){

        $district = $request->input('district'); 

        $data['district_wise_court']=CertifyingCourtDetail::
                                    where('district_id','=', $district )
                                    ->get();

        echo json_encode($data);

    }

    
    // Narcotic wise unit fetching
    public function narcotic_units(Request $request){

        $narcotic = $request->input('narcotic'); 
        $flag_other_narcotic = $request->input('flag_other_narcotic'); 
        $display = $request->input('display'); 
        
        // This part is for bringing units in the seizure screen
        if($flag_other_narcotic!=""){
            if($flag_other_narcotic==0){
                    $data['units']=Narcotic_unit::join('units',"narcotic_units.unit_id","=","units.unit_id")
                                    ->select('units.unit_id','unit_name','unit_degree')
                                    ->where('narcotic_id','=', $narcotic )
                                    ->get();
                                    
            }
            else if($flag_other_narcotic==1){
                    $data['units']=Unit::get();
            }
        }
        // This part is for bringing units in the disposal screen
        else if($display!=""){
            if($display=='Y'){
                $data['units']=Narcotic_unit::join('units',"narcotic_units.unit_id","=","units.unit_id")
                                        ->select('units.unit_id','unit_name','unit_degree')
                                        ->where('narcotic_id','=', $narcotic )
                                        ->get();
            }
            else if($display=='N'){
                $data['units']= Unit::get();
            }
        }

        echo json_encode($data);

    }


    //Fetch case details of a specific case no.
    public function fetch_case_details(Request $request){
        $user_type = Auth::user()->user_type;
        
        if($user_type=="ps"){
            $stakeholder = $request->input('stakeholder');
            $case_no = $request->input('case_no');
            $case_year = $request->input('case_year');

            $data['case_details'] = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                        ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                        ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                        ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                        ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                        ->leftjoin('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                        ->join('certifying_court_details','seizures.certification_court_id','=','certifying_court_details.court_id')
                        ->join('ndps_court_details','seizures.ndps_court_id','=','ndps_court_details.ndps_court_id')
                        ->where([
                            ['seizures.ps_id',$stakeholder],
                            ['seizures.case_no',$case_no],
                            ['seizures.case_year',$case_year]
                        ])                        
                        ->select('seizures.agency_id','seizures.ps_id','drug_name','narcotics.display','narcotics.drug_id','quantity_of_drug','seizure_quantity_weighing_unit_id',
                                'u1.unit_name AS seizure_unit','u1.unit_degree AS seizure_unit_degree','date_of_seizure','date_of_disposal',
                                'disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit', 'storage_name',
                                'court_name','ndps_court_details.ndps_court_id','ndps_court_name','date_of_certification',
                                'certification_flag','quantity_of_sample','u2.unit_name AS sample_unit',
                                'remarks','magistrate_remarks', 'storage_location_id', 'seizures.certification_court_id')
                        ->get();

        }
        else if($user_type=="agency"){
            $case_no_string = $request->input('case_no_string');
            $data['case_details'] = Seizure::join('agency_details','seizures.agency_id','=','agency_details.agency_id')
                        ->leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                        ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                        ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                        ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                        ->leftjoin('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                        ->join('certifying_court_details','seizures.certification_court_id','=','certifying_court_details.court_id')
                        ->join('ndps_court_details','seizures.ndps_court_id','=','ndps_court_details.ndps_court_id')
                        ->where([
                            ['seizures.agency_id',Auth::user()->agency_id],
                            ['seizures.case_no_string','ilike',$case_no_string]
                        ])                        
                        ->select('seizures.agency_id','seizures.ps_id','drug_name','narcotics.display','narcotics.drug_id','quantity_of_drug','seizure_quantity_weighing_unit_id',
                                'u1.unit_name AS seizure_unit','date_of_seizure','date_of_disposal',
                                'disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit','storage_name',
                                'court_name','ndps_court_details.ndps_court_id','ndps_court_name','date_of_certification',
                                'certification_flag','quantity_of_sample','u2.unit_name AS sample_unit',
                                'remarks','magistrate_remarks', 'storage_location_id', 'seizures.certification_court_id')
                        ->get();

        }
        
        foreach($data['case_details'] as $case_details){
            $case_details->date_of_seizure = Carbon::parse($case_details->date_of_seizure)->format('d-m-Y');
            if($case_details->certification_flag=='Y')
                $case_details->date_of_certification = Carbon::parse($case_details->date_of_certification)->format('d-m-Y');
            if($case_details->disposal_flag=='Y')
                $case_details->date_of_disposal = Carbon::parse($case_details->date_of_disposal)->format('d-m-Y');
            if($case_details->magistrate_remarks==null)
                $case_details->magistrate_remarks = "";
        }

        echo json_encode($data);
    }


    // Do Dispose
    public function dispose(Request $request){
        
        $this->validate ( $request, [ 
            'case_no_string' => 'required',
            'narcotic_type' => 'required|integer',
            'disposal_date' => 'required|date',
            'disposal_quantity' => 'required|numeric',
            'disposal_weighing_unit' => 'required|integer'
        ] ); 

        
        $case_no_string = $request->input('case_no_string');
        $narcotic_type = $request->input('narcotic_type');
        $disposal_date = Carbon::parse($request->input('disposal_date'))->format('Y-m-d');
        $disposal_quantity = $request->input('disposal_quantity'); 
        $disposal_weighing_unit = $request->input('disposal_weighing_unit');

        $data = [
            'disposal_flag'=>'Y',
            'date_of_disposal'=>$disposal_date,
            'disposal_quantity'=>$disposal_quantity,
            'disposal_quantity_weighing_unit_id'=>$disposal_weighing_unit,
            'updated_at'=>Carbon::today()
        ];

        
        Seizure::where([
            ['case_no_string',$case_no_string],
            ['drug_id',$narcotic_type]
        ])->update($data);
        
        
        return 1;
        
    }   
    
    
    // Fetching all narcotics
    public function fetch_narcotics(Request $request){
        $data = Narcotic::where('display','Y')
                        ->select('drug_id','drug_name')
                        ->get();

        echo json_encode($data);
    }

    // Save New Seizure Details 
    public function add_new_seizure_details(Request $request){

        $this->validate( $request, [ 
            'case_no_string' => 'required|exists:seizures,case_no_string',
            'narcotic_type' => 'required|integer',
            'seizure_date' => 'required|date',
            'seizure_quantity' => 'required|numeric',
            'seizure_weighing_unit' => 'required|integer',
        ] ); 

        $case_no_string = $request->input('case_no_string');
        $narcotic_type = $request->input('narcotic_type');
        $seizure_date = Carbon::parse($request->input('seizure_date'))->format('Y-m-d');
        $seizure_quantity = $request->input('seizure_quantity');
        $seizure_weighing_unit = $request->input('seizure_weighing_unit');
        $other_narcotic_name = $request->input('other_narcotic_name');
        $flag_other_narcotic = $request->input('flag_other_narcotic');
        $certification_flag='N';
        $disposal_flag='N';
        $update_date = Carbon::today(); 

        
        if($flag_other_narcotic==1){

            $count = Narcotic::where('drug_name','ILIKE',trim($other_narcotic_name))
                                ->count();

            if($count<1){

                Narcotic::insert([
                    'drug_name' => trim($other_narcotic_name),
                    'display' => 'N',
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                ]);

                $max_narcotic_value = Narcotic::max('drug_id');
                $narcotic_type = $max_narcotic_value;
            }
            else{
                $drug_id = Narcotic::where('drug_name','ILIKE',trim($other_narcotic_name))
                                    ->max('drug_id');
                $narcotic_type = $drug_id;
            }
        }

        $case_details = Seizure::where('case_no_string','ilike',$case_no_string)
                                ->get();

        Seizure::insert(
                [
                    'ps_id'=>$case_details[0]['ps_id'],
                    'case_no'=>$case_details[0]['case_no'],
                    'case_year'=>$case_details[0]['case_year'],
                    'case_no_string'=>$case_no_string,
                    'drug_id'=> $narcotic_type,
                    'quantity_of_drug'=>$seizure_quantity,
                    'seizure_quantity_weighing_unit_id'=>$seizure_weighing_unit,
                    'date_of_seizure'=>Date('Y-m-d', strtotime($seizure_date)),
                    'storage_location_id'=>$case_details[0]['storage_location_id'],
                    'agency_id'=>$case_details[0]['agency_id'],
                    'district_id'=>$case_details[0]['district_id'],
                    'ndps_court_id'=>$case_details[0]['ndps_court_id'],
                    'certification_court_id'=>$case_details[0]['certification_court_id'],
                    'certification_flag'=>$certification_flag,
                    'disposal_flag'=>$disposal_flag,
                    'remarks'=>$case_details[0]['remarks'],
                    'user_name'=>$case_details[0]['user_name'],
                    'created_at'=>$case_details[0]['created_at'],
                    'updated_at'=>$update_date
                ]

            );

        return 1;
    }


    public function monthly_report_status(Request $request){
        $user_type = Auth::user()->user_type;
        if($user_type=="ps")
            $ps_id = Auth::user()->ps_id;
        else if($user_type=="agency")
            $agency_id = Auth::user()->agency_id;

        $start_date = date('Y-m-d', strtotime('01-'.$request->input('month')));
        $end_date = date('Y-m-d', strtotime($start_date. ' +30 days'));
        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'CaseNo',
            1=>'More Details',
            2=>'Sl No',
            3 =>'Case_No',
            4 =>'Narcotic Type',
            5 =>'Certification Status',
            6 =>'Disposal Status',
            7 => 'Magistrate' 
        );

        // Fetching unique Case No. As Multiple Row May Exist For A Single Case No.
        if($user_type=="ps"){
            $cases = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                            ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                            ->join('certifying_court_details','seizures.certification_court_id','=','certifying_court_details.court_id')
                            ->where([
                                ['seizures.created_at','>=',$start_date],
                                ['seizures.created_at','<=',$end_date],
                                ['seizures.ps_id',$ps_id],
                                ['seizures.legacy_data_flag','N']
                            ])
                            ->orWhere([
                                ['seizures.updated_at','>=',$start_date],
                                ['seizures.updated_at','<=',$end_date],
                                ['seizures.ps_id',$ps_id],
                                ['seizures.legacy_data_flag','N']
                            ])
                            ->select('seizures.ps_id','seizures.agency_id','case_no_string','seizures.created_at','ps_name','agency_name','court_name')
                            ->orderBy('seizures.created_at','DESC')
                            ->distinct()
                            ->get();
        }
        else if($user_type=="agency"){
            $cases = Seizure::leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                            ->join('agency_details','seizures.agency_id','=','agency_details.agency_id')
                            ->join('certifying_court_details','seizures.certification_court_id','=','certifying_court_details.court_id')
                            ->where([
                                ['seizures.created_at','>=',$start_date],
                                ['seizures.created_at','<=',$end_date],
                                ['seizures.agency_id',$agency_id]
                            ])
                            ->orWhere([
                                ['seizures.updated_at','>=',$start_date],
                                ['seizures.updated_at','<=',$end_date],
                                ['seizures.agency_id',$agency_id]
                            ])
                            ->select('seizures.ps_id','seizures.agency_id','case_no_string','seizures.created_at','ps_name','agency_name','court_name')
                            ->orderBy('seizures.created_at','DESC')
                            ->distinct()
                            ->get();
        }
        
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
                    $report['Case_No'] = "<strong>".$case->case_no_string."</strong><br>(Case Initiated By: ".$case->agency_name.")<small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Case_No'] = "<strong>".$case->case_no_string."</strong><br>(Case Initiated By: ".$case->agency_name.")";
            }
            //If Case Initiated By Any PS
            else if($case->ps_id!=null && $case->agency_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Case_No'] = "<strong>".$case->case_no_string."</strong><small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Case_No'] = "<strong>".$case->case_no_string."</strong>";
            }
            //If Case Inserted By Agency
            else if($case->ps_id==null){
                //If submitted date is within 10 days of present date, a new marker will be shown
                if(((strtotime(date('Y-m-d')) - strtotime($case->created_at)) / (60*60*24) <=10))
                    $report['Case_No'] = "<strong>".$case->case_no_string."</strong> <small class='label pull-right bg-blue'>new</small>";
                else
                    $report['Case_No'] = "<strong>".$case->case_no_string."</strong>";
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


    public function fetch_more_details(Request $request){
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
