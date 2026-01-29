<?php

namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    protected array $keys = [
        'category_id',
        'search',
    ];
    protected function categoryId(Builder $builder, $value)
    {
        $builder->where('category_id', $value);
    }
    protected function search(Builder $builder, $value)
    {
        $builder->where(function ($b) use ($value) {
            $b->where('name', 'ilike', '%' . $value . '%');
            $b->orWhere('description', 'ilike', '%' . $value . '%');
        });
    }
}
