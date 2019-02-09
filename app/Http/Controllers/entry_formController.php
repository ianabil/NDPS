<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Narcotic;
use App\District;
use App\Unit;
use App\Agency_detail;
use App\Court_detail;
use App\Magistrate;
use App\Ps_detail;
use App\Seizure;
use App\Storage_detail;
use App\User;
use App\User_detail;
use Carbon\Carbon;
use DB;




class entry_formController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        
        $data['drugs'] = Narcotic::select('drug_id','drug_name')->get();
        $data['districts'] = District::select('district_id','district_name')->get();
        $data['units'] = Unit::select('unit_id','unit_name')->get();
        $data['courts'] = Court_detail::select('court_id','court_name')->get();
            
        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name
        from seizures s inner join units u1 on cast(s.unit_name as int)=u1.unit_id 
        inner join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        inner join units u3 on cast(s.undisposed_unit as int)=u3.unit_id where s.submit_flag='N' and s.agency_id='1' order by seizure_id";

        $data['seizures']=DB::select($sql);

        foreach($data['seizures'] as $seizures){
            $seizures->date_of_seizure = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            $seizures->date_of_disposal = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');
            $seizures->date_of_certification = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
        }
            

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

        seizure::where('submit_flag','N')->delete();

        $nature_of_narcotic = $request->input('nature_of_narcotic'); 
        $quantity_of_narcotics = $request->input('quantity_of_narcotics'); 
        $narcotic_unit = $request->input('narcotic_unit'); 
        $date_of_seizure =$request->input('date_of_seizure'); 
        $date_of_disposal =$request->input('date_of_disposal'); 
        $disposal_quantity = $request->input('disposal_quantity');
        $disposal_unit = $request->input('disposal_unit');
        $undisposed_quantity = $request->input('undisposed_quantity');
        $unit_of_undisposed_quantity = $request->input('unit_of_undisposed_quantity'); 
        $place_of_storage = $request->input('place_of_storage'); 
        $case_details = $request->input('case_details'); 
        $district = $request->input('district'); 
        $where = $request->input('where'); 
        $date_of_certification = $request->input('date_of_certification'); 
        $counter= $request->input('counter');
        $agency_id= 1;
        /*$court_id='';*/
        $user_name="CID";
        $remarks=$request->input('remarks');
        $update_date = Carbon::today();  
        $uploaded_date = Carbon::today();  
        $submit_flag=$request->input('submit_flag');

       
        for($i=0;$i<$counter;$i++)
        {

            seizure::insert(

                ['drug_name'=>$nature_of_narcotic[$i],
                 'quantity_of_drug'=>$quantity_of_narcotics[$i],
                 'unit_name'=>$narcotic_unit[$i],
                 'date_of_seizure'=> date('Y-m-d', strtotime($date_of_seizure[$i])),
                 'date_of_disposal'=>date('Y-m-d', strtotime($date_of_disposal[$i])),
                 'disposal_quantity'=>$disposal_quantity[$i],
                 'unit_of_disposal_quantity'=>$disposal_unit[$i],
                 'undisposed_quantity'=>$undisposed_quantity[$i],
                 'undisposed_unit'=>$unit_of_undisposed_quantity[$i],
                 'storage_location'=>$place_of_storage[$i],
                 'case_details'=>$case_details[$i],
                 'district_id'=>$district[$i],
                 'date_of_certification'=>date('Y-m-d', strtotime($date_of_certification[$i])),
                 'agency_id'=>$agency_id,
                 //'court_id'=>$court_id;
                 'certification_court_id'=>$where[$i],
                 'remarks'=>$remarks[$i],
                 'updated_at'=>$update_date,
                 'created_at'=>$uploaded_date,
                 'user_name'=>$user_name,
                 'submit_flag'=>$submit_flag
                 ]

            );

        }
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
}
