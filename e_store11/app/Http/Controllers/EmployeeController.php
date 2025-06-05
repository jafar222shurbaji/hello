<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseHasCookie;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{
    //

    public function index()
    {
        $employee = Employee::get();
        return EmployeeResource::collection($employee);
    }


    public function store(CreateUpdateEmployeeRequest $request)
    {
        $ValidatedDate = $request->validated();
        $ValidatedDate['password'] = bcrypt($ValidatedDate['password']);

        $user = Employee::create($ValidatedDate);
        return (new EmployeeResource($user))->response()->setStatusCode(201);
    }

    public function update(CreateUpdateEmployeeRequest $request, int $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(["message" => "Employee not found"], 404);
        }
        $employee->update(
            [
                "user_name" => $request->user_name,
                "email" => $request->email,
                "password" => Hash::make($request->password),

            ]
        );
        return (new EmployeeResource($employee))->response()->setStatusCode(200);
    }

    public function show(int $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(["message" => "Employee not found"], 404);
        }
        return new EmployeeResource($employee);
    }
    public function destroy(int $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(["message" => "Employee not found"], 404);
        }
        $employee->delete(); // Soft delete the user
        return response()->json(["message => User has been deleted"], 200);
    }

    public function restore(int $id)
    {
        $DeleteEmployee = Employee::withTrashed()->find($id);

        if (!$DeleteEmployee) {
            return response()->json(["message" => "employee not found"], 404);
        }
        $DeleteEmployee->restore();
        return response()->json(['message' => 'User restored successfully'], 200);
    }

    public function DeletedUsers()
    {
        return EmployeeResource::collection(Employee::onlyTrashed()->get());
    }
}
