<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use TheJawker\Interrogator\Interrogator;
use Illuminate\Database\Eloquent\Builder;

if (!function_exists('interrogate')) {

    /**
     * Shortcut to the Interrogator.
     *
     * @param Builder|Relation|string $builder The Model builder to interrogate.
     * @return Interrogator
     */
    function interrogate($builder)
    {
        if (is_string($builder)) {
            $builder = new $builder;
        }

        if ($builder instanceof Model) {
            $builder = $builder::query();
        }

        if ($builder instanceof Relation) {
            $builder = $builder->getQuery();
        }

        return new Interrogator($builder);
    }

}