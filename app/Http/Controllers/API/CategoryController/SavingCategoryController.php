<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Requests\BaseCategoryRequest;
use App\Models\API\Category\SavingCategory;

class SavingCategoryController extends BaseCategoryController
{
    protected $savingCategory;

    public function __construct(SavingCategory $savingCategory)
    {
        $this->savingCategory = $savingCategory;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->allResource($this->savingCategory);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseCategoryRequest $request)
    {
        $validatedData = $request->validated();
        return $this->createResource($validatedData, $this->savingCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->specificResource($this->savingCategory, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BaseCategoryRequest $request, $id)
    {
        $resource = $this->savingCategory->find($id);
        if (!$resource) {
            return $this->notFoundResponse();
        }
        $validatedData = $request->validated();
        return $this->updateResource($validatedData, $resource);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $resource = $this->savingCategory->find($id);
        if (!$resource) {
            return $this->notFoundResponse();
        }
        return $this->deleteResource($resource);
    }
}
