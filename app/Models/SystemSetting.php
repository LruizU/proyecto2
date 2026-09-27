<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function integer(string $key, int $default): int
    {
        $value = static::query()->where('key', $key)->value('value');

        return is_numeric($value) ? max(0, (int) $value) : $default;
    }
}
