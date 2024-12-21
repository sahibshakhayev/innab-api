<?php

namespace Modules\VideoLessonsTitle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\VideoLessonsTitle\Database\Factories\VideoLessonsTitleFactory;
use Spatie\Translatable\HasTranslations;
use Modules\Lesson\Models\Lesson;
class VideoLessonsTitle extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    public $translatable = ['title', 'slug'];

    /**
     * The attributes that are mass assignable.
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $maxOrder = self::max('order');
            $model->order = $maxOrder !== null ? $maxOrder + 1 : 1;
        });
    }

    /**
     * The attributes that are mass assignable.
     */

    public function links() {
       return $this->hasMany(Lesson::class, 'title_id','id');
    }

    protected static function newFactory(): VideoLessonsTitleFactory
    {
        //return VideoLessonsTitleFactory::new();
    }
}
