<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected static function booted(): void
    {
        static::created(function (Notification $notification): void {
            // Keep only the newest ten notifications for each user.
            $olderIds = static::query()
                ->where('user_id', $notification->user_id)
                ->orderByDesc('id')
                ->skip(10)
                ->take(10000)
                ->pluck('id');

            if ($olderIds->isNotEmpty()) {
                static::query()->whereIn('id', $olderIds)->delete();
            }
        });
    }
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'phone',
        'subject',
        'message',
    ];

}
