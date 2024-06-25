<?php

namespace App\Http\Controllers\API\CategoryController;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\{BaseCategoryCollection, BaseCategoryResource};
use App\Traits\{AppErrorResponse, AppResponse, UserOwnership};
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseCategoryController extends Controller
{    
    use AppResponse, UserOwnership, AppErrorResponse;

    protected function allResource(Model $resource)
    {
        $userId = auth()->id();
        $this->checkOrFindResource($resource);
        $allResource = $resource->where('user_id', $userId)->get();
        return $this->successResponse(new BaseCategoryCollection($allResource));
    }

    protected function createResource(array $validatedData, Model $resource)
    {
        try {
            DB::beginTransaction();
            $validatedData['user_id'] = auth()->id();
            $createdResource = $resource->create($validatedData);
            DB::commit();
            return $this->createdResponse(new BaseCategoryResource($createdResource));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse($e);
        }
    }

    protected function specificResource(Model $resource, $id)
    {
        try {
            DB::beginTransaction(); 
            $this->checkOrFindResource($resource, $id);
            $specificResource = $resource->where('user_id', auth()->id())->find($id);
            DB::commit();
            return $this -> successResponse(new BaseCategoryResource($specificResource));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }

    protected function updateResource(array $validatedData, Model $resource)
    {
        try {
            DB::beginTransaction();
            $this->checkOwnership($resource);
            $resource->update($validatedData);
            $updatedResource = $resource->fresh();
            DB::commit();
            return $this -> successResponse(new BaseCategoryResource($updatedResource));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }

    protected function deleteResource(Model $resource) 
    {
        try {
            DB::beginTransaction();
            $this -> checkDelete($resource);
            $resource->delete();
            DB::commit();
            return $this -> deleteResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }
}
