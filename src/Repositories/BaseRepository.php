<?php

namespace Waad\RepoMedia\Repositories;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{

    protected $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    //get model proparties dynamic from Database
    public function getProperties()
    {
        // $table = $this->model->getTable();
        // return DB::getSchemaBuilder()->getColumnListing($table) ;
        $getReflactionModel = new \ReflectionClass($this->model);
        $getProperties = $getReflactionModel->getProperty('fillable');
        $getProperties->setAccessible(true);
        return $getProperties->getValue(new $this->model);
    }
    //get relational model dynamic
    function getRelationMethod(){
        $getReflactionModel = new \ReflectionClass($this->model);
        $getRelation = $getReflactionModel->getProperty('relations');
        $getRelation->setAccessible(true);
        return $getRelation->getValue(new $this->model);
    }
    //Base repo to get all items
    public function index($take = null, $find = null, $where = [], $trash = null, $whereHas = []){
        $result = QueryBuilder::for($this->model)
                                ->allowedIncludes($this->getRelationMethod())
                                ->allowedFilters($this->getProperties())
                                ->allowedSorts($this->getProperties());
        if($find) {
            $result = $result->where($find['column'], $find['condition'], $find['value']);
        }
        if($where) {
            foreach($where as $condition) {
                $result = $result->where($condition['column'], $condition['condition'], $condition['value']);
            }
        }
        if($trash) {
            if($trash == 'all'){
                $result = $result->withTrashed();
            }else if($trash == 'trash'){
                $result = $result->onlyTrashed();
            }
        }

        if($whereHas){
            foreach($whereHas as $has) {
                $result = $result->whereHas($has['model'], function($query) use($has){
                    $query->where($has['column'],$has['condition'], $has['value']);
                });
            }
        }

        return $take == null ? $result->get() : $result->paginate($take);
    }
    //Base repo to get item by id
    public function show($id){
        return QueryBuilder::for($this->model)
                            ->allowedIncludes($this->getRelationMethod())
                            ->findOrFail($id);
    }
    //Base repo to create item
    public function create($data){
        return $this->model->create($data);
    }
    //Base repo to update item
    public function update($id, $values){
        $item = $this->model->findOrFail($id);
        return $item->update($values);
    }
    //base repo to delete item
    public function delete($id)
    {
        $object = $this->show($id);
        $object->media()->delete();
        return $object->delete();
    }
    //base repo to destory item
    public function destroy($id)
    {
        $object = $this->model->withTrashed()->findOrFail($id);
        $object->destroyMediaArray($object->media->pluck('id')->toArray());
        $object->media()->forceDelete();
        return $object->forceDelete();
    }
    //base repo to restore item
    public function restore($id)
    {
        $object = $this->model->onlyTrashed()->findOrFail($id);
        $object->media()->restore();
        return $object->restore();
    }
}
