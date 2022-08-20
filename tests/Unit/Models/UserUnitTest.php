<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestClassProperties;
use Tests\Traits\TestClassTraits;

class UserUnitTest extends TestCase
{
    use TestClassProperties, TestClassTraits;

    public function testUnitUserFillableProperty()
    {
        $expected = [
            'name',
            'email',
            'password',
        ];
        $this->assertPropertyValue('fillable', $expected);
    }

    public function testUnitUserCastsProperty()
    {
        $expected = [
            'email_verified_at' => 'datetime',
        ];
        $this->assertPropertyValue('casts', $expected);
    }

    public function testUnitUserHiddenProperty()
    {
        $expected = [
            'password',
            'remember_token',
        ];
        $this->assertPropertyValue('hidden', $expected);
    }

    public function testUnitUserImplementedTraits()
    {
        $expected = [
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class
        ];
        $this->assertImplementedTraits($expected);
    }

    protected function baseClass()
    {
        return User::class;
    }
}
