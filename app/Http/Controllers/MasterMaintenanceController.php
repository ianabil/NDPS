<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Agency_detail;
use App\Court_detail;
use App\District;
use App\User;
use Carbon\Carbon;
use App\Seizure;
use App\Narcotic;
use App\Unit;
use DB;

class MasterMaintenanceController extends Controller
{
    //Add stakeholder
    public function store_stakeholder(Request $request){

        $this->validate ( $request, [ 
            'stakeholder_name' => 'required|max:255|unique:agency_details,agency_name',
            'district' => 'required|max:255'         
        ] ); 

        $stakeholder = strtoupper($request->input('stakeholder_name'));
        $district = strtoupper($request->input('district')); 

        Agency_detail::insert([
            'agency_name'=>$stakeholder,
            'district_for_report'=>$district,
            'created_at'=>Carbon::today(),
            'updated_at'=>Carbon::today()
            ]);

        return 1;
    }

  // Data Table Code for stakeholders
    public function get_all_stakeholders_data(Request $request){
        $columns = array( 
            0 =>'ID', 
            1 =>'STAKEHOLDER',
            2 =>'DISTRICT',
            3=>'ACTION'
        );

        $totalData = Agency_detail::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $stakeholder = Agency_detail::offset($start)
                            ->limit($limit)
                            ->orderBy('agency_name',$dir)
                            ->get();
            $totalFiltered = Agency_detail::count();
        }
        else{
            $search = strtoupper($request->input('search.value'));
            $stakeholder = Agency_detail::where('agency_id','like',"%{$search}%")
                                ->orWhere('agency_name','like',"%{$search}%")
                                ->orWhere('district_for_report','like',"%{$search}%")
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy('agency_name',$dir)
                                ->get();
            $totalFiltered = Agency_detail::where('agency_id','like',"%{$search}%")
                                    ->orWhere('agency_name','like',"%{$search}%")
                                    ->orWhere('district_for_report','like',"%{$search}%")
                                    ->count();
            }

            $data = array();

