<?php

namespace TheJawker\Interrogator\Filter;

interface FilterInterface
{
    public function isApplicable(string $expression): bool;

    public function apply(string $column, string $expression);
}