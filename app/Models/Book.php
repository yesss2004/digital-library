<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'author',
        'publisher',
        'year',
        'pages',
        'category',
        'description',
        'reader_url',
        'pdf_file',
        'cover_image',
        'created_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'pages' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function toCatalogArray(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'cover_title' => $this->title,
            'cover_author' => $this->author,
            'cover' => 'linear-gradient(135deg, #16445d, #39a2a3)',
            'cover_image' => $this->cover_image
                ? Storage::disk('public')->url($this->cover_image)
                : null,
            'category' => $this->category,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'year' => $this->year,
            'pages' => $this->pages,
            'description' => $this->description,
            'reader_url' => $this->reader_url,
            'pdf_file' => $this->pdf_file,
            'pdf_url' => $this->pdf_file ? route('books.download', $this->slug) : null,
        ];
    }

    public function hasPdf(): bool
    {
        return filled($this->pdf_file);
    }
}
