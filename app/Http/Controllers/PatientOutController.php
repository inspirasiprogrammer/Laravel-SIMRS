<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patientout;
use App\Doctor;
use Carbon\Carbon;

class PatientoutController extends Controller
{

    public function index()
    {
        $doctors = Doctor::all();
        $patientout = Patientout::whereDate('created_at' , '=' , Carbon::today()->toDateString())->orderBy('created_at' , 'desc')->paginate(50);
        $patientoutID = Patientout::orderBy('created_at','desc')->first();
        $day = Carbon::today()->day;
        $month = Carbon::today()->month;
        $year = Carbon::today()->year;
        $date = $day . "." . $month . "." . $year;
        $last_day = Carbon::today()->day -1;
        if($last_day + 1 == $day){

            $patient_id = $patientoutID->patient_id+1;
            for($i = $patient_id ; $i <=$patient_id ; $i++)
                $patient_id = "0$i"; 

        }
        return view('admin.patientout' , ['patientouts' => $patientout, 'doctors' => $doctors , 'patient_id' => $patient_id , 'date' => $date]);
    }


    public function show()
    {
        $patientout = Patientout::orderBy('created_at' , 'desc')->paginate(50);
        return view('admin.patientout' , ['patientouts' => $patientout]);
    }

    public function store(Request $request) 
    {
        date_default_timezone_set("Asia/Dhaka");
        $patientout = new Patientout();
        $patientout->name = $request['name'];
        $patientout->patient_id = $request['patient_id'];
        $patientout->age = $request['age'];
        $patientout->mobile = $request['mobile'];
        $patientout->address = $request['address'];
        $patientout->doctor_id = $request['doctor_id'];
        // $patientout->receive_cash = $request['receive_cash'];
        $patientout->save();
        $day = Carbon::today()->day;
        $month = Carbon::today()->month;
        $year = Carbon::today()->year;
        $date = $day . "." . $month . "." . $year;
        return redirect()->route('invoiceout.index')->with(['success' => 'Patients Information Insert Successfully & Add Patients Report' , 'patient_name' => $patientout->name , 'patient_id' => $request['patient_id'], 'date' => $date ] );
    }

    public function update(Request $request,$id)
    {
        $patientout = Patientout::find($id);
        $patientout->name = $request['name'];
        $patientout->mobile = $request['mobile'];
        $patientout->address = $request['address'];
        $patientout->doctor_id = $request['doctor_id'];
        $patientout->save();
        return redirect()->back()->with(['success' => 'Updtaed Successfully'] );
    }

    public function destroy($id)
    {
        $patientout = Patientout::find($id);
        if(!$patientout){
            return redirect()->route('patientout.index')->with(['fail' => 'Page not found !']);
        }
        $patientout->delete();
        return redirect()->route('patientout.index')->with(['success' => 'Deleted Successfully.']);
    }

}