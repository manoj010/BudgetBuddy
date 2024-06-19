<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Requests\BaseCategoryRequest;
use App\Models\API\Category\IncomeCategory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

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
        $validatedData = $request->validated();
        return $this->updateResource($validatedData, $resource);
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
                'message' => 'Income Category successfully Deleted'
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
