<?php

use TheJawker\Interrogator\Interrogator;
use Illuminate\Database\Eloquent\Builder;

if (!function_exists('interrogate')) {

    /**
     * Shortcut to the Interrogator.
     *
     * @param Builder $builder The Model builder to interrogate.
     * @return Interrogator
     */
    function interrogate(Builder $builder)
    {
        return new Interrogator($builder);
    }

}