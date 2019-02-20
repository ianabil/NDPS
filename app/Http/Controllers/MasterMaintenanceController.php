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

       // Data Table Code Starts
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

    }
    


