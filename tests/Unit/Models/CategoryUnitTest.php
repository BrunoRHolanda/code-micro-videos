<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestClassProperties;
use Tests\Traits\TestClassTraits;

class CategoryUnitTest extends TestCase
{
    use TestClassProperties, TestClassTraits;

    public function testUnitCategoryFillableProperty()
    {
        $expected = [
            'name',
            'description',
            'is_active'
        ];
        $this->assertPropertyValue('fillable', $expected);
    }

    public function testUnitCategoryCastsProperty()
    {
        $expected = [
            'id' => 'string',
            'deleted_at' => 'datetime'
        ];
        $this->assertPropertyValue('casts', $expected);
    }

    public function testUnitCategoryDateProperty()
    {
        $expected = ['deleted_at'];
        $this->assertPropertyValue('dates', $expected);
    }

    public function testUnitCategoryImplementedTraits()
    {
        $expected = [
            HasFactory::class,
            SoftDeletes::class,
            Uuid::class
        ];
        $this->assertImplementedTraits($expected);
    }

    protected function baseClass()
    {
        return Category::class;
    }
}
