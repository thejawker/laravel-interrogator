<?php

namespace TheJawker\Interrogator\Filter;

class ComparisonFilter
    extends AbstractFilter
{
    /**
     * The allowed Operators and their Eloquent equivalents.
     *
     * @var string[]
     */
    private $operators = [
        'ge' => '>=',
        'gt' => '>',
        'le' => '<=',
        'lt' => '<',
    ];

    /**
     * Tests if the Comparison Filter is Applicable.
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return count($this->match($this->expression)) === 2;
    }

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression)
    {
        [$operator, $value] = $this->match($expression);

        $this->where($column, $this->operators[$operator], $value);
    }

    /**
     * Matches the operators.
     *
     * @param string $expression
     * @return array
     */
    private function match(string $expression): array
    {
        $regexOperators = implode('|', array_keys($this->operators));

        preg_match("/\[($regexOperators)\](.*)$/", $expression, $matches);

        if ($matches >= 2) {
            return array_slice($matches, 1);
        }

        return [];
    }
}