<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = auth()->user()->staff;
        return $this->sendResponse($staff, "Data retrieved");
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
        $input = $request->only('name', 'position', 'email', 'phone', 'address', 'salary', 'start_date');

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'position' => 'required',
            'address' => 'required',
            'salary' => 'required',
            'start_date' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation errors', $validator->errors(), 422);
        }
        
        $staff = auth()->user()->staff()->create($input);

        return $this->sendResponse($staff, 'Staff created successfully', 201);
    }

    /**
     * Valdate input from request
     */
    public function validateInput($request)
    {
        $input = $request->only('name', 'position', 'email', 'phone', 'address', 'salary', 'start_date');

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'position' => 'required',
            'address' => 'required',
            'salary' => 'required',
            'start_date' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation errors', $validator->errors(), 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $staff
     * @return \Illuminate\Http\Response
     */
    public function show($staff)
    {
        $staff = auth()->user()->staff()->where('id', $staff)->first();

        if(!$staff) {
            return $this->resourceNotFoundResponse('staff');
        }

        return $this->sendResponse($staff, 'Staff Retrieved');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $staff)
    {
        $input = $request->only('name', 'position', 'email', 'phone', 'address', 'salary', 'start_date');

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'position' => 'required',
            'address' => 'required',
            'salary' => 'required',
            'start_date' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation errors', $validator->errors(), 422);
        }

        // get staff
        $staff = auth()->user()->staff()->where('id', $staff)->first();

        if(!$staff) {
            return $this->resourceNotFoundResponse('staff');
        }

        $staff->update($input);

        $staff->refresh();

        return $this->sendResponse($staff, 'Staff Updated', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy($staff)
    {
        // get staff
        $staff = auth()->user()->staff()->where('id', $staff)->first();

        if(!$staff) {
            return $this->resourceNotFoundResponse('staff');
        }

        $staff->delete();

        return $this->sendResponse([], 'Staff Deleted');
    }
}
