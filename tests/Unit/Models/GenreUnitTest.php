<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestClassProperties;
use Tests\Traits\TestClassTraits;

class GenreUnitTest extends TestCase
{
    use TestClassProperties, TestClassTraits;

    public function testUnitGenreFillableProperty()
    {
        $expected = [
            'name',
            'is_active'
        ];
        $this->assertPropertyValue('fillable', $expected);
    }

    public function testUnitGenreCastsProperty()
    {
        $expected = [
            'id' => 'string',
            'deleted_at' => 'datetime'
        ];
        $this->assertPropertyValue('casts', $expected);
    }

    public function testUnitGenreDateProperty()
    {
        $expected = ['deleted_at'];
        $this->assertPropertyValue('dates', $expected);
    }

    public function testUnitGenreImplementedTraits()
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
        return Genre::class;
    }
}
