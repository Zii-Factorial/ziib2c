<?php

namespace App\Criteria;

use Illuminate\Database\Query\Builder;
use Prettus\Repository\Contracts\Criteria;
use Prettus\Repository\Contracts\Repository;

class LatestSortCriteria implements Criteria
{
    /**
     * @param  Builder  $model
     */
    public function apply($model, Repository $repository)
    {
        return $model->orderByDesc('created_at');
    }
}
