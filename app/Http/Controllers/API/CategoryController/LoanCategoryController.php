<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseCategoryRequest;
use App\Http\Resources\Category\BaseCategoryCollection;
use App\Http\Resources\Category\BaseCategoryResource;
use App\Models\API\Category\LoanCategory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LoanCategoryController extends Controller
{
    protected $loan_cat;

    public function __construct(LoanCategory $loan_cat)
    {
        $this->loan_cat = $loan_cat;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $loanCategories = $this->loan_cat->where('user_id', $user->id)->get();

        if ($loanCategories->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'No loan categories found for the authenticated user'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'data' => new BaseCategoryCollection($loanCategories)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseCategoryRequest $request, Authenticatable $user)
    {
        try {
            DB::beginTransaction();
            $loan_cat = $this->loan_cat->create([
                'user_id' => $user->id,
                'name' => $request->name,
                'description' => $request->description,
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_CREATED,
                'data' => new BaseCategoryResource($loan_cat)
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

        $loanCategory = $this->loan_cat->where('user_id', $user->id)->find($categoryId);

        if (is_null($loanCategory)) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Saving category not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => new BaseCategoryResource($loanCategory)
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
    public function update(BaseCategoryRequest $request, LoanCategory $loanCategory)
    {
        $user = auth()->user();

        // dd($user->id, $loanCategory->user_id);

        if ($loanCategory->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'You do not have permission to update this resource'
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            DB::beginTransaction();
            $loanCategory->update($request->validated());
            DB::commit();
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => new BaseCategoryResource($loanCategory),
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
    public function destroy(LoanCategory $loanCategory)
    {
        $user = auth()->user();

        // dd($user->id, $loanCategory->user_id);

        if ($loanCategory->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'You do not have permission to delete this resource'
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            DB::beginTransaction();
            $loanCategory->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'message' => 'Saving Category successfully Deleted'
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
