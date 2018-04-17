<?php

namespace TheJawker\Interrogator\Filter;

interface FilterInterface
{
    /**
     * Tests if the Filter is Applicable.
     *
     * @return bool
     */
    public function isApplicable(): bool;

    /**
     * Prepares the expression.
     *
     * @param string $expression
     */
    public function prepareExpression(string $expression);

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression);
}