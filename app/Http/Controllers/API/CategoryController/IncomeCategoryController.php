<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequests\IncomeCategoryRequest;
use App\Http\Resources\Category\IncomeCategory\IncomeCategoryCollection;
use App\Http\Resources\Category\IncomeCategory\IncomeCategoryResource;
use App\Models\API\CategoryModels\IncomeCategory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IncomeCategoryController extends Controller
{
    protected $income_cat;

    public function __construct(IncomeCategory $income_cat)
    {
        $this->income_cat = $income_cat;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $incomeCategories = $this->income_cat->where('user_id', $user->id)->get();

        if ($incomeCategories->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'No income categories found for the authenticated user'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'data' => new IncomeCategoryCollection($incomeCategories)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeCategoryRequest $request, Authenticatable $user)
    {
        try {
            DB::beginTransaction();
            $income_cat = $this->income_cat->create([
                'user_id' => $user->id,
                'name' => $request->name,
                'description' => $request->description,
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_CREATED,
                'data' => new IncomeCategoryResource($income_cat)
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

        $incomeCategory = $this->income_cat->where('user_id', $user->id)->find($categoryId);

        if (is_null($incomeCategory)) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Income category not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => new IncomeCategoryResource($incomeCategory)
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
    public function update(IncomeCategoryRequest $request, IncomeCategory $incomeCategory)
    {
        $user = auth()->user();

        // dd($user->id, $incomeCategory->user_id);

        if ($incomeCategory->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'You do not have permission to update this resource'
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            DB::beginTransaction();
            $incomeCategory->update($request->validated());
            DB::commit();
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => new IncomeCategoryResource($incomeCategory),
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
    public function destroy(IncomeCategory $incomeCategory)
    {
        $user = auth()->user();

        // dd($user->id, $incomeCategory->user_id);

        if ($incomeCategory->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'You do not have permission to delete this resource'
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            DB::beginTransaction();
            $incomeCategory->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'message' => 'Income Category Deleted'
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
