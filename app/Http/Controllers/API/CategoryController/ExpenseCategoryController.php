<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Requests\BaseCategoryRequest;
use App\Models\API\Category\ExpenseCategory;

class ExpenseCategoryController extends BaseCategoryController
{
    protected $expensesCategory;

    public function __construct(ExpenseCategory $expensesCategory)
    {
        $this->expensesCategory = $expensesCategory;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->allResource($this->expensesCategory);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseCategoryRequest $request)
    {
        $validatedData = $request->validated();
        return $this->createResource($validatedData, $this->expensesCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->specificResource($this->expensesCategory, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BaseCategoryRequest $request, $id)
    {
        $resource = $this->expensesCategory->find($id);
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
        $resource = $this->expensesCategory->find($id);
        if (!$resource) {
            return $this->notFound();
        }
        return $this->deleteResource($resource);
    }
}
