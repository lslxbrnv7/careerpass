<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $company
 * @property string $wysiwyg
 * @property string $owner
 * @property Carbon $starts_at
 * @property Carbon $expires_at
 * @property boolean $is_active
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Job extends BaseModel
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_ACTIVE = 'ACTIVE';

    protected $table = 'job';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'company', 'owner', 'starts_at', 'expires_at', 'is_active'];

    protected $casts = [
        'starts_at' => 'date',
        'expires_at' => 'date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
