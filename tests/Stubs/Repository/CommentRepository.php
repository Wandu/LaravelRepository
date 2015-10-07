<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Wandu\Laravel\Repository\MoreItemsRepositoryInterface;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\Model\Comment;
use Wandu\Laravel\Repository\Traits\MoreItemsRepositoryTrait;

class CommentRepository extends Repository implements MoreItemsRepositoryInterface
{
    use MoreItemsRepositoryTrait;

    /** @var string */
    protected $model = Comment::class;

    protected $orders = [
        'ancestor_vote' => false,
        'ancestor_order' => false,
        'order' => true,
    ];
}
