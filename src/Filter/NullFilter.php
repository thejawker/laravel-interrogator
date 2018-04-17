<?php

namespace TheJawker\Interrogator\Filter;

class NullFilter
    extends AbstractFilter
{
    /**
     * Tests if the List Filter is Applicable.
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return count($this->match($this->expression)) === 1;
    }

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression)
    {
        [$expression] = $this->match($expression);
        $not = $expression === '!null';
        $this->builder->whereNull($column, $this->boolean, $not);
    }

    private function match(string $expression): array
    {
        preg_match("/\[(null|!null)\]/", $expression, $matches);

        if ($matches >= 2) {
            return array_slice($matches, 1);
        }

        return [];
    }
}