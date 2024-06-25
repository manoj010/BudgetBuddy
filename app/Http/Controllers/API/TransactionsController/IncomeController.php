<?php

namespace App\Http\Controllers\API\TransactionsController;

use App\Http\Controllers\Controller;
use App\Models\API\Transactions\Income;
use App\Http\Requests\IncomeRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Income\{IncomeCollection, IncomeResource};
use App\Traits\{AppErrorResponse, AppResponse, UserOwnership};

class IncomeController extends Controller
{
    use AppResponse, UserOwnership, AppErrorResponse;

    protected $income;

    public function __construct(Income $income)
    {
        $this->income = $income;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $income = $this->income->where('user_id', auth()->id())->get();
        $totalIncome = $income->sum('amount');
        return $this->successResponse(new IncomeCollection($income), 'All Income');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $income = $this->income::create($validatedData);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_CREATED,
                'data' => new IncomeResource($income)
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
    public function show($incomeId = null)
    {
        $user = auth()->user();

        $income = $this->income->where('user_id', $user->id)->find($incomeId);

        if (is_null($income)) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Income not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => new IncomeResource($income)
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
    public function update(IncomeRequest $request, Income $income)
    {
        if ($response = $this->checkOwnership($income)) {
            return $response;
        }

        try {
            DB::beginTransaction();
            $income->update($request->validated());
            DB::commit();
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => new IncomeResource($income),
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
    public function destroy(string $id)
    {
        //
    }
}
