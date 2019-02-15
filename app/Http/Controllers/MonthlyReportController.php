<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Narcotic;
use App\District;
use App\Unit;
use App\Agency_detail;
use App\Court_detail;
use App\Magistrate;
use App\Seizure;
use App\Storage_detail;
use App\User;
use App\User_detail;
use Carbon\Carbon;
use DB;


class MonthlyReportController extends Controller
{
    public function submitted_stakeholders(Request $request){
        $month_of_report = date('Y-m-d', strtotime('01-'.$request->input('month_of_report')));
        
        $data = array();

        $data['agency'] = Seizure::join('agency_details','seizures.agency_id','=','agency_details.agency_id')
                                ->where([['month_of_report',$month_of_report],['submit_flag','S']])
                                ->select('agency_details.agency_id','agency_details.agency_name')
                                ->distinct()
                                ->get();

        $data['court'] = Seizure::join('court_details','seizures.court_id','=','court_details.court_id')
                                ->where([['month_of_report',$month_of_report],['submit_flag','S']])
                                ->select('court_details.court_id','court_details.court_name')
                                ->distinct()
                                ->get();

        echo json_encode($data);
    }


    public function show_monthly_report(Request $request){
        $month_of_report = date('Y-m-d', strtotime('01-'.$request->input('month_of_report')));
        $stakeholder = $request->input('stakeholder');

        
        // For dataTable :: STARTS
        $columns = array( 
            0 =>'Narcotic Nature',
            1 =>'Seize Quantity',
            2 =>'Seizure Date',
            3 =>'Disposal Date',
            4 =>'Disposal Quantity',
            5 =>'Not Disposed Quantity',
            6 =>'Storage Place',
            7 =>'Case Details',
            8 =>'Where',
            9 =>'Certification Date',
            10 => 'Remarks',
            11 => 'Sl No'
        );

        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        from seizures s inner join units u1 on cast(s.unit_name as int)=u1.unit_id 
        left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        left join court_details on s.certification_court_id = court_details.court_id
        left join districts on s.district_id = districts.district_id
        where s.submit_flag='S' and s.user_name= '".$stakeholder."' and 
        s.month_of_report = '".$month_of_report."' order by seizure_id";

        $data['seizures']=DB::select($sql);

        $record = array();

        $report['Sl No'] = 0;
        foreach($data['seizures'] as $seizures){
            $report['Sl No'] = $report['Sl No'] + 1;
            $report['Narcotic Nature'] = $seizures->drug_name;
            $report['Seize Quantity'] = $seizures->quantity_of_drug. " ".$seizures->seizure_unit;
            $report['Seizure Date'] = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            $report['Disposal Date'] = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');
            $report['Disposal Quantity'] = $seizures->disposal_quantity. " ".$seizures->disposal_unit;
            $report['Not Disposed Quantity'] = $seizures->undisposed_quantity. " ".$seizures->undisposed_unit_name;
            $report['Storage Place'] = $seizures->storage_location;
            $report['Case Details'] = $seizures->case_details;
            $report['Where'] = $seizures->court_name;
            $report['Certification Date'] = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
            $report['Remarks'] = $seizures->remarks;

            $record[] = $report;

        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval(sizeof($record)),
            "recordsFiltered" =>intval(sizeof($record)),
            "data" => $record
        );
        
        echo json_encode($json_data);

    }
}
