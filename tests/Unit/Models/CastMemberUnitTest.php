<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestClassProperties;
use Tests\Traits\TestClassTraits;

class CastMemberUnitTest extends TestCase
{
    use TestClassProperties, TestClassTraits;

    public function testUnitCastMemberFillableProperty()
    {
        $expected = [
            'name',
            'type'
        ];
        $this->assertPropertyValue('fillable', $expected);
    }

    public function testUnitCastMemberCastsProperty()
    {
        $expected = [
            'id' => 'string',
            'deleted_at' => 'datetime'
        ];
        $this->assertPropertyValue('casts', $expected);
    }

    public function testUnitCastMemberDateProperty()
    {
        $expected = ['deleted_at'];
        $this->assertPropertyValue('dates', $expected);
    }

    public function testUnitCastMemberImplementedTraits()
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
        return CastMember::class;
    }
}
