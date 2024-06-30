<?php

namespace App\Http\Controllers\API\TransactionsController;

use App\Http\Controllers\Controller;
use App\Models\API\Transactions\Income;
use App\Http\Requests\IncomeRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Income\{IncomeCollection, IncomeResource};
use App\Traits\{AppResponse, UserOwnership};

class IncomeController extends Controller
{
    use AppResponse, UserOwnership;

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
        return $this->success(new IncomeCollection($income), 'All Income');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->id();
            $income = $this->income::create($validatedData);
            DB::commit();
            return $this->success(new IncomeResource($income), 'Income created successfully', Response::HTTP_CREATED);
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
            $this->checkOrFindResource($this->income, $id);
            $specificResource = $this->income->where('user_id', auth()->id())->find($id);
            DB::commit(); 
            return $this -> success(new IncomeResource($specificResource));
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncomeRequest $request, Income $income)
    {
        $this->checkOwnership($income);
        try {
            DB::beginTransaction();
            $income->update($request->validated());
            DB::commit();
            return $this -> success(new IncomeResource($income), 'Income updated Successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> error($e);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        $this->checkOwnership($income);
        try {
            DB::beginTransaction();
            $income->delete();
            DB::commit();
            return $this->success('Income deleted Successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> error($e);
        }
    }
}
