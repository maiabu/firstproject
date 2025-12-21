<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Hotel extends Model implements HasMedia
{
    use InteractsWithMedia;
        protected $fillable = [
        'name',
        'city',
        'description',
        'address',
        'category_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

      public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile(); 
    }

     public function getCoverUrlAttribute(): ?string{
    $cover = $this->getFirstMedia('cover');
    return $cover ? $cover->getUrl() : null;
}
}
