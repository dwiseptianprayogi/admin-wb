<?php

namespace App\Models;

use App\Traits\DynamicAttributeMapper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\PermissionRegistrar;

class Permission extends SpatiePermission
{
    use HasFactory;
    use HasUuids, DynamicAttributeMapper;
    protected $primaryKey = 'Key1';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::setAttributeMapping([
            'uuid' => 'Key1',
            'name' => 'Character01',
            'guard_name' => 'ShortChar01',
            'created_at' => 'Date01',
            'updated_at' => 'Date02',
        ]);
        static::creating(function ($model) {
            $model->uuid = \Illuminate\Support\Str::uuid();
        });
    }
}
