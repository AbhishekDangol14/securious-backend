<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface {
    protected Model $model;

    public function all() {
        return $this->model->get();
    }

    public function fillables() {
        return $this->model->getFillable();
    }

    public function paginate(int $pages = 15, array $selectedColumns = [], $relations = [], $conditions = [], $orderBy = null ) {
        $model = $this->model;
        if ($selectedColumns !== []) {
            $model = $model->select($selectedColumns);
        }

        if ($relations) {
            $model = $model->with($relations);
        }

        if ($conditions){
           foreach ($conditions as $column=>$value) {
               $model= $model->where($column, $value);
           }
        }

        if ($orderBy) {
            $model = $model->orderBy($orderBy,'desc');
        }
        return $model->paginate($pages);
    }

    public function find($id) {
        return $this->model->where('id',$id )->first();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data): mixed
    {
        return $this->model->create($data);
    }

    public function delete(int $id, bool $force = false): void
    {
        $model = $this->find($id);

        if (!$model) {
            return;
        }

        if ($force) {
            $model->forceDelete();
            return;
        }

        $model->delete();
    }

    public function update(array $data, $id)
    {

        return $this->find($id);
    }

    public function toggleStatus($id,$status){
        $model = $this->find($id);
        $model->update(['is_active' => $status]);
    }

    public function getModel() {
        return $this->model;
    }

    /**
     * @param $trait
     * @return bool
     */
    public function hasFeature($trait): bool
    {
        $x = class_uses($this->model)[$trait] ?? false;
        return (bool) $x;
    }

//    public function storeWithRelation($model, $relation, $items){
//       $model->with($relation)->saveMany($items);
//    }
}
