<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailToken extends Model
{
    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'used',
        'used_at',
        'user_agent',
        'ip_address'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'used' => 'boolean'
    ];

    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    public function markAsUsed(string $userAgent = null, string $ipAddress = null): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress
        ]);
    }

    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public static function cleanup(): int
    {
        return static::where('expires_at', '<', now())
            ->orWhere('used', true)
            ->delete();
    }
};
