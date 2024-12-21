<?php

namespace Modules\Corporative\Models;

use App\Models\SystemFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Corporative\Database\Factories\CorporativeFactory;
use Spatie\Translatable\HasTranslations;

class Corporative extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public $translatable = ['banner_title', 'banner_description', 'content_title', 'content_top_text', 'content_text', 'corporative_trainings'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $maxOrder = self::max('order');
            $model->order = $maxOrder !== null ? $maxOrder + 1 : 1;
        });
    }

    public function images()
    {
        return $this->hasMany(SystemFiles::class, 'relation_id')->where('model_type', 'corporative')->where('file_type', 'image');
    }

    public function image()
    {
        return $this->hasOne(SystemFiles::class, 'relation_id')->where('model_type', 'corporative')->where('file_type', 'image')->where('type', 'image')->select('url', 'relation_id');
    }
    public function banner()
    {
        return $this->hasOne(SystemFiles::class, 'relation_id')->where('model_type', 'corporative')->where('file_type', 'image')->where('type', 'banner')->select('url', 'relation_id');
    }


    protected static function newFactory(): CorporativeFactory
    {
        //return CorporativeFactory::new();
    }
}
