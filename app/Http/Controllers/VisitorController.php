<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitors = auth()->user()->visitors;
        return $this->sendResponse($visitors, 'Data retrieved');
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
        $input = $request->only('full_name', 'occupation', 'note', 'staff_id', 'email', 'phone');

        $validator = Validator::make($input, [
            'full_name' => 'required',
            'occupation' => 'nullable',
            'note' => 'nullable',
            'staff_id' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation errors', $validator->errors(), 422);
        }
        
        $visitor = auth()->user()->visitors()->create($input);

        return $this->sendResponse($visitor, 'Visitor logged successfully', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $visitor
     * @return \Illuminate\Http\Response
     */
    public function show($visitor)
    {
        $visitor = auth()->user()->visitors()->where('id', $visitor)->first();

        if(!$visitor) {
            return $this->resourceNotFoundResponse('visitor');
        }

        return $this->sendResponse($visitor, 'Visitor Retrieved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $visitor)
    {
        $input = $request->only('full_name', 'occupation', 'note', 'email', 'phone');

        $validator = Validator::make($input, [
            'full_name' => 'required',
            'occupation' => 'nullable',
            'note' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation errors', $validator->errors(), 422);
        }

        // get visitor
        $visitor = auth()->user()->visitors()->where('id', $visitor)->first();

        if(!$visitor) {
            return $this->resourceNotFoundResponse('visitor');
        }

        $visitor->update($input);

        $visitor->refresh();

        return $this->sendResponse($visitor, 'Visitor Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy($visitor)
    {
        // get visitor
        $visitor = auth()->user()->visitors()->where('id', $visitor)->first();

        if(!$visitor) {
            return $this->resourceNotFoundResponse('visitor');
        }

        $visitor->delete();

        return $this->sendResponse([], 'Visitor deleted');
    }
}
