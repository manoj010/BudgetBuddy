<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Http\Requests\StoreBaseModelRequest;
use App\Http\Requests\UpdateBaseModelRequest;

class BaseModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBaseModelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BaseModel $baseModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BaseModel $baseModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBaseModelRequest $request, BaseModel $baseModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BaseModel $baseModel)
    {
        //
    }
}
