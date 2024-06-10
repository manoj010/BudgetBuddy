<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequests\IncomeCategoryRequest;
use App\Http\Resources\Category\IncomeCategory\IncomeCategoryCollection;
use App\Http\Resources\Category\IncomeCategory\IncomeCategoryResource;
use App\Models\API\CategoryModels\IncomeCategory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IncomeCategoryController extends Controller
{
    protected $income_cat;

    public function __construct(IncomeCategory $income_cat)
    {
        $this -> income_cat = $income_cat;
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
            'income_categories' => new IncomeCategoryCollection($incomeCategories)
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
                'income_category' => $income_cat
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
        $incomeCategory = $this->income_cat->find($categoryId);

        if (is_null($incomeCategory)) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Income category not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'income_category' => new IncomeCategoryResource($incomeCategory)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
