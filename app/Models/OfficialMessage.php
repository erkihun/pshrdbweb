<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

final class OfficialMessage extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'title',
        'message',
        'photo_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function newUniqueId(): string
    {
        return (string) Str::uuid7();
    }
}
