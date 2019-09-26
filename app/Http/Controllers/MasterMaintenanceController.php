<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Narcotic;
use App\Narcotic_unit;
use App\NdpsCourtDetail;
use App\Unit;
use App\Agency_detail;
use App\CertifyingCourtDetail;
use App\District;
use App\Seizure;
use App\Storage_detail;
use App\Ps_detail;
use App\User;
use Carbon\Carbon;
use DB;
use Auth;


class MasterMaintenanceController extends Controller
{
    
    //Agency::Start
        
        //Add stakeholder
        public function store_agency(Request $request){

            $this->validate ( $request, [ 
                'agency_name' => 'required|alpha|max:255|unique:agency_details,agency_name'
            ]); 

            $stakeholder = trim(strtoupper($request->input('agency_name')));

            Agency_detail::insert([
                'agency_name'=>$stakeholder,
                'created_at'=>Carbon::today(),
                'updated_at'=>Carbon::today()
            ]);

            return 1;
        }

        // Data Table Code for stakeholders
        public function get_all_agencies_data(Request $request){
            $columns = array( 
                0 =>'ID', 
                1 =>'AGENCY NAME',
                2 =>'ACTION'
            );

            $totalData = Agency_detail::count();

            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($request->input('search.value'))){
                $agencies = Agency_detail::offset($start)
                                            ->limit($limit)
                                            ->orderBy('agency_name',$dir)
                                            ->get();
                $totalFiltered = Agency_detail::count();
            }
            else{
                $search = $request->input('search.value');
                $agencies = Agency_detail::where('agency_id','ilike',"%{$search}%")
                                    ->orWhere('agency_name','ilike',"%{$search}%")
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy('agency_name',$dir)
                                    ->get();
                $totalFiltered = Agency_detail::where('agency_id','ilike',"%{$search}%")
                                        ->orWhere('agency_name','ilike',"%{$search}%")
                                        ->count();
                }

                $data = array();

                if($agencies){
                    foreach($agencies as $agency){
                        $nestedData['ID'] = $agency->agency_id;
                        $nestedData['AGENCY NAME'] = $agency->agency_name;
                        $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                    }
        
                }

                /*update stakeholder*/
                public function update_agency(Request $request){
                    $this->validate ( $request, [ 
                        'id' => 'required|integer|max:200|exists:agency_details,agency_id',
                        'agency_name' => 'required|alpha|max:255|unique:agency_details,agency_name'
                    ]);
                    
                    $id = $request->input('id');
                    $stakeholder = strtoupper(trim($request->input('agency_name')));

                    $data = [
                        'agency_name'=>$stakeholder,
                        'updated_at'=>Carbon::today()
                    ];

                    Agency_detail::where('agency_id',$id)->update($data);
                    
                    return 1;
                
                }

                //deleting stakeholder
                public function destroy_stakeholder(Request $request)
                {
                    $this->validate ( $request, [ 
                        'id' => 'required|integer|max:200|exists:agency_details,agency_id',                        
                    ]);

                    $id = $request->input('id');
                    Agency_detail::where('agency_id',$id)->delete();
                    return 1;
                }
                
            //Stakeholder::End

            //Magistrate Master maintenance :: Start
          
                //Certifying court master maintenance view
                public function index_certifying_court_maintenance_view(Request $request)
                {
                    $data= array();

                    $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();
                    

                    return view('magistrate_maintenance_view',compact('data'));
                }

