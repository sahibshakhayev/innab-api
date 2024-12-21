<?php

namespace Modules\News\Models;

use App\Models\SystemFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\News\Database\Factories\NewsFactory;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public $translatable = ['title', 'slug', 'page_title', 'text','short_description', 'seo_title', 'seo_keywords', 'seo_description'];

    protected static function boot()
    {
        parent::boot();

       static::creating(function ($model) {
            $maxOrder = self::max('order');
            $maxPinnedOrder = self::max('pinned_order');
            $model->order = $maxOrder !== null ? $maxOrder + 1 : 1;
            $model->pinned_order = $maxPinnedOrder !== null ? $maxPinnedOrder + 1 : 1;
        });

    }


    public function images()
    {
        return $this->hasMany(SystemFiles::class, 'relation_id')->where('model_type', 'news')->where('file_type', 'image');
    }

    public function image()
    {
        return $this->hasOne(SystemFiles::class, 'relation_id')->where('model_type', 'news')->where('file_type', 'image');
    }

    protected static function newFactory(): NewsFactory
    {
        //return NewsFactory::new();
    }
}
