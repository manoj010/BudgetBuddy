<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Requests\BaseCategoryRequest;
use App\Models\API\Category\IncomeCategory;

class IncomeCategoryController extends BaseCategoryController
{
    protected $incomeCategory;

    public function __construct(IncomeCategory $incomeCategory)
    {
        $this->incomeCategory = $incomeCategory;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->allResource($this->incomeCategory);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseCategoryRequest $request)
    {
        $validatedData = $request->validated();
        return $this->createResource($validatedData, $this->incomeCategory);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->specificResource($this->incomeCategory, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BaseCategoryRequest $request, $id)
    {
        $resource = $this->incomeCategory->find($id);
        if (!$resource) {
            return $this->notFound();
        }
        $validatedData = $request->validated();
        return $this->updateResource($validatedData, $resource);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $resource = $this->incomeCategory->find($id);
        if (!$resource) {
            return $this->notFound();
        }
        return $this->deleteResource($resource);
    }
}
