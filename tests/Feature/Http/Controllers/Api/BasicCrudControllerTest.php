<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\Stub\Http\Controllers\CategoryControllerStub;
use Tests\Stub\Models\CategoryStub;
use Tests\TestCase;

class BasicCrudControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        CategoryStub::createTable();

        $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown();
    }

    public function testIndex()
    {
        $category = CategoryStub::create(['name' => 'test', 'description' => 'test']);
        $controller = new CategoryControllerStub();

        $this->assertEquals([$category->toArray()], $controller->index()->toArray());
    }

    public function testInvalidationDataStore()
    {
        $this->expectException(ValidationException::class);

        $request = \Mockery::mock(Request::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn(['name' => '']);

        $this->controller->store($request);
    }
}
