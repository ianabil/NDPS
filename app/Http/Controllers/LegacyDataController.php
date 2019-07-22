<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Agency_detail;
use App\Court_detail;
use App\District;
use App\User;
use Carbon\Carbon;
use App\Seizure;
use App\Narcotic;
use App\Narcotic_unit;
use App\Unit;
use App\Ps_detail;
use App\Storage_detail;
use Auth;
use DB;

class LegacyDataController extends Controller
{    
    public function index()
    {
        $data = array();
        
        $data['ps'] = Ps_detail::select('ps_id','ps_name')
                                ->orderBy('ps_name')
                                ->get();

        $data['stakeholders'] = Agency_detail::select('agency_id','agency_name')
                                            ->where('agency_name','ilike','%NCB%')
                                            ->orderBy('agency_name')
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
                                            ->where('agency_name', 'not like', '%NCB%')
                                            ->orderBy('agency_name')
                                            ->get(); 
        
        return view('legacy_data_entry_form', compact('data'));
    }

    
    public function store(Request $request)
    {
        //
    }
    

}
