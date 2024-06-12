<?php

namespace App\Http\Controllers\API\TransactionsController;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeRequest;
use App\Http\Resources\Income\IncomeCollection;
use App\Http\Resources\Income\IncomeResource;
use App\Models\API\Transactions\Income;
use App\Traits\UserOwnership;
use App\Services\UserService;
use App\Traits\ResourceNotFound;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IncomeController extends Controller
{
    use UserOwnership, ResourceNotFound;

    protected $income, $userService;

    public function __construct(Income $income, UserService $userService)
    {
        $this->income = $income;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = $this->userService->getUserId();

        if ($response = $this->checkResource($this->income, $userId)) {
            return $response;
        }

        $income = $this->income->where('user_id', $userId)->get();
        $totalIncome = $income->sum('amount');

        return response()->json([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'data' => new IncomeCollection($income),
            'total_income' => $totalIncome
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request, Authenticatable $user)
    {
        try {
            DB::beginTransaction();
            $income = $this->income->create([
                'user_id' => $user->id,
                'source' => $request->source,
                'amount' => $request->amount,
                'category_id' => $request->category_id,
                'date_received' => $request->date_received,
                'notes' => $request->notes
            ]);
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