                //showing exisiting Certifying court
                public function get_all_certifying_court_details(Request $request){

                    $columns = array( 
                        0 =>'certifying_court_id', 
                        1 =>'certifying_court_name',
                        2 =>'district_name',
                        3 =>'district_id',
                        4 =>'action'
                    );

                    $totalData = CertifyingCourtDetail::count();

                    $totalFiltered = $totalData; 

                    $limit = $request->input('length');
                    $start = $request->input('start');
                    $order = $columns[$request->input('order.0.column')];
                    $dir = $request->input('order.0.dir');


                    if(empty($request->input('search.value'))){
                        $certifying_courts = CertifyingCourtDetail::
                                        join('districts','certifying_court_details.district_id','=','districts.district_id')                               
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('court_name',$dir)
                                        ->get();

                        $totalFiltered = CertifyingCourtDetail::count();
                    }
                    else{
                        $search = $request->input('search.value');
                        $certifying_courts = CertifyingCourtDetail::
                                        join('districts','certifying_court_details.district_id','=','districts.district_id')                               
                                        ->where('court_id','ilike',"%{$search}%")
                                        ->orWhere('court_name','ilike',"%{$search}%")
                                        ->orWhere('district_name','ilike',"%{$search}%")
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('court_name',$dir)
                                        ->get();
                            
                        $totalFiltered = CertifyingCourtDetail::
                                        join('districts','certifying_court_details.district_id','=','districts.district_id')                   
                                        ->where('court_id','ilike',"%{$search}%")
                                        ->orWhere('court_name','ilike',"%{$search}%")
                                        ->orWhere('district_name','ilike',"%{$search}%")
                                        ->count();


                        }

                    $data = Array();

                    if($certifying_courts){
                        foreach($certifying_courts as $certifying_court){
                            $nestedData['certifying_court_id'] = $certifying_court->court_id;
                            $nestedData['certifying_court_name'] = $certifying_court->court_name;
                            $nestedData['district_name'] = $certifying_court->district_name;
                            $nestedData['district_id'] = $certifying_court->district_id;
                            $nestedData['action'] = "<i class='fa fa-trash delete' aria-hidden='true' title='Delete'></i><br><i class='fa fa-pencil edit' aria-hidden='true' title='Edit'></i>";
            
                            $data[] = $nestedData;
                        }
                            $json_data = array(
                                "draw" => intval($request->input('draw')),
                                "recordsTotal" => intval($totalData),
                                "recordsFiltered" =>intval($totalFiltered),
                                "data" => $data
                            );
                    
                            echo json_encode($json_data);
                    }
            
                }

                /*adding new Certifying court*/
                public function store_certifying_court(Request $request){

                    $this->validate ( $request, [                         
                        'certifying_court_name' => 'required|alpha_dash|max:255|unique:certifying_court_details,court_name',
                        'district_name' => 'required|integer|max:200|exists:districts,district_id'
                    ] ); 

                $certifying_court_name=trim(strtoupper($request->input('certifying_court_name')));
                $district_name=$request->input('district_name');

                CertifyingCourtDetail::insert([
                    'court_name'=>$certifying_court_name,
                    'district_id'=>$district_name,
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                ]);
                return 1;

            }
        
            /*Update Certifying court */
            public function update_certifying_court(Request $request){
                $this->validate ( $request, [ 
                    'certifying_court_id' => 'required|integer|max:200|exists:certifying_court_details,court_id',
                    'certifying_court_name' => 'required|alpha_dash|max:255',
                    'district_name' => 'required|integer|max:200|exists:districts,district_id'         
                ]); 

                
                $id = $request->input('certifying_court_id');
                $certifying_court_name= trim(strtoupper($request->input('certifying_court_name')));
                $district_id = $request->input('district_name');

                $data = [
                    'court_name'=>$certifying_court_name,
                    'district_id'=>$district_id,
                    'updated_at'=>Carbon::today()
                ];   

                CertifyingCourtDetail::where('court_id',$id)->update($data);
                
                return 1;

            }


            //delete Certifying court details
            public function destroy_certifying_court(Request $request)
            {
                $this->validate ( $request, [ 
                    'id' => 'required|integer|max:200|exists:certifying_court_details,court_id',                    
                ]); 

                $id = $request->input('id');
                CertifyingCourtDetail::where('court_id',$id)->delete();
                return 1;
            }            
            
        //Court:End


            
        //Narcotic:Start

            // Data Table Code for Narcotics
            public function index_narcotic_maintenance_view()
            {
                $data= Unit::orderBy('unit_name')->get();
                return view('narcotic_maintenance_view',compact('data'));
            }

            public function get_all_narcotics_data(Request $request)
            {
                $columns = array( 
                    0 =>'drug_id', 
                    1 =>'narcotic_name',
                    2=>'unit_name',
                    3=>'unit_id',
                    4=>'action'
                );
        
                $totalData = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                            ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')
                                            ->where('display','Y')
                                            ->count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $narcotics = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                                ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')
                                                ->where('display','Y')                               
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy('drug_name',$dir)
                                                ->get();
                    $totalFiltered = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                                    ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
                                                    ->where('display','Y')
                                                    ->count();
                }
                else
                {
                    $search = $request->input('search.value');
                    $narcotics = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                            ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
                                            ->where('unit_name','ilike',"%{$search}%")
                                            ->orWhere('drug_name','ilike',"%{$search}%")                                    
                                            ->offset($start)
                                            ->limit($limit)
                                            ->orderBy('drug_name',$dir)
                                            ->get();
                    $totalFiltered = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                            ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
                                            ->where('unit_name','ilike',"%{$search}%")
                                            ->orWhere('drug_name','ilike',"%{$search}%")                                            
                                            ->count();
                }
        
