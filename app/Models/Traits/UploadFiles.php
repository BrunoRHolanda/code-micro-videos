<?php

namespace App\Models\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadFiles
{
    protected abstract function uploadDir(): string;

    /**
     * @param UploadedFile[] $files
     * @return void
     */
    public function uploadFiles(array $files): void
    {
        foreach ($files as $file) {
            $this->uploadFile($file);
        }
    }

    public function uploadFile(UploadedFile $file): void
    {
        $file->store($this->uploadDir());
    }

    public function deleteFiles(array $files)
    {
        foreach ($files as $file) {
            $this->deleteFile($file);
        }
    }

    public function deleteFile(string|UploadedFile $file)
    {
        $filename = $file instanceof UploadedFile ? $file->hashName() : $file;

        Storage::delete("{$this->uploadDir()}/$filename");
    }

    /**
     * @param array $attributes
     * @return UploadedFile[]
     */
    public static function extractFiles(array &$attributes = []): array
    {
            $files = [];

            foreach (self::$fileFields as $file) {
                if (isset($attributes[$file]) && $attributes[$file] instanceof UploadedFile) {
                    $files[] = $attributes[$file];
                    $attributes[$file] = $attributes[$file]->hashName();
                }
            }

            return $files;
    }
}
