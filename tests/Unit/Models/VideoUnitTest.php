<?php

namespace Tests\Unit\Models;

use App\Models\Enums\Rating;
use App\Models\Traits\UploadFiles;
use App\Models\Video;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestClassProperties;
use Tests\Traits\TestClassTraits;

class VideoUnitTest extends TestCase
{
    use TestClassProperties, TestClassTraits;

    public function testUnitVideoFillableProperty()
    {
        $expected = [
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            'video_file',
        ];
        $this->assertPropertyValue('fillable', $expected);
    }

    public function testUnitVideoCastsProperty()
    {
        $expected = [
            'id' => 'string',
            'opened' => 'boolean',
            'year_launched' => 'integer',
            'duration' => 'integer',
            'rating' => Rating::class,
            'deleted_at' => 'datetime'
        ];
        $this->assertPropertyValue('casts', $expected);
    }

    public function testUnitVideoDateProperty()
    {
        $expected = ['deleted_at'];
        $this->assertPropertyValue('dates', $expected);
    }

    public function testUnitVideoImplementedTraits()
    {
        $expected = [
            HasFactory::class,
            SoftDeletes::class,
            Uuid::class,
            UploadFiles::class
        ];

        $this->assertImplementedTraits($expected);
    }

    protected function baseClass()
    {
        return Video::class;
    }
}