                $data = array();
        
                if($narcotics)
                {
                    foreach($narcotics as $narcotic)
                    {
                        $nestedData['drug_id'] = $narcotic->drug_id;
                        
                        $nestedData['narcotic_name'] = $narcotic->drug_name;
                        
                        $nestedData['unit_name'] = $narcotic->unit_name;

                        $nestedData['unit_id'] = $narcotic->unit_id;

                        $nestedData['action'] = "<i class='fa fa-trash delete' aria-hidden='true' title='Delete'></i><br><i class='fa fa-pencil edit' aria-hidden='true' title='Edit'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                }
            
            }


            //Add Narcotics
            public function store_narcotic(Request $request){
                $this->validate ( $request, [ 
                    'narcotic_name' => 'required|alpha_dash|max:255',
                    'weighing_unit' => 'required|array',         
                    'weighing_unit.*' => 'required|integer|max:200|exists:units,unit_id'         
                ]); 

                $narcotic = trim(strtoupper($request->input('narcotic_name')));
                $unit = $request->input('weighing_unit');               
               
                
                Narcotic::insert([
                    'drug_name' => $narcotic,
                    'display' => 'Y',
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                ]);

                $narcotic_id = Narcotic::max('drug_id');

                for($i=0;$i<sizeof($unit);$i++){
                    Narcotic_unit::insert([
                        'narcotic_id'=>$narcotic_id,
                        'unit_id'=>$unit[$i],
                        'created_at'=>Carbon::today(),
                        'updated_at'=>Carbon::today()
                    ]);
                }
        
                return 1;



                }

            //update Narcotics
            public function update_narcotics(Request $request){
                $this->validate ( $request, [ 
                    'narcotic_id' => 'required|integer|max:200|exists:narcotics,drug_id',
                    'narcotic_name' => 'required|alpha_dash|max:255',
                    'narcotic_unit' => 'required|integer|max:200|exists:units,unit_id',
                    'unit_id' => 'required|integer|max:200|exists:units,unit_id'
                ]); 

                    
                $narcotic_id = $request->input('narcotic_id');                
                $narcotic_name = trim(strtoupper($request->input('narcotic_name')));
                $narcotic_unit = $request->input('narcotic_unit');  
                $unit_id = $request->input('unit_id');   


                Narcotic::where('drug_id',$narcotic_id)
                                ->update([
                                    'drug_name' => $narcotic_name,
                                    'updated_at'=>Carbon::today()
                                ]);

                Narcotic_unit::where([
                                ['narcotic_id',$narcotic_id],
                                ['unit_id',$unit_id]
                            ])->update([
                                'unit_id' =>$narcotic_unit,
                                'updated_at'=>Carbon::today()
                            ]);

                return 1;
                
            }

                //Delete Narcotics
                public function destroy_narcotic(Request $request){
                    $this->validate ( $request, [ 
                        'narcotic_id' => 'required|integer|max:200|exists:narcotics,drug_id',                        
                        'unit_id' => 'required|integer|max:200|exists:units,unit_id'
                    ]); 

                    $narcotic_id = $request->input('narcotic_id');                                    
                    $unit_id = $request->input('unit_id'); 
                    
                    Narcotic_unit::where([
                                    ['narcotic_id',$narcotic_id],
                                    ['unit_id',$unit_id]
                                ])->delete();
                    
                    $count = Narcotic_unit::where('narcotic_id',$narcotic_id)->count();
                    
                    if($count==0)
                        Narcotic::where('drug_id',$narcotic_id)->delete();                    
                
                    return 1;
                }                
            
        //Narcotic:ends



        //Weighing Unit:start

            // Data Table Code for Unit
            public function get_all_weighing_units(Request $request)
            {
                $columns = array( 
                    0 =>'ID', 
                    1 =>'UNIT NAME',
                    2=>'UNIT DEGREE',
                    3=>'ACTION'
                );
                $totalData =Unit::count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $units = Unit::offset($start)
                                ->limit($limit)
                                ->orderBy('unit_id',$dir)
                                ->get();
                    $totalFiltered = Unit::count();
                }
                else
                {
                    $search = $request->input('search.value');
                    $units = Unit::where('unit_id','ilike',"%{$search}%")
                                    ->orWhere('unit_name','ilike',"%{$search}%")                                    
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy('unit_name',$dir)
                                    ->get();
                    $totalFiltered = Unit::where('unit_id','ilike',"%{$search}%")
                                        ->orWhere('unit_name','ilike',"%{$search}%")                                           
                                        ->count();
                }
        
                $data = array();
        
                if($units)
                {
                    foreach($units as $unit)
                    {
                        $nestedData['ID'] = $unit->unit_id;
                        $nestedData['UNIT NAME'] = $unit->unit_name;
                        $nestedData['UNIT DEGREE'] = $unit->unit_degree;
                        $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                }
            
            
            }

            //Add Weighing Unit
            public function store_weighing_unit(Request $request){
                $this->validate ( $request, [ 
                    'weighing_unit_name' => 'required|alpha|max:255|unique:units,unit_name',
                    'unit_degree' => 'required|integer|max:3|in:0,1,2,3'   
                ]); 

                $weighing_unit_name = trim(strtoupper($request->input('weighing_unit_name'))); 
                $unit_degree = $request->input('unit_degree'); 

                Unit::insert([
                    'unit_name'=>$weighing_unit_name,
                    'unit_degree' =>$unit_degree,
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                ]);
        
                return 1;
            }

            //Update Weighing Unit
            public function update_weighing_unit(Request $request){
                $this->validate ( $request, [ 
                    'unit_id' => 'required|integer|max:200|exists:units,unit_id',
                    'weighing_unit_name' => 'required|alpha|max:255'          
                ]); 

                
                $unit_id = $request->input('unit_id');
                $weighing_unit_name = trim(strtoupper($request->input('weighing_unit_name'))); 

                $data = [
                    'unit_name'=>$weighing_unit_name,
                    'updated_at'=>Carbon::today()

                ];

                Unit::where('unit_id',$unit_id)->update($data);
                
                return 1;
            
            }

             //Delete Weighing Unit
             public function destroy_weighing_unit(Request $request){
                $this->validate ( $request, [ 
                    'unit_id' => 'required|integer|max:200|exists:units,unit_id'
                ]); 
                
                $unit_id = $request->input('unit_id');
                
                Unit::where('unit_id',$unit_id)->delete();

                return 1;

              }

              public function destroy_seizure_weighing_unit_record(Request $request)
              {
                    $this->validate ( $request, [ 
                        'unit_id' => 'required|integer|max:200|exists:units,unit_id'
                    ]); 
                
                    $unit_id = $request->input('unit_id');

                  Seizure::where('seizure_quantity_weighing_unit_id',$unit_id)
                            ->orWhere('sample_quantity_weighing_unit_id',$unit_id)
                            ->orWhere('disposal_quantity_weighing_unit_id',$unit_id)
                            ->delete();

                  Narcotic_unit::where('unit_id',$unit_id)->delete();

                  Unit::where('unit_id',$unit_id)->delete();

                  return 1;

              }

        //Weighing Unit:end

        //Police Staion:Start

            public function index_ps_maintenance_view(Request $request)
            {
                $data= array();

                $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();                

                return view('ps_maintenance_view',compact('data'));
            }


            // Data Table Code for PS
                public function get_all_ps(Request $request)
                {
                    $columns = array( 
                        0 => 'ps_id', 
                        1 => 'ps_name',
                        2 => 'district_name',
                        3 => 'district_id',
                        4 => 'action'
                    );

                    $totalData =Ps_detail::count();
            
                    $totalFiltered = $totalData; 
            
                    $limit = $request->input('length');
                    $start = $request->input('start');
                    $order = $columns[$request->input('order.0.column')];
                    $dir = $request->input('order.0.dir');
            
                    if(empty($request->input('search.value'))){
                        $ps_details = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('district_name',$dir)
                                        ->get();
                        $totalFiltered = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                          ->count();
                    }
                    else
                    {
                        $search = $request->input('search.value');
                        $ps_details = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                        ->where('ps_name','ilike',"%{$search}%")
                                        ->orWhere('district_name','ilike',"%{$search}%")
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('district_name',$dir)
                                        ->get();
                        $totalFiltered = Ps_detail::join('districts','ps_details.district_id','=','districts.district_id')
                                                    ->where('ps_name','ilike',"%{$search}%")  
                                                    ->orWhere('district_name','ilike',"%{$search}%")                                    
                                                    ->count();
                    }
            
                    $data = array();
            
                    if($ps_details)
                    {
                        foreach($ps_details as $ps)
                        {
                            $nestedData['ps_id'] = $ps->ps_id;
                            $nestedData['ps_name'] = $ps->ps_name;
                            $nestedData['district_name'] = $ps->district_name;
                            $nestedData['district_id'] = $ps->district_id;
                            $nestedData['action'] = "<i class='fa fa-trash delete' aria-hidden='true' title='Delete'></i><br><i class='fa fa-pencil edit' aria-hidden='true' title='Edit'></i>";
            
                            $data[] = $nestedData;
                        }
                            $json_data = array(
                                "draw" => intval($request->input('draw')),
                                "recordsTotal" => intval($totalData),
                                "recordsFiltered" =>intval($totalFiltered),
                                "data" => $data
                            );
                    
                            echo json_encode($json_data);
                    }
                
                
                }

                //Adding new PS
                public function store_ps(Request $request){

                    $this->validate ( $request, [                     
                        'ps_name' => 'required|alpha_dash|max:255|unique:ps_details,ps_name',
                        'district_name' => 'required|integer|max:200|exists:districts,district_id' 
                    ]); 

                    $ps_name= trim(strtoupper($request->input('ps_name')));
                    $district_name=$request->input('district_name');

                    Ps_detail::insert([
                        'ps_name'=>$ps_name,
                        'district_id'=>$district_name,
                        'created_at'=>Carbon::today(),
                        'updated_at'=>Carbon::today()
                    ]);
                    
                    return 1;
                }

                //Update PS
                public function update_ps(Request $request){
                    $this->validate ( $request, [ 
                        'ps_id' => 'required|integer|max:1200|exists:ps_details,ps_id',
                        'ps_name' => 'required|alpha_dash|max:255',      
                        'district_name' => 'required|integer|max:200|exists:districts,district_id' 
                    ] ); 

                        
                    $ps_id = $request->input('ps_id');
                    $ps_name = trim(strtoupper($request->input('ps_name')));
                    $district_name=$request->input('district_name');
                
                    $data = [
                            'ps_name'=>$ps_name,
                            'district_id'=>$district_name,
                            'updated_at'=>Carbon::today()
                        ];

                    Ps_detail::where('ps_id',$ps_id)->update($data);
                    
                    return 1;
                    
                }

                //Delete Police Station
                public function destroy_ps(Request $request){
                    $this->validate ( $request, [ 
                        'ps_id' => 'required|integer|max:1200|exists:ps_details,ps_id'
                    ]); 

                    $ps_id = $request->input('ps_id');
                    Ps_detail::where('ps_id',$ps_id)->delete();
                    return 1;
                }

        //Police Staion:End


        //District::Start
        
        //Add District
        public function store_district(Request $request){

            $this->validate ($request, [ 
                'district_name' => 'required|alpha_dash|max:255|unique:districts,district_name'
            ]); 

            $district_name = trim(strtoupper($request->input('district_name')));

            District::insert([
                'district_name'=>$district_name,
                'created_at'=>Carbon::today(),
                'updated_at'=>Carbon::today()
            ]);

            return 1;
        }

        // Data Table Code for District
        public function get_all_district(Request $request){
            $columns = array( 
                0 =>'ID', 
                1 =>'DISTRICT NAME',
                2 =>'ACTION'
            );

            $totalData = District::count();

            $totalFiltered = $totalData; 

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($request->input('search.value'))){
                $districts = District::offset($start)
                                    ->limit($limit)
                                    ->orderBy('district_name',$dir)
                                    ->get();
                $totalFiltered = District::count();
            }
            else{
                $search = $request->input('search.value');
                $districts = District::where('district_id','ilike',"%{$search}%")
                                    ->orWhere('district_name','ilike',"%{$search}%")
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy('district_name',$dir)
                                    ->get();
                $totalFiltered = District::where('district_id','ilike',"%{$search}%")
                                            ->orWhere('district_name','ilike',"%{$search}%")
                                            ->count();
            }

            $data = array();

            if($districts){
                foreach($districts as $district){
                    $nestedData['ID'] = $district->district_id;
                    $nestedData['DISTRICT NAME'] = $district->district_name;
                    $nestedData['ACTION'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
    
                    $data[] = $nestedData;
                }
                
                $json_data = array(
                    "draw" => intval($request->input('draw')),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" =>intval($totalFiltered),
                    "data" => $data
                );
        
                echo json_encode($json_data);
            }
    
        }

        /*update district*/
        public function update_district(Request $request){
            $this->validate ( $request, [ 
                'id' => 'required|integer|max:200|exists:districts,district_id',
                'district_name' => 'required|alpha_dash|max:255|unique:districts,district_name'
            ]);
            
            $id = $request->input('id');
            $district = trim(strtoupper($request->input('district_name')));

            $data = [
                'district_name'=>$district,
                'updated_at'=>Carbon::today()
            ];

            District::where('district_id',$id)->update($data);
            
            return 1;
        
        }

        //deleting District
        public function destroy_district(Request $request)
        {
            $this->validate ( $request, [ 
                'id' => 'required|integer|max:200|exists:districts,district_id'
            ]);

            $id = $request->input('id');

            District::where('district_id',$id)->delete();
            return 1;
        }
        //District::End



        //NDPS Court:Start

        public function index_ndps_court_maintenance_view(Request $request)
        {
            $data= array();

            $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();                

            return view('ndps_court_maintenance_view',compact('data'));
        }


        // Data Table Code for NDPS Court
            public function get_all_ndps_court(Request $request)
            {
                $columns = array( 
                    0 =>'id', 
                    1 =>'ndps_court_name',
                    2 =>'district_id',
                    3 =>'district',
                    4 =>'action'
                );

                $totalData =NdpsCourtDetail::count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $ndps_courts = NdpsCourtDetail::join('districts','ndps_court_details.district_id','=','districts.district_id')
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy('district_name',$dir)
                                    ->get();
                    $totalFiltered = NdpsCourtDetail::join('districts','ndps_court_details.district_id','=','districts.district_id')
                                        ->count();
                }
                else
                {
                    $search = $request->input('search.value');
                    $ndps_courts = NdpsCourtDetail::join('districts','ndps_court_details.district_id','=','districts.district_id')
                                    ->where('ndps_court_name','ilike',"%{$search}%")
                                    ->orWhere('district_name','ilike',"%{$search}%")
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy('district_name',$dir)
                                    ->get();
                    $totalFiltered = NdpsCourtDetail::join('districts','ndps_court_details.district_id','=','districts.district_id')
                                                ->where('ndps_court_name','ilike',"%{$search}%")  
                                                ->orWhere('district_name','ilike',"%{$search}%")                                    
                                                ->count();
                }
        
                $data = array();
        
                if($ndps_courts)
                {
                    foreach($ndps_courts as $ndps_court)
                    {
                        $nestedData['id'] = $ndps_court->ndps_court_id;
                        $nestedData['ndps_court_name'] = $ndps_court->ndps_court_name;
                        $nestedData['district_id'] = $ndps_court->district_id;
                        $nestedData['district'] = $ndps_court->district_name;
                        $nestedData['action'] = "<i class='fa fa-trash delete' aria-hidden='true' title='Delete'></i><br><i class='fa fa-pencil edit' aria-hidden='true' title='Edit'></i>";
        
                        $data[] = $nestedData;
                    }
                    $json_data = array(
                        "draw" => intval($request->input('draw')),
                        "recordsTotal" => intval($totalData),
                        "recordsFiltered" =>intval($totalFiltered),
                        "data" => $data
                    );
            
                    echo json_encode($json_data);
                }            
            
            }

