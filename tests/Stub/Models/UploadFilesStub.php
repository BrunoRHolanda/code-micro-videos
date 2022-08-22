<?php

namespace Tests\Stub\Models;

use App\Models\Traits\UploadFiles;
use Illuminate\Database\Eloquent\Model;

class UploadFilesStub extends Model
{
    use UploadFiles;

    public static array $fileFields = [
         'file1',
         'file2',
    ];

    protected function uploadDir(): string
    {
        return "1";
    }
}
