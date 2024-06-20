<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Requests\BaseCategoryRequest;
use App\Models\API\Category\IncomeCategory;
use Illuminate\Contracts\Auth\Authenticatable;

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
        $user = auth()->user();
        return $this->allResource($this->incomeCategory, $user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseCategoryRequest $request, Authenticatable $user)
    {
        $validatedData = $request->validated();
        return $this->createResource($validatedData, $this->incomeCategory, $user);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();
        return $this->specificResource($this->incomeCategory, $user, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BaseCategoryRequest $request, $id)
    {
        $resource = $this->incomeCategory->find($id);
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
        $resource = $this->incomeCategory->find($id);
        if (!$resource) {
            return $this->notFoundResponse();
        }
        return $this->deleteResource($resource);
    }
}