            //Adding new NDPS Court
            public function store_ndps_court(Request $request){

                $this->validate ( $request, [                     
                    'ndps_court_name' => 'required|alpha_dash|max:255|unique:ndps_court_details,ndps_court_name',
                    'district_name' => 'required|integer|max:200|exists:districts,district_id'  
                ]); 

                $ndps_court_name= trim(strtoupper($request->input('ndps_court_name')));
                $district_name=$request->input('district_name');

                NdpsCourtDetail::insert([
                    'ndps_court_name'=>$ndps_court_name,
                    'district_id'=>$district_name,
                    'created_at'=>Carbon::today(),
                    'updated_at'=>Carbon::today()
                ]);
                
                return 1;
            }

            //Update NDPS Court
            public function update_ndps_court(Request $request){
                $this->validate ( $request, [ 
                    'ndps_court_id' => 'required|integer|max:200|exists:ndps_court_details,ndps_court_id',
                    'ndps_court_name' => 'required|alpha_dash|max:255',      
                    'district_name' => 'required|integer|max:200|exists:districts,district_id',
                ] ); 

                    
                $ndps_court_id = $request->input('ndps_court_id');
                $ndps_court_name = trim(strtoupper($request->input('ndps_court_name')));
                $district_id = $request->input('district_name');
            
                $data = [
                    'ndps_court_name'=>$ndps_court_name,
                    'district_id'=>$district_id,
                    'updated_at'=>Carbon::today()
                ];

                NdpsCourtDetail::where('ndps_court_id',$ndps_court_id)->update($data);
                
                return 1;
                
            }

