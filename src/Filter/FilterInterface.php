<?php

namespace TheJawker\Interrogator\Filter;

interface FilterInterface
{
    /**
     * Tests if the Filter is Applicable.
     *
     * @param string $expression
     * @return bool
     */
    public function isApplicable(string $expression): bool;

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression);
}