<?php

namespace Domain\Shared\Builders\Base;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseBuilder extends Builder
{
    const EQUALS = 'equals';

    const LIKE = 'like';

    public function valid()
    {
        return $this;
    }

    public function searchableByAdmin()
    {
        return $this;
    }

    public function listableByAdmin()
    {
        return $this->searchableByAdmin();
    }

    public function searchableByCustomer()
    {
        return $this;
    }

    public function listableByCustomer()
    {
        return $this->searchableByCustomer();
    }

    public function whereAssociatedPropertyIs($modelLcfirstCamelcaseName, $propertyName, $equalsOrLike, $value)
    {
        return $this->whereHas($modelLcfirstCamelcaseName, function ($query) use ($propertyName, $equalsOrLike, $value) {
            if ($equalsOrLike == self::EQUALS) {
                $query->where($propertyName, $value);
            } elseif ($equalsOrLike == self::LIKE) {
                $query->where($propertyName, 'like', '%'.$value.'%');
            }
        });
    }

    public function orderByBindingProperty($modelLcfirstCamelcaseName, $propertyName, $direction)
    {
        return $this->whereHas($modelLcfirstCamelcaseName, function ($query) use ($propertyName, $direction) {
            $query->orderBy($propertyName, $direction);
        });
    }
}
