<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Requests\BaseCategoryRequest;
use App\Models\API\Category\LoanCategory;

class LoanCategoryController extends BaseCategoryController
{
    protected $loanCategory;

    public function __construct(LoanCategory $loanCategory)
    {
        $this->loanCategory = $loanCategory;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->allResource($this->loanCategory);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseCategoryRequest $request)
    {
        $validatedData = $request->validated();
        return $this->createResource($validatedData, $this->loanCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->specificResource($this->loanCategory, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BaseCategoryRequest $request, $id)
    {
        $resource = $this->loanCategory->find($id);
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
        $resource = $this->loanCategory->find($id);
        if (!$resource) {
            return $this->notFoundResponse();
        }
        return $this->deleteResource($resource);
    }
}
