<?php
namespace Wandu\Laravel\Repository\DataMapper;

use ArrayAccess;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class DataMapper implements ArrayAccess
{
    /** @var array */
    protected static $uncached = [];

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(EloquentModel $model)
    {
        $this->dataSet = $model->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->dataSet;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->dataSet[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return isset($this->dataSet[$offset]) ? $this->dataSet[$offset] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset) || $offset === '') {
            throw new InvalidArgumentException('offset must be a string.');
        }
        $this->dataSet[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->dataSet[$offset]);
    }
}
