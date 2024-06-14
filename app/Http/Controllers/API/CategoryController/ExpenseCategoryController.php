<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseCategoryRequest;
use App\Http\Resources\Category\BaseCategoryCollection;
use App\Http\Resources\Category\BaseCategoryResource;
use App\Models\API\Category\ExpenseCategory;
use App\Services\UserService;
use App\Traits\ResourceNotFound;
use App\Traits\UserOwnership;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ExpenseCategoryController extends Controller
{
    use UserOwnership, ResourceNotFound;

    protected $expense_cat, $userService;

    public function __construct(ExpenseCategory $expense_cat, UserService $userService)
    {
        $this->expense_cat = $expense_cat;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = $this->userService->getUserId();

        if ($response = $this->checkResource($this->expense_cat, $userId)) {
            return $response;
        }

        $expenseCategories = $this->expense_cat->where('user_id', $userId)->get();

        return response()->json([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'data' => new BaseCategoryCollection($expenseCategories)
        ], Response::HTTP_OK);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseCategoryRequest $request, Authenticatable $user)
    {
        try {
            DB::beginTransaction();
            $expense_cat = $this->expense_cat->create([
                'user_id' => $user->id,
                'name' => $request->name,
                'description' => $request->description,
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_CREATED,
                'data' => new BaseCategoryResource($expense_cat)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($categoryId = null)
    {
        $user = auth()->user();

        $expenseCategory = $this->expense_cat->where('user_id', $user->id)->find($categoryId);

        if (is_null($expenseCategory)) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Expense category not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => new BaseCategoryResource($expenseCategory)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => new BaseCategoryResource($expenseCategory),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'message' => 'Expense Category successfully Deleted'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
