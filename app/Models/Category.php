<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
      use InteractsWithMedia;
     protected $fillable = [
        'name',
        'description',
    ];

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
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
