<?php
namespace Wandu\Laravel\Repository\Stubs\DataMapper;

use Wandu\Laravel\Repository\DataMapper\DataMapper;

class Article extends DataMapper
{
    protected static $uncached = ['categories'];
}
