<?php

namespace App\Http\Controllers;

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

class LegacyDataController extends Controller
{    
    public function index()
    {
        $data = array();
        
        $data['ps'] = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                ->where('ps_details.district_id',Auth::user()->district_id)
                                ->select('ps_id','ps_name')
                                ->orderBy('ps_name')
                                ->get();

        $data['narcotics'] = Narcotic::where('display','Y')
                                        ->select('drug_id','drug_name')
                                        ->orderBy('drug_name')
                                        ->get();

        $data['districts'] = District::select('district_id','district_name')
                                        ->orderBy('district_name')
                                        ->get();

        $data['storages'] = Storage_detail::where('display','Y')
                                            ->select('storage_id','storage_name')
                                            ->orderBy('storage_name')
                                            ->get(); 

        $data['agencies'] = Agency_detail::select('agency_id','agency_name')
                                            ->orderBy('agency_name')
                                            ->get(); 
        
        return view('legacy_data_entry_form', compact('data'));
    }

    
    public function store(Request $request)
    {
        $this->validate( $request, [ 
            'stakeholder' => 'required|integer',
            'case_no' => 'required|integer',
            'case_year' => 'required|integer',
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
            'court' => 'required|exists:court_details,court_id',
        ] ); 
      

        $ps = $request->input('stakeholder');
        $agency_id = $request->input('agency_name');
        $case_no = $request->input('case_no'); 
        $case_year = $request->input('case_year'); 
        $narcotic_type = $request->input('narcotic_type');         
        $seizure_date = $request->input('seizure_date'); 
        $seizure_quantity =$request->input('seizure_quantity'); 
        $seizure_weighing_unit = $request->input('seizure_weighing_unit');
        $storage = $request->input('storage');
        $remark = $request->input('remark');
        $district = $request->input('district'); 
        $court = $request->input('court');
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
                        'drug_name' => trim($other_narcotic_name[$i]),
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
                        'storage_name'=>trim($other_storage_name),
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
                        'drug_id'=> $narcotic_type[$i],
                        'quantity_of_drug'=>$seizure_quantity[$i],
                        'seizure_quantity_weighing_unit_id'=>$seizure_weighing_unit[$i],
                        'date_of_seizure'=>date('Y-m-d', strtotime($seizure_date[$i])),
                        'storage_location_id'=>$storage,
                        'agency_id'=>$agency_id,
                        'district_id'=>$district,
                        'certification_court_id'=>$court,
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
            'narcotic_type' => 'required|integer',
            'seizure_date' => 'required|date',
            'seizure_quantity' => 'required|numeric',
            'seizure_weighing_unit' => 'required|integer',
        ] ); 

        $user_type = $request->input('stakeholder_type');
        
        if($user_type=="ps"){
            $ps = $request->input('stakeholder');
            $agency_id = null;
        }
        else if($user_type=="agency"){
            $agency_id = $request->input('stakeholder');
            $ps = null;
        }
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');
        $narcotic_type = $request->input('narcotic_type');
        $seizure_date = Carbon::parse($request->input('seizure_date'))->format('Y-m-d');
        $seizure_quantity = $request->input('seizure_quantity');
        $seizure_weighing_unit = $request->input('seizure_weighing_unit');
        $other_narcotic_name = $request->input('other_narcotic_name');
        $flag_other_narcotic = $request->input('flag_other_narcotic');
        $narcotic_type = $request->input('narcotic_type');
        $seizure_date = $request->input('seizure_date');
        $seizure_quantity = $request->input('seizure_quantity');
        $seizure_weighing_unit = $request->input('seizure_weighing_unit');
        $storage = $request->input('storage');
        $district = $request->input('district');
        $court = $request->input('court');
        $remark = $request->input('remark');
        $certification_flag='N';
        $disposal_flag='N';
        $user_name=Auth::user()->user_name;
        $update_date = Carbon::today();  
        $uploaded_date = Carbon::today(); 

        
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

        seizure::insert(
                [
                    'ps_id'=>$ps,
                    'case_no'=>$case_no,
                    'case_year'=>$case_year,
                    'drug_id'=> $narcotic_type,
                    'quantity_of_drug'=>$seizure_quantity,
                    'seizure_quantity_weighing_unit_id'=>$seizure_weighing_unit,
                    'date_of_seizure'=>Date('Y-m-d', strtotime($seizure_date)),
                    'storage_location_id'=>$storage,
                    'agency_id'=>$agency_id,
                    'district_id'=>$district,
                    'certification_court_id'=>$court,
                    'certification_flag'=>$certification_flag,
                    'disposal_flag'=>$disposal_flag,
                    'remarks'=>$remark,
                    'user_name'=>$user_name,
                    'created_at'=>$uploaded_date,
                    'updated_at'=>$update_date
                ]

            );

        return 1;
    }


    // District wise NDPS Court fetching
    public function district_wise_court(Request $request){

        $district = $request->input('district'); 

        $data['district_wise_court']=Court_detail::
                                    where('district_id','=', $district )
                                    ->get();

        echo json_encode($data);

    }

    // PS wise District fetching
    public function stakeholder_wise_district(Request $request){
        $user_type = $request->input('user_type');

        if($user_type=="ps"){

            $stakeholder_id = $request->input('stakeholder'); 

            $data['stakeholder_wise_district']=Ps_detail::join("districts","ps_details.district_id","=","districts.district_id")
                                        ->where('ps_id','=', $stakeholder_id)
                                        ->get();
        }
        else if($user_type=="agency"){
            $data['stakeholder_wise_district']=District::orderBy('district_name')
                                                ->get();
        }

        echo json_encode($data);

    }


    // Do Dispose
    public function dispose(Request $request){
        
        $this->validate ( $request, [ 
            'stakeholder' => 'required|integer',
            'case_no' => 'required|integer',
            'case_year' => 'required|integer',
            'narcotic_type' => 'required|integer',
            'disposal_date' => 'required|date',
            'disposal_quantity' => 'required|numeric',
            'disposal_weighing_unit' => 'required|integer'
        ] ); 

        $user_type = $request->input('stakeholder_type');        
         
        $case_no = $request->input('case_no'); 
        $case_year = $request->input('case_year');
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

        if($user_type=="ps"){
            $ps = $request->input('stakeholder');

            Seizure::where([['ps_id',$ps],
                        ['case_no',$case_no],
                        ['case_year',$case_year],
                        ['drug_id',$narcotic_type]
                    ])->update($data);
        
        }
        else if($user_type=="agency"){
            $agency_id = $request->input('stakeholder');

            Seizure::where([
                        ['agency_id',$agency_id],
                        ['case_no',$case_no],
                        ['case_year',$case_year],
                        ['drug_id',$narcotic_type]
                    ])->update($data);
        
        }        
        
        return 1;
        
    }   


    //Fetch case details of a specific case no.
    public function fetch_case_details(Request $request){
        $stakeholder = $request->input('stakeholder');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');

        $user_type = $request->input('stakeholder_type');
        
        if($user_type=="ps"){
            $data['case_details'] = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->leftjoin('agency_details','seizures.agency_id','=','agency_details.agency_id')
                        ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                        ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                        ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                        ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                        ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                        ->join('court_details','seizures.certification_court_id','=','court_details.court_id')
                        ->join('districts','seizures.district_id','=','districts.district_id')
                        ->where([['seizures.ps_id',$stakeholder],['seizures.case_no',$case_no],['seizures.case_year',$case_year]])                        
                        ->select('seizures.agency_id','seizures.ps_id','drug_name','narcotics.display','narcotics.drug_id','quantity_of_drug','seizure_quantity_weighing_unit_id',
                                'u1.unit_name AS seizure_unit','u1.unit_degree AS seizure_unit_degree','date_of_seizure','date_of_disposal',
                                'disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit', 'storage_name',
                                'court_name','districts.district_id','district_name','date_of_certification',
                                'certification_flag','quantity_of_sample','u2.unit_name AS sample_unit',
                                'remarks','magistrate_remarks', 'storage_location_id', 'seizures.certification_court_id')
                        ->get();

        }
        else if($user_type=="agency"){
            $data['case_details'] = Seizure::join('agency_details','seizures.agency_id','=','agency_details.agency_id')
                        ->leftjoin('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                        ->join('units AS u1','seizures.seizure_quantity_weighing_unit_id','=','u1.unit_id')
                        ->leftjoin('units AS u2','seizures.sample_quantity_weighing_unit_id','=','u2.unit_id')
                        ->leftjoin('units AS u3','seizures.disposal_quantity_weighing_unit_id','=','u3.unit_id')
                        ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                        ->join('court_details','seizures.certification_court_id','=','court_details.court_id')
                        ->join('districts','seizures.district_id','=','districts.district_id')
                        ->where([['seizures.agency_id',$stakeholder],['seizures.case_no',$case_no],['seizures.case_year',$case_year]])                        
                        ->select('seizures.agency_id','seizures.ps_id','drug_name','narcotics.display','narcotics.drug_id','quantity_of_drug','seizure_quantity_weighing_unit_id',
                                'u1.unit_name AS seizure_unit','date_of_seizure','date_of_disposal',
                                'disposal_quantity','disposal_flag','u3.unit_name AS disposal_unit','storage_name',
                                'court_name','districts.district_id','district_name','date_of_certification',
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


    // Do certification
    public function certify(Request $request){
        $this->validate ( $request, [ 
            'stakeholder' => 'required|integer',
            'case_no' => 'required|integer',
            'case_year' => 'required|integer',
            'narcotic_type' => 'required|integer',
            'sample_quantity' => 'required|numeric',
            'sample_weighing_unit' => 'required|integer',
            'certification_date' => 'required|date',
            'magistrate_remarks' => 'nullable|max:255'
        ] ); 

        $stakeholder = $request->input('stakeholder'); 
        $stakeholder_type = $request->input('stakeholder_type'); 
        $case_no = $request->input('case_no'); 
        $case_year = $request->input('case_year');
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

        if($stakeholder_type=='ps'){
            Seizure::where([
                ['ps_id',$stakeholder],
                ['case_no',$case_no],
                ['case_year',$case_year],
                ['drug_id',$narcotic_type]
            ])->update($data);
        }
        else if($stakeholder_type=='agency'){
            Seizure::where([
                ['agency_id',$stakeholder],
                ['case_no',$case_no],
                ['case_year',$case_year],
                ['drug_id',$narcotic_type]
            ])->update($data);
        }
        
        return 1;
        
    }
    

}
