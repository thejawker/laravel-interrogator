<?php

namespace TheJawker\Interrogator\Filter;

class Delegator
{
    /**
     * Holds a list of available Filters
     *
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * Delegator constructor.
     *
     * @param FilterInterface[] $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Makes a Delegator.
     *
     * @param FilterInterface[] $filters
     * @return Delegator
     */
    public static function make(array $filters)
    {
        return new self($filters);
    }

    /**
     * Iterates over the Filters and runs the first applicable Filter.
     *
     * @param string $column
     * @param string $value
     */
    public function execute(string $column, string $value)
    {
        foreach ($this->filters as $filter) {
            if ($filter->isApplicable($value)) {
                $filter->apply($column, $value);
                break;
            }
        }
    }
}