<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Agency_detail;
use App\Court_detail;
use App\District;

use Carbon\Carbon;
use DB;

class MasterMaintenanceController extends Controller
{
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

     public function index(Request $request)
     {
         $data= array();

        $data['districts'] = District::select('district_id','district_name')->orderBy('district_name')->get();
        

        return view('court_view',compact('data'));
    }

    public function store_court(Request $request){

        $this->validate ( $request, [ 
            
            'court_name' => 'required|max:255|unique:court_details,court_name',
            'district_name' => 'required|max:255'

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




                    
           





    }

    


