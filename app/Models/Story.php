<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 'draft';       // Bản nháp
    const STATUS_PENDING = 'pending';   // Đang chờ duyệt
    const STATUS_PUBLISHED = 'published'; // Đã xuất bản
    const STATUS_REJECTED = 'rejected';  // Đã từ chối

    protected $fillable = [
        'name',
        'summary',  // Make sure this matches your database column
        'content',
        'author_id',
        'age_rating',
        'language',
        'status',
        'cover_image',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function readingHistories()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function hashtags()
    {
        return $this->hasMany(Hashtag::class);
    }

    // Phương thức helper để lấy tên tiếng Việt của trạng thái
    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case self::STATUS_DRAFT:
                return 'Bản nháp';
            case self::STATUS_PENDING:
                return 'Đang chờ duyệt';
            case self::STATUS_PUBLISHED:
                return 'Đã xuất bản';
            case self::STATUS_REJECTED:
                return 'Đã từ chối';
            default:
                return 'Không xác định';
        }
    }
}