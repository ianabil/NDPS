<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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



class MagistrateController extends Controller
{
    /* For Judicial Magistrate */

    // Show index page for Magistrate's end
    public function show_magistrate_index(Request $request){

        $data = array();
        
        $data['ps'] = Ps_detail::select('ps_id','ps_name')->get();
        
        return view('magistrate_entry_form', compact('data'));
    }


    //Fetch case details of a specific case no.
    public function fetch_case_details(Request $request){
        $court_id =Auth::user()->court_id;
        $ps = $request->input('ps');
        $case_no = $request->input('case_no');
        $case_year = $request->input('case_year');

        $data['case_details'] = Seizure::join('ps_details','seizures.ps_id','=','ps_details.ps_id')
                        ->join('narcotics','seizures.drug_id','=','narcotics.drug_id')
                        ->join('units','seizure_quantity_weighing_unit_id','=','units.unit_id')
                        ->join('storage_details','seizures.storage_location_id','=','storage_details.storage_id')
                        ->join('districts','seizures.district_id','=','districts.district_id')
                        ->join('court_details','seizures.certification_court_id','=','court_details.court_id')
                        ->where([['seizures.ps_id',$ps],['seizures.case_no',$case_no],['seizures.case_year',$case_year],['certification_court_id',$court_id]])
                        ->limit(1)
                        ->get();
        foreach($data['case_details'] as $case_details){
            $case_details->date_of_seizure = Carbon::parse($case_details->date_of_seizure)->format('d-m-Y');
            if($case_details->certification_flag=='Y')
                $case_details->date_of_certification = Carbon::parse($case_details->date_of_certification)->format('d-m-Y');
            
        }

        echo json_encode($data);
    }

    // Do certification
    public function certify(Request $request){
        $this->validate ( $request, [ 
            'ps' => 'required|integer',
            'case_no' => 'required|integer',
            'case_year' => 'required|integer',
            'certification_date' => 'required|date'
        ] ); 

        $ps = $request->input('ps'); 
        $case_no = $request->input('case_no'); 
        $case_year = $request->input('case_year');
        $certification_date = Carbon::parse($request->input('certification_date'))->format('Y-m-d');

        $data = [
            'certification_flag'=>'Y',
            'date_of_certification'=>$certification_date,
            'updated_at'=>Carbon::today()
        ];

        Seizure::where([['ps_id',$ps],['case_no',$case_no],['case_year',$case_year]])->update($data);
        
        return 1;
        
    }
}
