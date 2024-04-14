<?php

namespace App\Http\Controllers;
use App\Models\Student; 
use Illuminate\Http\Request;

class StudentController extends Controller
{
    function index(){
        $data['getStudent'] = Student::all();
        return view('student.index', $data);
    }

    function createStudent(Request $request){
        $student = new Student();
        $student->name = $request->name;
        $student->age = $request->age;
        $student->mobile_number = $request->phone_number;
        $student->save();
        return $student;
    }

    function getStudent(Request $request){
        $students = Student::all();
        return response()->json(['students' => $students], 200);

    }

    function duplicateRecords(Request $request){
        $existingStudents = Student::all();  
        foreach ($existingStudents as $data) {
            $student = new Student();
            $student->name = $data->name;
            $student->age = $data->age;
            $student->mobile_number = $data->mobile_number;  
            $student->save();
        }
        return response()->json(['message' => 'Student records duplicated successfully'], 200);

       //dd("Student duplicate record added successfully");
    }
}
