<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseCategoryRequest;
use App\Http\Resources\Category\{BaseCategoryCollection, BaseCategoryResource};
use App\Models\API\Category\ExpenseCategory;
use App\Services\UserService;
use App\Traits\{AppResponse, UserOwnership};
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryController extends Controller
{
    use UserOwnership, AppResponse;

    protected $expensesCategory, $userService;

    public function __construct(ExpenseCategory $expensesCategory, UserService $userService)
    {
        $this->expensesCategory = $expensesCategory;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = $this->userService->getUserId();
        if ($response = $this->checkResource($this->expensesCategory, $userId)) {
            return $response;
        }
        $expenseCategories = $this->expensesCategory->where('user_id', $userId)->get();
        $data = new BaseCategoryCollection($expenseCategories);
        return $this->successResponse($data);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseCategoryRequest $request, Authenticatable $user)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request -> validated();
            $validatedData['user_id'] = $user->id;
            $expensesCategory = $this->expensesCategory->create($validatedData);
            DB::commit();
            $data = new BaseCategoryResource($expensesCategory);
            return $this->createdResponse($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userId = auth()->id();

        if ($response = $this->findResource($this->expensesCategory, $id)) {
            return $response;
        }

        $expenseCategory = $this->expensesCategory->where('user_id', $userId)->find($id);
        try {
            $data = new BaseCategoryResource($expenseCategory);
            return $this -> successResponse($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BaseCategoryRequest $request, ExpenseCategory $expenseCategory)
    {
        if ($response = $this->checkOwnership($expenseCategory)) {
            return $response;
        }

        try {
            DB::beginTransaction();
            $expenseCategory->update($request->validated());
            DB::commit();
            $data = new BaseCategoryResource($expenseCategory);
            return $this -> successResponse($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($response = $this->checkDelete($expenseCategory)) {
            return $response;
        }

        try {
            DB::beginTransaction();
            $expenseCategory->delete();
            DB::commit();
            return $this -> deleteResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        } 
    }
}