            //Delete NDPS Court
            public function destroy_ndps_court(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required|integer|max:200|exists:ndps_court_details,ndps_court_id',                    
                ] ); 

                $id = $request->input('id');
                NdpsCourtDetail::where('ndps_court_id',$id)->delete();

                return 1;
            }

            
        //NDPS Court:End


        //Storage :start

            public function index_storage_maintenance_view(Request $request)
            {
                $data= array();

                $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();                

                return view('storage_maintenance_view',compact('data'));
            }


            // Data Table Code for STORAGE
            public function get_all_storage(Request $request)
            {
                $columns = array( 
                    0 =>'storage_id', 
                    1 =>'storage_name',
                    2 =>'district_name',
                    3 =>'district_id',
                    4=>'action'
                );

                $totalData =Storage_detail::where('display','Y')->count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $storages = Storage_detail::join('districts','storage_details.district_id','=','districts.district_id')
                                                ->where('display','Y')
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy('storage_id',$dir)
                                                ->get();
                    $totalFiltered = Storage_detail::join('districts','storage_details.district_id','=','districts.district_id')
                                                    ->where('display','Y')
                                                    ->count();
                }
                else
                {
                    $search = $request->input('search.value');
                    $storages = Storage_detail::join('districts','storage_details.district_id','=','districts.district_id')
                                                ->where('storage_name','ilike',"%{$search}%")
                                                ->orWhere('storage_id','ilike',"%{$search}%")
                                                ->orWhere('district_name','ilike',"%{$search}%")
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy('storage_id',$dir)
                                                ->get();
                    $totalFiltered = Storage_detail::join('districts','storage_details.district_id','=','districts.district_id')
                                                    ->where('storage_name','ilike',"%{$search}%")
                                                    ->orWhere('storage_id','ilike',"%{$search}%")  
                                                    ->orWhere('district_name','ilike',"%{$search}%")                                         
                                                    ->count();
                }
        
                $data = array();
        
                if($storages)
                {
                    foreach($storages as $storage)
                    {
                        $nestedData['storage_id'] = $storage->storage_id;
                        $nestedData['storage_name'] = $storage->storage_name;
                        $nestedData['district_name'] = $storage->district_name;
                        $nestedData['district_id'] = $storage->district_id;
                        $nestedData['action'] = "<i class='fa fa-trash delete' aria-hidden='true' title='Delete'></i><br><i class='fa fa-pencil edit' aria-hidden='true' title='Edit'></i>";
        
                        $data[] = $nestedData;
                    }
                        $json_data = array(
                            "draw" => intval($request->input('draw')),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" =>intval($totalFiltered),
                            "data" => $data
                        );
                
                        echo json_encode($json_data);
                }
            
            
            }

             //Adding new STORAGE
             public function store_storage(Request $request){

                $this->validate ( $request, [                     
                    'malkhana_name' => 'required|alpha_dash|max:255|unique:storage_details,storage_name',
                    'district_name' => 'required|integer|max:200|exists:districts,district_id'
                ]);

                $storage_name = trim(strtoupper($request->input('malkhana_name')));
                $district_id = $request->input('district_name');

                Storage_detail::insert([
                                    'storage_name'=>$storage_name,
                                    'district_id'=>$district_id,
                                    'display'=>'Y',
                                    'created_at'=>Carbon::today(),
                                    'updated_at'=>Carbon::today()
                                ]);
                return 1;
            }

             //Update STORAGE
             public function update_storage(Request $request){
                $this->validate ( $request, [ 
                    'malkhana_id' => 'required|integer|max:200|exists:storage_details,storage_id',
                    'malkhana_name' => 'required|alpha_dash|max:255',
                    'district_name' => 'required|integer|max:200|exists:districts,district_id',        
                ]); 

                    
                $malkhana_id = $request->input('malkhana_id');
                $malkhana_name = trim(strtoupper($request->input('malkhana_name')));
                $district_id = $request->input('district_name');
            
                $data = [
                    'storage_name'=>$malkhana_name,
                    'district_id'=>$district_id,
                    'updated_at'=>Carbon::today()
                ];

                Storage_detail::where('storage_id',$malkhana_id)->update($data);
                
                return 1;
            
            }

              //Delete storage
             public function destroy_storage(Request $request){
                $this->validate ( $request, [ 
                    'storage_id' => 'required|integer|max:200|exists:storage_details,storage_id'
                ]); 

                $storage_id = $request->input('storage_id');

                Storage_detail::where('storage_id',$storage_id)->delete();

                return 1;
              }

        //Storage:End

        // New User Creation
        
        public function index_user_creation(){

            $data['agency_details'] = Agency_detail::select('agency_id','agency_name')
                                    ->distinct()
                                    ->orderBy('agency_name')
                                    ->get();

            $data['ps_details'] = Ps_detail::select('ps_id','ps_name')
                                    ->distinct()
                                    ->orderBy('ps_name')
                                    ->get();

            $data['certifying_court_details'] = CertifyingCourtDetail::select('court_id','court_name')
                                    ->orderBy('court_name')
                                    ->get();

            $data['ndps_court_details'] = NdpsCourtDetail::select('ndps_court_id','ndps_court_name')
                                        ->orderBy('ndps_court_name')
                                        ->get();

            return view('create_new_user', compact('data'));
        }
            

        public function create_new_user(Request $request){

            $this->validate ( $request, [ 
                'user_id' => 'required|alpha_dash|max:30|unique:users,user_id',
                'user_name' => 'required|alpha_dash|max:100|unique:users,user_name',
                'password' => 'required|confirmed|max:20|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                'user_type' => 'required|alpha_dash|max:30|in:ps,agency,special_court,magistrate,high_court,admin',
                'agency_name' => 'nullable|integer|max:200|exists:agency_details,agency_id',
                'certifying_court_name' => 'nullable|integer|max:500|unique:users,certifying_court_id|exists:certifying_court_details,court_id',
                'ndps_court_name' => 'nullable|integer|max:200|unique:users,ndps_court_id|exists:ndps_court_details,ndps_court_id',
                'ps_name' => 'nullable|integer|max:1200|unique:users,ps_id|exists:ps_details,ps_id',
                'email_id' => 'nullable|email|max:100|unique:users,email',
                'contact_no' => 'nullable|integer|max:12|unique:users,contact_no'         
            ] ); 


            $user_id = $request->input('user_id');
            $user_name = $request->input('user_name');
            $password = Hash::make($request->input('password'));
            $user_type = $request->input('user_type');
            $agency_name = $request->input('agency_name');

            if(empty($agency_name))
                $agency_name=null;

            $certifying_court_name = $request->input('certifying_court_name');

            if(empty($certifying_court_name))
                $certifying_court_name=null;

            $ndps_court_name = $request->input('ndps_court_name');

            if(empty($ndps_court_name))
                $ndps_court_name=null;

            $ps_name = $request->input('ps_name');

            if(empty($ps_name))
                $ps_name=null;

            $email = $request->input('email_id');
            $phno = $request->input('contact_no');
            $created_at = Carbon::today();
            $updated_at = Carbon::today();

            User::insert([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'password' => $password,
                    'agency_id' => $agency_name,
                    'ps_id' => $ps_name,
                    'certifying_court_id' => $certifying_court_name,
                    'ndps_court_id' => $ndps_court_name,
                    'email' => $email,
                    'contact_no' => $phno,
                    'user_type' => $user_type,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at
            ]);

            return 1;
        }

        //Update Password:Start

        public function update_password(Request $request){

            $this->validate($request,[
                'new_password' => 'required|confirmed|min:6|max:15|different:current_password|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                'current_password' =>'required',
                'uid' => 'required|alpha_dash|max:200|exists:users,user_id'
            ]);
            
            $uid=$request->input('uid');  
            $new_password = Hash::make($request->input('new_password'));
            $cur_password =  $request->input('current_password');
            $data['user'] = User::where('user_id',$uid)
                            ->get();

            if(Hash::check($cur_password, $data['user'][0]['password']))
            {
                User::where('user_id',$uid)
                ->update([
                    'password'=>$new_password, 
                    'updated_at'=>Carbon::today()
                ]);
            
                return 1;
            }
            else
                return 0;
           
        }

        //Update Password:End
 }

        


