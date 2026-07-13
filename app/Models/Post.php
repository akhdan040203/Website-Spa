<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title','slug','excerpt','content','featured_image','image_alt','status','published_at','meta_title','meta_description','focus_keyword','canonical_url','robots_index','robots_follow','og_title','og_description','og_image'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'robots_index' => 'boolean', 'robots_follow' => 'boolean'];
    }

    public function author(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string { return 'slug'; }

    public function publicUrl(): string
    {
        return route('posts.show', $this);
    }

    public function seoTitle(): string
    {
        return $this->meta_title ?: $this->title.' | Ungu Spa';
    }

    public function seoDescription(): string
    {
        return $this->meta_description
            ?: $this->excerpt
            ?: Str::limit(trim(strip_tags($this->content)), 155);
    }

    public function publicImage(): string
    {
        if ($this->og_image) return Str::startsWith($this->og_image, ['http://', 'https://', 'assets/']) ? asset($this->og_image) : asset('storage/'.$this->og_image);
        if ($this->featured_image) return Str::startsWith($this->featured_image, ['http://', 'https://', 'assets/']) ? asset($this->featured_image) : asset('storage/'.$this->featured_image);
        return asset('assets/ganbar/heroo.webp');
    }
}
