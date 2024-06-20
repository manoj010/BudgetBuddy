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
        // $data = new BaseCategoryCollection($allResource);
        return $this->successResponse($allResource);
    }

    protected function createResource(array $validatedData, Model $resource, Authenticatable $user)
    {
        try {
            DB::beginTransaction();
            $validatedData['user_id'] = $user->id;
            $createdResource = $resource->create($validatedData);
            DB::commit();
            $data = new BaseCategoryResource($createdResource);
            return $this->createdResponse($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse($e);
        }
    }

    protected function specificResource(Model $resource, Authenticatable $user, $id)
    {
        $this->checkOrFindResource($resource, $id);
        $specificResource = $resource->where('user_id', $user->id)->find($id);
        try {
            DB::commit();
            return $this -> successResponse($specificResource);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }

    protected function updateResource(array $validatedData, Model $resource)
    {
        $this->checkOwnership($resource);
        try {
            DB::beginTransaction();
            $resource->update($validatedData);
            $updatedResource = $resource->fresh();
            DB::commit();
            $data = new BaseCategoryResource($updatedResource);
            return $this -> successResponse($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }

    protected function deleteResource(Model $resource) 
    {
        $this -> checkDelete($resource);
        try {
            DB::beginTransaction();
            $resource->delete();
            DB::commit();
            return $this -> deleteResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> serverErrorResponse($e);
        }
    }
}
