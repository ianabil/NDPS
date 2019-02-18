<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Agency_detail;

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

    
    
}
