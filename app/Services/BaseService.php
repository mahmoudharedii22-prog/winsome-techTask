<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected Model $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index(): Collection
    {
        return $this->model::all();
    }

    public function store(array $data)
    {
        return $this->model::create($data);
    }

    public function update($model_object, array $data): Model
    {

        $model_object->update($data);

        return $model_object;
    }

    public function destroy(Model $model_object): bool
    {
        return $model_object->delete();
    }

    public function show(Model $model_object): Model
    {
        return $model_object;
    }

    public function forceDelete(int $model_object_id): bool
    {
        $model_object = $this->model::withTrashed()->findOrFail($model_object_id);

        return $model_object->forceDelete();
    }

    public function restore(int $model_object_id): Model
    {
        try {
            $this->model::withTrashed()->findOrFail($model_object_id)->restore();
            $model_object = $this->model::findOrFail($model_object_id);
        } catch (\Exception $e) {
           abort(404, 'Model not found');
        }

        return $model_object;
    }

    public function showDeleted(): Collection
    {
        return $this->model::onlyTrashed()->get();
    }
}
