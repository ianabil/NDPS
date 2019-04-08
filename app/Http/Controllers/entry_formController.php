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




class entry_formController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agency_id =Auth::user()->stakeholder_id;

        $data = array();
        
        $data['ps'] = Ps_detail::select('ps_id','ps_name')->get();
        $data['narcotics'] = Narcotic::select('drug_id','drug_name')->distinct()->get();
        $data['districts'] = District::select('district_id','district_name')->get();
        $data['storages'] = Storage_detail::select('storage_id','storage_name')->get();
        $data['units'] = Unit::select('unit_id','unit_name')->get();
        //$data['courts'] = Court_detail::select('court_id','court_name')->get();
            
        // $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        // s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        // from seizures s left join units u1 on cast(s.unit_name as int)=u1.unit_id 
        // left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        // left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        // left join court_details on s.certification_court_id = court_details.court_id
        // left join districts on s.district_id = districts.district_id
        // where s.submit_flag='N' and s.agency_id= ".$agency_id." order by seizure_id";

        //$data['seizures']=DB::select($sql);

        // foreach($data['seizures'] as $seizures){
        //     if(empty($seizures->date_of_seizure))
        //         $seizures->date_of_seizure='';
        //     else
        //         $seizures->date_of_seizure = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            
            
        //     if(empty($seizures->date_of_disposal))
        //         $seizures->date_of_disposal='';
        //     else
        //         $seizures->date_of_disposal = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');


        //     if(empty($seizures->date_of_certification))
        //         $seizures->date_of_certification='';
        //     else
        //         $seizures->date_of_certification = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
        // }
            

        return view('entry_form',compact('data'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate ( $request, [ 
            'ps' => 'required|integer',
            'case_no' => 'required|integer',
            'case_year' => 'required|integer',
            'narcotic_type' => 'required|integer',
            'seizure_date' => 'required|date',
            'seizure_quantity' => 'required|numeric',
            'seizure_weighing_unit' => 'required|integer',
            'storage' => 'required|integer',
            'remark' => 'nullable|max:255',
            'district' => 'required|integer',         
            'court' => 'required|integer',
        ] ); 

        $ps = $request->input('ps'); 
        $case_no = $request->input('case_no'); 
        $case_year = $request->input('case_year'); 
        $narcotic_type = $request->input('narcotic_type'); 
        $seizure_date =$request->input('seizure_date'); 
        $seizure_quantity =$request->input('seizure_quantity'); 
        $seizure_weighing_unit = $request->input('seizure_weighing_unit');
        $storage = $request->input('storage');
        $remark = $request->input('remark');
        $district = $request->input('district'); 
        $court = $request->input('court');
        $certification_flag='N';
        $disposal_flag='N';
        $agency_id= Auth::user()->stakeholder_id;
        $user_name=Auth::user()->user_name;
        $update_date = Carbon::today();  
        $uploaded_date = Carbon::today();  
        

        seizure::insert(

            [
                'ps_id'=>$ps,
                'case_no'=>$case_no,
                'case_year'=>$case_year,
                'drug_id'=> $narcotic_type,
                'quantity_of_drug'=>$seizure_quantity,
                'seizure_quantity_weighing_unit_id'=>$seizure_weighing_unit,
                'date_of_seizure'=>date('Y-m-d', strtotime($seizure_date)),
                'storage_location_id'=>$storage,
                'stakeholder_id'=>$agency_id,
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
    public function district_wise_court(Request $request){

        $district = $request->input('district'); 

        $data['district_wise_court']=Court_detail::
                                    where('district_id','=', $district )
                                    ->get();

          

        echo json_encode($data);


    }

    
    
    public function submission_validation(Request $request){
        
        $month_of_report = date('Y-m-d',strtotime('01-'.$request->input('month_of_report'))); 
        $submit_flag='S';
        $agency_id=Auth::user()->stakeholder_id;
        $month_of_report=Seizure::where([['month_of_report','=', $month_of_report], 
                                    ['submit_flag','=', $submit_flag ],
                                    ['agency_id','=', $agency_id ]])
                                    ->count();

    
        echo json_encode($month_of_report);

    }
}
