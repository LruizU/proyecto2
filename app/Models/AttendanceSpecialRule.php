<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSpecialRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'minutes',
        'starts_at',
        'ends_at',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'minutes' => 'integer',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appliesTo(string $date): bool
    {
        return (! $this->starts_at || $this->starts_at->toDateString() <= $date)
            && (! $this->ends_at || $this->ends_at->toDateString() >= $date);
    }

    public static function minutesFor(?int $employeeId, string $date): int
    {
        if (! $employeeId) {
            return 0;
        }

        return (int) (static::query()
            ->where('employee_id', $employeeId)
            ->where(function ($query) use ($date): void {
                $query->whereNull('starts_at')->orWhereDate('starts_at', '<=', $date);
            })
            ->where(function ($query) use ($date): void {
                $query->whereNull('ends_at')->orWhereDate('ends_at', '>=', $date);
            })
            ->latest('id')
            ->value('minutes') ?? 0);
    }
}
