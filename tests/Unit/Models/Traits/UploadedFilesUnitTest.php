<?php

namespace Tests\Unit\Models\Traits;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Stub\Models\UploadFilesStub;
use Tests\TestCase;

class UploadedFilesUnitTest extends TestCase
{
    private UploadFilesStub $instance;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instance = new UploadFilesStub();
    }

    public function testUploadFile()
    {
        $file = UploadedFile::fake()->create('video.mp4');

        $this->instance->uploadFile($file);

        Storage::assertExists("1/{$file->hashName()}");
    }

    public function testUploadFiles()
    {
        $file1 = UploadedFile::fake()->create('video1.mp4');
        $file2 = UploadedFile::fake()->create('video2.mp4');

        $this->instance->uploadFiles([$file1, $file2]);

        Storage::assertExists("1/{$file1->hashName()}");
        Storage::assertExists("1/{$file2->hashName()}");
    }

    public function testDeleteFile()
    {
        $file = UploadedFile::fake()->create('video.mp4');
        $filename = $file->hashName();

        $this->instance->uploadFile($file);

        $this->instance->deleteFile($filename);

        Storage::assertMissing("1/{$filename}");

        $file = UploadedFile::fake()->create('video.mp4');

        $this->instance->uploadFile($file);

        $this->instance->deleteFile($file);

        Storage::assertMissing("1/{$file->hashName()}");
    }

    public function testDeleteFiles()
    {
        $file1 = UploadedFile::fake()->create('video1.mp4');
        $file2 = UploadedFile::fake()->create('video2.mp4');

        $this->instance->uploadFiles([$file1, $file2]);

        $this->instance->deleteFiles([$file1->hashName(), $file2]);

        Storage::assertMissing("1/{$file1->hashName()}");
        Storage::assertMissing("1/{$file2->hashName()}");
    }

    public function testExtractFiles()
    {
        $attributes = [];
        $files = UploadFilesStub::extractFiles($attributes);

        $this->assertCount(0, $attributes);
        $this->assertCount(0, $files);

        $attributes = ['file1' => 'test'];
        $files = UploadFilesStub::extractFiles($attributes);

        $this->assertCount(1, $attributes);
        $this->assertEquals(['file1' => 'test'], $attributes);
        $this->assertCount(0, $files);

        $file1 = UploadedFile::fake()->create('video1.mp4');
        $file2 = UploadedFile::fake()->create('video2.mp4');
        $attributes = [
            'file1' => $file1,
            'file2' => $file2,
        ];
        $files = UploadFilesStub::extractFiles($attributes);

        $this->assertCount(2, $attributes);
        $this->assertEquals([
            'file1' => $file1->hashName(),
            'file2' => $file2->hashName(),
        ], $attributes);
        $this->assertCount(2, $files);
    }
}
