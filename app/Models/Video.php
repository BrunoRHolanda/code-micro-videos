<?php

namespace App\Models;

use App\Models\Enums\Rating;
use App\Models\Traits\UploadFiles;
use App\Models\Traits\Uuid;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Throwable;

class Video extends Model
{
    use HasFactory,
        SoftDeletes,
        Uuid,
        UploadFiles;

    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duration',
        'video_file'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'string',
        'opened' => 'boolean',
        'year_launched' => 'integer',
        'duration' => 'integer',
        'rating' => Rating::class
    ];

    public $incrementing = false;

    public static array $fileFields = [
        'video_file',
    ];

    /**
     * @throws Throwable
     */
    public static function create(array $attributes = []): Model|Builder
    {
        $files = self::extractFiles($attributes);

        try {
            DB::beginTransaction();

            /**
             * @var Video $video
             */
            $video = static::query()->create($attributes);

            $video->categories()->sync($attributes['categories']);
            $video->genres()->sync($attributes['genres']);

            $video->uploadFiles($files);

            DB::commit();

            return $video;
        } catch (Exception $exception) {
            if (isset($video)) {
                //del files
            }

            DB::rollBack();

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        try {
            DB::beginTransaction();

            $saved = parent::update($attributes, $options);

            if ($saved) {
                if (isset($attributes['categories'])) {
                    $this->categories()->sync($attributes['categories']);
                }

                if (isset($attributes['genres'])) {
                    $this->genres()->sync($attributes['genres']);
                }
            }

            DB::commit();

            return $saved;
        } catch (Exception $exception) {

            DB::rollBack();

            throw $exception;
        }
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTrashed();
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class)->withTrashed();
    }

    protected function uploadDir(): string
    {
        return $this->id;
    }
}
