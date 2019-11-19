<?php

namespace TheJawker\Interrogator\Filter;

use Illuminate\Support\Str;

class ListFilter
    extends AbstractFilter
{
    /**
     * Tests if the List Filter is Applicable.
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return Str::contains($this->expression, ',') && !Str::contains($this->expression, '\,');
    }

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression)
    {
        $values = explode(',', $expression);
        $this->whereIn($column, $values);
    }
}