<?php

namespace Tests\Traits;

use ReflectionClass;
use ReflectionException;

trait TestClassTraits
{
    abstract protected function baseClass();

    /**
     * @throws ReflectionException
     */
    protected  function assertImplementedTraits(array $expectedTraitNames): void
    {
        $reflection = new ReflectionClass($this->baseClass());
        $actual = $reflection->getTraitNames();

        $this->assertEquals($expectedTraitNames, $actual);
    }
}