            if($stakeholder){
                foreach($stakeholder as $stakeholder){
                    $nestedData['ID'] = $stakeholder->agency_id;
                    $nestedData['STAKEHOLDER'] = $stakeholder->agency_name;
                    $nestedData['DISTRICT'] = $stakeholder->district_for_report;
                    $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
    
                    $data[] = $nestedData;
                }
                    $json_data = array(
                        "draw" => intval($request->input('draw')),
                        "recordsTotal" => intval($totalData),
                        "recordFiltered" =>intval($totalFiltered),
                        "data" => $data
                    );
            
                    echo json_encode($json_data);
                }
    
            }

            /*update stakeholder*/

            public function update_stakeholder(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required',
                    'stakeholder' => 'required|max:255',
                    'district' => 'required|max:255'          
                ] ); 

                
                $id = $request->input('id');
                $stakeholder = strtoupper($request->input('stakeholder'));
                $district = $request->input('district');

                $data = [
                    'agency_name'=>$stakeholder,
                    'updated_at'=>Carbon::today(),
                    'district_for_report'=>$district

                ];

                Agency_detail::where('agency_id',$id)->update($data);
                
                return 1;
            
            }

             //deleting stakeholder

                public function destroy_stakeholder(Request $request)
                {
                    $id = $request->input('id');
                    $count = Seizure::where('agency_id',$id)->count();
                
                    if($count>=1)
                        return 0;
                    else{
                        Agency_detail::where('agency_id',$id)->delete();
                        return 1;
                    }
                    // echo 1;
                }
          
                //court master maintenance view

                public function index_court(Request $request)
                {
                    $data= array();

                    $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();
                    

                    return view('court_view',compact('data'));
                }

                //showing exisiting courts

                public function get_all_court_details(Request $request){

                    $columns = array( 
                        0 =>'COURT ID', 
                        1 =>'COURT NAME',
                        2 =>'DISTRICT NAME',
                        3=>'ACTION'
                    );

                    $totalData = Court_detail::count();

                    $totalFiltered = $totalData; 

                    $limit = $request->input('length');
                    $start = $request->input('start');
                    $order = $columns[$request->input('order.0.column')];
                    $dir = $request->input('order.0.dir');


                    if(empty($request->input('search.value'))){

                        $court = Court_detail::
                                        join('districts','court_details.district_id','=','districts.district_id')                               
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('court_name',$dir)
                                        ->get();

                        $totalFiltered = Court_detail::count();
                    }
                    else{

                        $court = Court_detail::
                                        join('districts','court_details.district_id','=','districts.district_id')                               
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('court_name',$dir)
                                        ->get();
                            
                        $totalFiltered = Court_detail::
                                        join('districts','court_details.district_id','=','districts.district_id')                   
                                        ->where('court_id','like',"%{$search}%")
                                        ->orWhere('court_name','like',"%{$search}%")
                                        ->orWhere('district_name','like',"%{$search}%")
                                        ->count();


                        }

                    $data = Array();

                    if($court){
                        foreach($court as $court){
                            $nestedData['COURT ID'] = $court->court_id;
                            $nestedData['COURT NAME'] = $court->court_name;
                            $nestedData['DISTRICT NAME'] = $court->district_name;
                            $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
            
                            $data[] = $nestedData;
                        }
                            $json_data = array(
                                "draw" => intval($request->input('draw')),
                                "recordsTotal" => intval($totalData),
                                "recordFiltered" =>intval($totalFiltered),
                                "data" => $data
                            );
                    
                            echo json_encode($json_data);
                        }
            
                            }
                /*adding new court*/

                public function store_court(Request $request){

                    $this->validate ( $request, [ 
                        
                        'court_name' => 'required|max:255|unique:court_details,court_name',
                        'district_name' => 'required|integer|max:255'

                    ] ); 

                $court_name=strtoupper($request->input('court_name'));
                $district_name=strtoupper($request->input('district_name'));

                Court_detail::insert([
                    'court_name'=>$court_name,
                    'district_id'=>$district_name,
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                    ]);
                return 1;

            }
        
            /*Update court */
            public function update_court(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required',
                    'court_name' => 'required|max:255'         
                ] ); 

                
                $id = $request->input('id');
                $court_name= strtoupper($request->input('court_name'));

                $data = [
                    'court_name'=>$court_name,
                    'updated_at'=>Carbon::today()
                    ];         
                 Court_detail::where('court_id',$id)->update($data);
                
                 return 1;
            }


            //deleting court details
            public function destroy_court(Request $request)
            {
                $id = $request->input('id');
                $count = Seizure::where('certification_court_id',$id)->count();
                
                if($count>0)
                    return 0;
                else{
                    Court_detail::where('court_id',$id)->delete();
                    return 1;
                }
            }
            
        //Court:End
            
        //Narcotic:Start

            // Data Table Code for Narcotics
            public function index_narcotic()
            {
                $data=Unit::get();

                return view('narcotic_view',compact('data'));

            }

            public function get_all_narcotics_data(Request $request)
            {
                $columns = array( 
                    0 =>'ID', 
                    1 =>'NARCOTIC',
                    2=>'UNIT',
                    3=>'ACTION'
                );
        
                $totalData = Narcotic::join('units','units.unit_id','=','narcotics.drug_unit')
                                       ->count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $narcotic = Narcotic::join('units','units.unit_id','=','narcotics.drug_unit')                               
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy('drug_name',$dir)
                                    ->get();
                    $totalFiltered = Narcotic::join('units','units.unit_id','=','narcotics.drug_unit')
                                            ->count();
                }
                else
                {
                    $search = strtoupper($request->input('search.value'));
                    $narcotic = Narcotic::join('units','units.unit_id','=','narcotics.drug_unit')
                                        ->where('drug_id','like',"%{$search}%")
                                        ->orWhere('drug_name','like',"%{$search}%")                                    
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('drug_name',$dir)
                                        ->get();
                    $totalFiltered = Narcotic::join('units','units.unit_id','=','narcotics.drug_unit')
                                            ->store_courtwhere('drug_id','like',"%{$search}%")
                                            ->orWhere('drug_name','like',"%{$search}%")                                           
                                            ->count();
                }
        
                $data = array();
        
                if($narcotic)
                {
                    foreach($narcotic as $narcotic)
                    {
                        $nestedData['ID'] = $narcotic->drug_id;
                        $nestedData['NARCOTIC'] = $narcotic->drug_name;
                        $nestedData['UNIT'] = $narcotic->unit_name;
                        $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                }
            
            }


        //Add Narcotics


            public function store_narcotic(Request $request){
                $this->validate ( $request, [ 
                    'narcotic_name' => 'required|max:255|unique:narcotics,drug_name',
                    'narcotic_unit' => 'required|max:255'         
                ] ); 

                $narcotic = ucwords($request->input('narcotic_name'));
                $unit = $request->input('narcotic_unit');
                
                $narcotic_id = Narcotic::max('drug_id');
                for($i=0;$i<sizeof($unit);$i++){
                    Narcotic::insert([
                        'drug_id'=>$narcotic_id+1,
                        'drug_name'=>$narcotic,
                        'drug_unit'=>$unit[$i],
                        'created_at'=>Carbon::today(),
                        'updated_at'=>Carbon::today()
                        ]);
                }
        
                return 1;



                }

                //update Narcotics
                public function update_narcotics(Request $request){
                    $this->validate ( $request, [ 
                        'id' => 'required',
                        'narcotic' => 'required|max:255',
                        'unit' => 'required|max:255'          
                    ] ); 

                    
                    $id = $request->input('id');
                    $narcotic = strtoupper($request->input('narcotic'));
                    $unit = $request->input('unit');

                    $data = [
                        'drug_name'=>$narcotic,
                        'updated_at'=>Carbon::today(),
                        'drug_unit'=>$unit

                    ];

                    Narcotic::where('drug_id',$id)->update($data);
                    
                    return 1;
                
                }

                //Delete Narcotics
                public function destroy_narcotic(Request $request){
                        $id = $request->input('id');
                        Narcotic::where('drug_id',$id)->delete();
                        return 1;
                }
            
        //Narcotic:ends

        //Unit:start

            // Data Table Code for Unit
            public function get_all_units(Request $request)
            {
                $columns = array( 
                    0 =>'ID', 
                    1 =>'UNIT NAME',
                    2=>'ACTION'
                );
                $totalData =Unit::count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $unit = Unit::offset($start)
                                    ->limit($limit)
                                    ->orderBy('unit_id',$dir)
                                    ->get();
                    $totalFiltered = Unit::count();
                }
                else
                {
                    $search = strtoupper($request->input('search.value'));
                    $unit = Unit::where('unit_id','like',"%{$search}%")
                                        ->orWhere('unit_name','like',"%{$search}%")                                    
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('unit_name',$dir)
                                        ->get();
                    $totalFiltered = Unit::where('unit_id','like',"%{$search}%")
                                            ->orWhere('unit_name','like',"%{$search}%")                                           
                                            ->count();
                }
        
                $data = array();
        
                if($unit)
                {
                    foreach($unit as $unit)
                    {
                        $nestedData['ID'] = $unit->unit_id;
                        $nestedData['UNIT NAME'] = $unit->unit_name;
                        $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                }
            
            
            }

            //Add Unit
            public function store_unit(Request $request){
                $this->validate ( $request, [ 
                    'narcotic_unit' => 'required|max:255'         
                ] ); 
                $narcotic_unit = strtoupper($request->input('narcotic_unit')); 

                Unit::insert([
                    'unit_name'=>$narcotic_unit,
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                    ]);
        
            return 1;
            }

            //update Unit
            public function update_unit(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required',
                    'narcotic_unit' => 'required|max:255'          
                ] ); 

                
                $id = $request->input('id');
                $unit = $request->input('unit');

                $data = [
                    'unit_name'=>$unit,
                    'updated_at'=>Carbon::today(),

                ];

                Unit::where('unit_id',$id)->update($data);
                
                return 1;
            
            }


        //Unit:end






                // New User Creation
                
                public function index_user_creation(){

                    $agency_details = Agency_detail::select('agency_id','agency_name')
                                                        ->distinct()
                                                        ->orderBy('agency_name')
                                                        ->get();

                    return view('create_new_user', compact('agency_details'));
                }
                    

                public function create_new_user(Request $request){

                    $this->validate ( $request, [ 
                        'user_id' => 'required|unique:users,user_id|max:30',
                        'user_name' => 'required|max:255',
                        'password' => 'required|confirmed|max:255',
                        'user_type' => 'required|max:30',
                        'stakeholder_name' => 'required|integer',
                        'email_id' => 'nullable|email|max:100',
                        'contact_no' => 'nullable|integer'         
                    ] ); 


                    $user_id = $request->input('user_id');
                    $user_name = $request->input('user_name');
                    $password = Hash::make($request->input('password'));
                    $user_type = $request->input('user_type');
                    $stakeholder_name = $request->input('stakeholder_name');
                    $email = $request->input('email_id');
                    $phno = $request->input('contact_no');
                    $created_at = Carbon::today();
                    $updated_at = Carbon::today();

                    User::insert([
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'password' => $password,
                            'stakeholder_id' => $stakeholder_name,
                            'email' => $email,
                            'contact_no' => $phno,
                            'user_type' => $user_type,
                            'created_at' => $created_at,
                            'updated_at' => $updated_at
                    ]);

                    return 1;
                }
 }

        


