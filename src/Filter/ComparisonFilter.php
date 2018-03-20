<?php

namespace TheJawker\Interrogator\Filter;

class ComparisonFilter
    extends AbstractFilter
{
    private $operators = [
        'ge' => '>=',
        'gt' => '>',
        'le' => '<=',
        'lt' => '<',
    ];

    public function isApplicable(string $expression): bool
    {
        return count($this->match($expression)) === 2;
    }

    public function apply(string $column, string $expression)
    {
        [$operator, $value] = $this->match($expression);

        $this->builder->orWhere($column, $this->operators[$operator], $value);
    }

    /**
     * @param string $expression
     * @return array
     */
    private function match(string $expression): array
    {
        $regexOperators = implode(array_keys($this->operators), '|');

        preg_match("/\[($regexOperators)\](.*)$/", $expression, $matches);

        if ($matches >= 2) {
            return array_slice($matches, 1);
        }

        return [];
    }
}