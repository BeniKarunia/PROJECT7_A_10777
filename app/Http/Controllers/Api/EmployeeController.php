<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Employee;

class EmployeeController extends Controller

{
    public function index()
    {
        $employees = Employee::all();

        if(count($employees) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $employees
            ],200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_pegawai' =>'required|max:60|unique:employees',
            'nip' =>'required|max:6',
            'role' =>'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required',
            'no_telp' =>'required|max:13'
        ]);
        
        if($validate->fails())
            return response(['message'=>$validate->errors()],400);
        
        $employee = Employee::create($storeData);
        return response([
            'message' =>'Add Employee Success',
            'data' => $employee
        ], 200);
    }

    public function show($id)
    {
        $employee = Employee::find($id);
        if(!is_null($employee)){
            return response([
                'message' =>'Retrieve Employee Success',
                'data' => $employee
            ], 200);
        }
        return response([
            'message' =>'Employee Not Found',
            'data' => null
        ], 404);
    
    }
    
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response([
                'message' =>'Employee Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_pegawai' =>['required','max:60',Rule::unique('employees')->ignore($employee)],
            'nip' =>'required|max:6',
            'role' =>'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required',
            'no_telp' =>'required|max:13'
        ]);
        if($validate->fails())
            return response(['message'=>$validate->errors()],400);
        
        $employee ->nama_pegawai = $updateData['nama_pegawai'];
        $employee ->nip = $updateData['nip'];
        $employee ->role = $updateData['role'];
        $employee ->alamat = $updateData['alamat'];
        $employee ->tgl_lahir = $updateData['tgl_lahir'];
        $employee ->no_telp = $updateData['no_telp'];

        if($employee->save()){
            return response([
                'message' =>'Update Employee Success',
                'data' => $employee
            ], 200);
        }

        return response([
            'message' =>'Update employee Failed',
            'data' => $employee
        ], 400);

    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response([
                'message' =>'Employee Not Found',
                'data' => null
            ], 404);
        }

        if($employee->delete()){
            return response([
                'message' =>'Delete Employee Success',
                'data' => $employee
            ], 200);
        }

        return response([
            'message' =>'Delete Employee Failed',
            'data' => null
        ], 400);

    }
}
