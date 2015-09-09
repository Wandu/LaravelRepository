<?php
namespace Wandu\Laravel\Repository\DataMapper;

use Illuminate\Database\Eloquent\Collection as EloquentColection;
use Illuminate\Database\Eloquent\Model;

interface Mappable
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $class
     * @return \Wandu\Laravel\Repository\DataMapper\DataMapper;
     */
    public function toMapper(Model $class);

    /**
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @return \Wandu\Laravel\Repository\DataMapper\Collection
     */
    public function toMappers(EloquentColection $collection);
}
