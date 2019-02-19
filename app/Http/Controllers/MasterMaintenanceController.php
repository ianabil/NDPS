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
    
}
