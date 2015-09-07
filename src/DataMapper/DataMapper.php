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
     * @param array $dataSet
     * @return static
     */
    public static function fromArray(array $dataSet)
    {
        $item = new static();
        $item->dataSet = $dataSet;
        return $item;
    }

    /** @var array */
    protected $dataSet;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(EloquentModel $model = null)
    {
        $this->dataSet = isset($model) ? $model->toArray() : [];
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
