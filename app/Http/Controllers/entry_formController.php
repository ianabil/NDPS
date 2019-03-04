<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Narcotic;
use App\District;
use App\Unit;
use App\Agency_detail;
use App\Court_detail;
use App\Seizure;
use App\Storage_detail;
use App\User;
use Carbon\Carbon;
use DB;
use Auth;




class entry_formController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agency_id =Auth::user()->stakeholder_id;

        $data = array();
        
        $data['drugs'] = Narcotic::select('drug_id','drug_name')->get();
        $data['districts'] = District::select('district_id','district_name')->get();
        $data['units'] = Unit::select('unit_id','unit_name')->get();
        $data['courts'] = Court_detail::select('court_id','court_name')->get();
            
        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        from seizures s left join units u1 on cast(s.unit_name as int)=u1.unit_id 
        left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        left join court_details on s.certification_court_id = court_details.court_id
        left join districts on s.district_id = districts.district_id
        where s.submit_flag='N' and s.agency_id= ".$agency_id." order by seizure_id";

        $data['seizures']=DB::select($sql);

        foreach($data['seizures'] as $seizures){
            if(empty($seizures->date_of_seizure))
                $seizures->date_of_seizure='';
            else
                $seizures->date_of_seizure = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            
            
            if(empty($seizures->date_of_disposal))
                $seizures->date_of_disposal='';
            else
                $seizures->date_of_disposal = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');


            if(empty($seizures->date_of_certification))
                $seizures->date_of_certification='';
            else
                $seizures->date_of_certification = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
        }
            

        return view('entry_form',compact('data'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $month_of_report = date('Y-m-d', strtotime('01-'.$request->input('month_of_report')));

        seizure::where([
                        ['submit_flag','N'],
                        ['agency_id',Auth::user()->stakeholder_id],
                        ['month_of_report',$month_of_report]
                ])->delete();

        $nature_of_narcotic = $request->input('nature_of_narcotic'); 
        $quantity_of_narcotics = $request->input('quantity_of_narcotics'); 
        $narcotic_unit = $request->input('narcotic_unit'); 
        $date_of_seizure =$request->input('date_of_seizure'); 
        $date_of_disposal =$request->input('date_of_disposal'); 
        $disposal_quantity = $request->input('disposal_quantity');
        $disposal_unit = $request->input('disposal_unit');
        $undisposed_quantity = $request->input('undisposed_quantity');
        $unit_of_undisposed_quantity = $request->input('unit_of_undisposed_quantity'); 
        $place_of_storage = $request->input('place_of_storage'); 
        $case_details = $request->input('case_details'); 
        $district = $request->input('district'); 
        $where = $request->input('where'); 
        $date_of_certification = $request->input('date_of_certification'); 
        $counter= $request->input('counter');
        $agency_id= Auth::user()->stakeholder_id;
        $user_name=Auth::user()->user_name;
        $remarks=$request->input('remarks');
        $update_date = Carbon::today();  
        $uploaded_date = Carbon::today();  
        $submit_flag=$request->input('submit_flag');
        
            
        for($i=0;$i<$counter;$i++)
        {
            //For preventing blank date value getting inserted as 1970-01-01 into DB
            if(empty($date_of_disposal[$i]))
                $disposal_date =NULL;
            else
                $disposal_date = date('Y-m-d', strtotime($date_of_disposal[$i]));

            
            if(empty($date_of_certification[$i]))
                $certification_date =NULL;
            else
                $certification_date = date('Y-m-d', strtotime($date_of_certification[$i]));

            seizure::insert(

                ['drug_name'=>$nature_of_narcotic[$i],
                 'quantity_of_drug'=>$quantity_of_narcotics[$i],
                 'unit_name'=>$narcotic_unit[$i],
                 'date_of_seizure'=> date('Y-m-d', strtotime($date_of_seizure[$i])),
                 'date_of_disposal'=>$disposal_date,
                 'disposal_quantity'=>$disposal_quantity[$i],
                 'unit_of_disposal_quantity'=>$disposal_unit[$i],
                 'undisposed_quantity'=>$undisposed_quantity[$i],
                 'undisposed_unit'=>$unit_of_undisposed_quantity[$i],
                 'storage_location'=>$place_of_storage[$i],
                 'case_details'=>$case_details[$i],
                 'district_id'=>$district[$i],
                 'date_of_certification'=>$certification_date,
                 'agency_id'=>$agency_id,
                 'certification_court_id'=>$where[$i],
                 'remarks'=>$remarks[$i],
                 'updated_at'=>$update_date,
                 'created_at'=>$uploaded_date,
                 'user_name'=>$user_name,
                 'submit_flag'=>$submit_flag,
                 'month_of_report'=>$month_of_report
                 ]

            );

        }
        return 1;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function post_submission_preview(){
        $agency_id = Auth::user()->stakeholder_id;
        $data = array();

        $sql="select s.*,u1.unit_name seizure_unit, s.disposal_quantity, u2.unit_name disposal_unit,
        s.undisposed_quantity,u3.unit_name undisposed_unit_name, court_details.*, districts.*
        from seizures s left join units u1 on cast(s.unit_name as int)=u1.unit_id 
        left join units u2 on cast(s.unit_of_disposal_quantity as int)=u2.unit_id 
        left join units u3 on cast(s.undisposed_unit as int)=u3.unit_id
        left join court_details on s.certification_court_id = court_details.court_id
        left join districts on s.district_id = districts.district_id
        where s.submit_flag='S' and s.agency_id= ".$agency_id." and s.month_of_report = (select max(month_of_report) from seizures where agency_id = ".$agency_id.")
        order by seizure_id";

        $data['seizures']=DB::select($sql);
        $data['agency_details'] = Agency_detail::where('agency_id',$agency_id)->get();
                
        foreach($data['seizures'] as $seizures){
            if(empty($seizures->date_of_seizure))
                $seizures->date_of_seizure='';
            else
                $seizures->date_of_seizure = Carbon::parse($seizures->date_of_seizure)->format('d-m-Y');
            
            
            if(empty($seizures->date_of_disposal))
                $seizures->date_of_disposal='';
            else
                $seizures->date_of_disposal = Carbon::parse($seizures->date_of_disposal)->format('d-m-Y');


            if(empty($seizures->date_of_certification))
                $seizures->date_of_certification='';
            else
                $seizures->date_of_certification = Carbon::parse($seizures->date_of_certification)->format('d-m-Y');
           
                
            $seizures->month_of_report = date('F',strtotime($seizures->month_of_report)).'-'.date('Y',strtotime($seizures->month_of_report));
        }
        
        return view('post_submission_preview',compact('data'));   

    }

    public function district_wise_court(Request $request){

        $district = $request->input('district'); 

        $data['district_wise_court']=Court_detail::
                                    where('district_id','=', $district )
                                    ->get();

          

        echo json_encode($data);


    }

    public function narcotic_suggestion(Request $request){
        $word = $request->input('word');
        $available_narcotics = Narcotic::select('drug_name')
                                        ->get();
        echo json_encode($available_narcotics);
    }

    public function submission_validation(Request $request){

       

        $month_of_report = date('Y-m-d',strtotime('01-'.$request->input('month_of_report'))); 
        $submit_flag='S';
        $agency_id=Auth::user()->stakeholder_id;
        $month_of_report=Seizure::
                                    where([['month_of_report','=', $month_of_report], 
                                    ['submit_flag','=', $submit_flag ],
                                    ['agency_id','=', $agency_id ]])
                                    ->count();

    
        echo json_encode($month_of_report);

    }
}
