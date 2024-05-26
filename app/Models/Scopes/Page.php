<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Page implements Scope
{
    protected int $take = 20;
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): Builder
    {
        if (request()->has('take')) {
            $this->take = (int) request()->input('take');
        }
        if (request()->has('page')) {
            $page = (int) request()->input('page') - 1;
            $builder->skip($this->take * $page)->take($this->take);
        } else {
            $builder->take($this->take);
        }
        return $builder;
    }
}
