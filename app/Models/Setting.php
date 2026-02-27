<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function getValue(string $key, ?string $default = null): ?string
    {
        if (!Schema::hasTable('settings')) {
            return $default;
        }

        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function setValue(string $key, ?string $value): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
