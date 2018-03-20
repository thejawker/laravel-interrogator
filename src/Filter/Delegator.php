<?php

namespace TheJawker\Interrogator\Filter;

class Delegator
{
    /**
     * @var FilterInterface[]
     */
    private $handlers = [];

    /**
     * Delegator constructor.
     *
     * @param FilterInterface[] $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public static function make(array $handlers)
    {
        return new self($handlers);
    }

    public function execute(string $column, string $value)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->isApplicable($value)) {
                $handler->apply($column, $value);
                break;
            }
        }
    }
}