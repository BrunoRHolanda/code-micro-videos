<?php

namespace Tests\Traits;

use ReflectionClass;

trait TestClassProperties
{
    abstract protected function baseClass();

    protected function assertPropertyValue(
        string $propertyName,
        array $expected
    ) {
        $reflection = new ReflectionClass($this->baseClass());
        $actual = $reflection->getProperty($propertyName)->getValue(new ($this->baseClass()));

        $this->assertEquals($expected, $actual);
    }
}
