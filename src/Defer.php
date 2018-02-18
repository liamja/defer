<?php

namespace Liamja\Defer;

class Defer
{
    protected $deferrables = array();

    public function __invoke(callable $deferrable)
    {
        $this->push($deferrable);
    }

    public function push(callable $deferrable)
    {
        $this->deferrables[] = $deferrable;
    }

    public function __destruct()
    {
        $this->callDeferrables();
    }

    public function callDeferrables()
    {
        $deferrables = array_reverse($this->deferrables);

        foreach ($deferrables as $deferrable) {
            $deferrable();
        }

        $this->deferrables = array();
    }
}
