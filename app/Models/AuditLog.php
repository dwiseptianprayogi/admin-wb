<?php


namespace App\Models;

use App\Traits\DynamicAttributeMapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditLog extends Model
{
    use DynamicAttributeMapper;
    protected $table = 'Ice.UD04';
    protected $primaryKey = 'Key1';
    public $incrementing = false;
    protected $fillable = [
        'description',
        'subject_uuid',
        'subject_type',
        'user_uuid',
        'properties',
        'host',
    ];

    protected $casts = [
        'properties' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'Key1', 'Key3');
    }

    protected static function boot()
    {
        parent::boot();

        static::setAttributeMapping([
            'uuid' => 'Key1',
            'description' => 'Character01',
            'subject_uuid' => 'Key2',
            'subject_type' => 'Character02',
            'user_uuid' => 'Key3',
            'properties' => 'Character03',
            'host' => 'Character04',
            'created_at' => 'Character05',
            'updated_at' => 'Character06',
        ]);
        static::creating(function ($model) {
            $model->uuid = \Illuminate\Support\Str::uuid();
        });
    }
}
