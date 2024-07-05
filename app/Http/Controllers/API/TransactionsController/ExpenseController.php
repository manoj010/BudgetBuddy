<?php

namespace App\Http\Controllers\API\TransactionsController;

use App\Http\Controllers\Controller;
use App\Models\API\Transactions\Expense;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Expense\{ExpenseCollection, ExpenseResource};
use App\Traits\{AppResponse, UserOwnership};

class ExpenseController extends Controller
{
    use AppResponse, UserOwnership;

    protected $expense;

    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expense = $this->expense->where('user_id', auth()->id())->get();
        return $this->success(new ExpenseCollection($expense), 'All Expenses');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->id();
            $expense = $this->expense::create($validatedData);
            DB::commit();
            return $this->success(new ExpenseResource($expense), 'Expense created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            DB::beginTransaction(); 
            $this->checkOrFindResource($this->expense, $id);
            $specificResource = $this->expense->where('user_id', auth()->id())->find($id);
            DB::commit(); 
            return $this -> success(new ExpenseResource($specificResource));
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $this->checkOwnership($expense);
        try {
            DB::beginTransaction();
            $expense->update($request->validated());
            DB::commit();
            return $this -> success(new ExpenseResource($expense), 'Expense updated Successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> error($e);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $this->checkOwnership($expense);
        try {
            DB::beginTransaction();
            $expense->delete();
            DB::commit();
            return $this->success('Expense deleted Successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> error($e);
        }
    }
}
