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

    //District::Start

        //Fetching districts
        public function index_district(Request $request)
                {
                    $data= array();

                    $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();
                    

                    return view('district_view',compact('data'));
                }


    //District::End

    //Agency::Start
        
        //Add stakeholder
        public function store_agency(Request $request){

            $this->validate ( $request, [ 
                'agency_name' => 'required|max:255|unique:agency_details,agency_name'
            ] ); 

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
                        'id' => 'required|exists:agency_details,agency_id',
                        'agency_name' => 'required|max:255|unique:agency_details,agency_name'
                    ]);
                    
                    $id = $request->input('id');
                    $stakeholder = strtoupper($request->input('agency_name'));

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
                    $id = $request->input('id');
                    Agency_detail::where('agency_id',$id)->delete();
                    return 1;
                }

                public function destroy_seizure_stakeholder_record(Request $request)
                {
                    $id = $request->input('id');
                    Seizure::where('agency_id',$id)->delete();
                    User::where('agency_id',$id)->delete();
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
                        3 =>'action'
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
                            $nestedData['action'] = "<i class='fa fa-trash' aria-hidden='true'></i>";
            
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
                        
                        'certifying_court_name' => 'required|max:255|unique:certifying_court_details,court_name',
                        'district_name' => 'required|exists:districts,district_id'

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
                    'id' => 'required|exists:certifying_court_details,court_id',
                    'certifying_court_name' => 'required|max:255|unique:certifying_court_details,court_name'         
                ] ); 

                
                $id = $request->input('id');
                $certifying_court_name= trim(strtoupper($request->input('certifying_court_name')));

                $data = [
                    'court_name'=>$certifying_court_name,
                    'updated_at'=>Carbon::today()
                    ];   

                CertifyingCourtDetail::where('court_id',$id)->update($data);
                
                return 1;

            }


            //delete Certifying court details
            public function destroy_certifying_court(Request $request)
            {
                $id = $request->input('id');
                CertifyingCourtDetail::where('court_id',$id)->delete();
                return 1;
            }

            public function destroy_seizure_certifying_court_record(Request $request){
                $id = $request->input('id');
                User::where('certifying_court_id',$id)->delete();
                Seizure::where('certification_court_id',$id)->delete();                
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
                    0 =>'ID', 
                    1 =>'NARCOTIC',
                    2=>'UNIT',
                    3=>'ACTION'
                );
        
                $totalData = Narcotic::count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $narcotics = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                                ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy('drug_name',$dir)
                                                ->get();
                    $totalFiltered = Narcotic_unit::join('units','units.unit_id','=','narcotic_units.unit_id')                               
                                                    ->join('narcotics','narcotics.drug_id','=','narcotic_units.narcotic_id')                               
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
                        $nestedData['ID'] = $narcotic->drug_id;
                        
                        $nestedData['NARCOTIC'] = $narcotic->drug_name;

                        $unit = Unit::get();
                        
                        $option = "";
                        $option = $option."<select class='form-control unit data' style='width:150px'>";
                        foreach($unit as $data1){
                            $option = $option."<option value='".$data1['unit_id']."'";
                            
                            if($data1['unit_id']==$narcotic->unit_id)
                                $option = $option." selected>".$data1['unit_name']."</option>";
                            else
                                $option = $option.">".$data1['unit_name']."</option>";
                        }

                        
                        $option = $option."</select>";
                        
                        $nestedData['UNIT'] = $option;
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


            //Add Narcotics
            public function store_narcotic(Request $request){
                $this->validate ( $request, [ 
                    'narcotic_name' => 'required|max:255|unique:narcotics,drug_name',
                    'weighing_unit' => 'required|array',         
                    'weighing_unit.*' => 'required|exists:units,unit_id'         
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
                    'narcotic_id' => 'required|exists:narcotics,drug_id',
                    'narcotic_name' => 'required|max:255|unique:narcotics,drug_name',
                    'weighing_unit' => 'required|exists:units,unit_id'          
                ]); 

                    
                $id = $request->input('narcotic_id');
                $narcotic = trim(strtoupper($request->input('narcotic_name')));
                $unit = $request->input('weighing_unit');

                $data = [
                    'drug_name'=>$narcotic,
                    'updated_at'=>Carbon::today()
                ];

                Narcotic::where('drug_id',$id)->update($data);
                
                // $data = [
                //     'narcotic_id'=>$id,
                //     'unit_id'=>$unit,
                //     'updated_at'=>Carbon::today()
                // ];

                // Narcotic_unit::where('drug_id',$id)->update($data);                

                return 1;
                
            }

                //Delete Narcotics
                public function destroy_narcotic(Request $request){
                        $id = $request->input('id');
                        $unit_id= $request->input('unit');
                        $narotic_count=Narcotic_unit::where('narcotic_id',$id)->count();
                        if($narotic_count==1)
                        {
                            Narcotic_unit::where('narcotic_id',$id)->delete();
                            Narcotic::where('drug_id',$id)->delete();
                        }
                        else{
                            Narcotic_unit::where('narcotic_id',$id)
                                            ->where('unit_id',$unit_id)
                                            ->delete();
                        }
                        return 1;
                }

                
                public function destroy_seizure_narcotic_record(Request $request)
                {
                    $id = $request->input('id');
                    $unit_id= $request->input('unit');
                    $narotic_count=Narcotic_unit::where('narcotic_id',$id)->count();
                    Seizure::where('drug_id',$id)
                            ->where('unit_id',$unit_id)
                            ->delete();
                    if($narotic_count==1)
                    {
                        Narcotic_unit::where('narcotic_id',$id)->delete();
                        Narcotic::where('drug_id',$id)->delete();
                    }
                    else
                    {
                        Narcotic_unit::where('narcotic_id',$id)
                                            ->where('unit_id',$unit_id)
                                            ->delete();
                    }
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
                    'weighing_unit_name' => 'required|max:255|unique:units,unit_name',
                    'unit_degree' => 'required|integer'   
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
                    'unit_id' => 'required|exists:units,unit_id',
                    'weighing_unit_name' => 'required|max:255|unique:units,unit_name'          
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
                    'unit_id' => 'required|exists:units,unit_id'
                ]); 
                
                $unit_id = $request->input('unit_id');
                
                Unit::where('unit_id',$unit_id)->delete();

                return 1;

              }

              public function destroy_seizure_weighing_unit_record(Request $request)
              {
                    $this->validate ( $request, [ 
                        'unit_id' => 'required|exists:units,unit_id'
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
                        0 =>'ID', 
                        1 =>'POLICE STATION NAME',
                        3 =>'DISTRICT',
                        2=>'ACTION'
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
                                        ->orderBy('ps_id',$dir)
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
                                        ->orderBy('ps_name',$dir)
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
                            $nestedData['ID'] = $ps->ps_id;
                            $nestedData['POLICE STATION NAME'] = $ps->ps_name;
                            $nestedData['DISTRICT'] = $ps->district_name;
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

                //Adding new PS
                public function store_ps(Request $request){

                    $this->validate ( $request, [                     
                        'ps_name' => 'required|string|max:255|unique:ps_details,ps_name',
                        'district_name' => 'required|exists:districts,district_id'                    

                    ] ); 

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
                        'id' => 'required|exists:ps_details,ps_id',
                        'ps_name' => 'required|max:255|unique:ps_details,ps_name',      
                    ] ); 

                        
                    $id = $request->input('id');
                    $ps_name = trim(strtoupper($request->input('ps_name')));
                
                    $data = [
                        'ps_name'=>$ps_name,
                        'updated_at'=>Carbon::today()
                        ];

                    Ps_detail::where('ps_id',$id)->update($data);
                    
                    return 1;
                    
                }

                //Delete Police Station
                    public function destroy_ps(Request $request){
                        $id = $request->input('id');
                        Ps_detail::where('ps_id',$id)->delete();
                        return 1;
                    }

                    public function destroy_seizure_ps_record(Request $request){
                        $id = $request->input('id');
                        User::where('ps_id',$id)->delete();
                        Seizure::where('ps_id',$id)->delete();
                        Ps_detail::where('ps_id',$id)->delete();
                        return 1;

                    }

        //Police Staion:End


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
                    0 =>'ID', 
                    1 =>'STORAGE NAME',
                    2 =>'DISTRICT NAME',
                    3=>'ACTION'
                );
                $totalData =Storage_detail::count();
        
                $totalFiltered = $totalData; 
        
                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
        
                if(empty($request->input('search.value'))){
                    $storages = Storage_detail::join('districts','storage_details.district_id','=','districts.district_id')
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy('storage_id',$dir)
                                    ->get();
                    $totalFiltered = Storage_detail::join('districts','storage_details.district_id','=','districts.district_id')
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
                        $nestedData['ID'] = $storage->storage_id;
                        $nestedData['STORAGE NAME'] = $storage->storage_name;
                        $nestedData['DISTRICT NAME'] = $storage->district_name;
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

             //Adding new STORAGE
             public function store_storage(Request $request){

                $this->validate ( $request, [                     
                    'malkhana_name' => 'required|max:255|unique:storage_details,storage_name',
                    'district_name' => 'required|exists:districts,district_id'                          

                ] );

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
                    'id' => 'required|exists:storage_details,storage_id',
                    'malkhana_name' => 'required|max:255|unique:storage_details,storage_name',      
                ]); 

                    
                $id = $request->input('id');
                $malkhana_name = trim(strtoupper($request->input('malkhana_name')));
            
                $data = [
                    'storage_name'=>$malkhana_name,
                    'updated_at'=>Carbon::today()
                ];

                Storage_detail::where('storage_id',$id)->update($data);
                
                return 1;
            
            }

              //Delete storage
             public function destroy_storage(Request $request){
                $this->validate ( $request, [ 
                    'id' => 'required|exists:storage_details,storage_id'
                ]); 

                $id = $request->input('id');

                Storage_detail::where('storage_id',$id)->delete();

                return 1;
              }

              public function destroy_seizure_storage_record(Request $request)
              {
                $this->validate ( $request, [ 
                    'id' => 'required|exists:storage_details,storage_id'
                ]); 

                $id = $request->input('id');
                
                Seizure::where('storage_location_id',$id)->delete();
                Storage_detail::where('storage_id',$id)->delete();

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
                'user_id' => 'required|unique:users,user_id|max:30',
                'user_name' => 'required|max:100|unique:users,user_name',
                'password' => 'required|confirmed|max:20',
                'user_type' => 'required|max:30',
                'stakeholder_name' => 'nullable|integer|unique:users,agency_id|exists:agency_details,agency_id',
                'certifying_court_name' => 'nullable|integer|unique:users,certifying_court_id|exists:certifying_court_details,court_id',
                'ndps_court_name' => 'nullable|integer|unique:users,ndps_court_id|exists:ndps_court_details,ndps_court_id',
                'ps_name' => 'nullable|integer|unique:users,ps_id|exists:ps_details,ps_id',
                'email_id' => 'required|email|max:100|unique:users,email',
                'contact_no' => 'nullable|integer|unique:users,contact_no'         
            ] ); 


            $user_id = $request->input('user_id');
            $user_name = $request->input('user_name');
            $password = Hash::make($request->input('password'));
            $user_type = $request->input('user_type');
            $agency_name = $request->input('agency_name');
            $certifying_court_name = $request->input('certifying_court_name');
            $ndps_court_name = $request->input('ndps_court_name');
            $ps_name = $request->input('ps_name');
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

            $this->validate($request,
                    ['new_password' => 'required|confirmed|alpha_dash|min:6|max:15|different:current_password',
                    'current_password' =>'required'
                    ]);
            
            $uid=$request->input('uid');  
            $new_password = Hash::make($request->input('new_password'));
            $cur_password =  $request->input('current_password');
            $data['user'] = User::where('user_id',$uid)
                            ->get();

            if(Hash::check($cur_password, $data['user'][0]['password']))
            {
                User::where('user_id',$uid)
                ->update(['password'=>$new_password, 'updated_at'=>Carbon::today()]);
            
                return 1;
            }

            else
             return 0;

           
            
           
        }

        //Update Password:End
 }

        


