<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmploymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmployeeProfile extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'employment_status' => EmploymentStatus::class,
            'birth_date' => 'date',
            'contract_start' => 'date',
            'retired_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
        $this->addMediaCollection('documents');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user_id', 'national_id', 'job_title', 'employment_status',
                'employment_link', 'contract_start', 'retired_at',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
