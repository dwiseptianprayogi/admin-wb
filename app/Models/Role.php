<?php

namespace App\Models;

use App\Traits\DynamicAttributeMapper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;
    use HasUuids, DynamicAttributeMapper;
    protected $primaryKey = 'Key1';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'Date01';
    const UPDATED_AT = 'Date02';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::setAttributeMapping([
            'uuid' => 'Key1',
            'name' => 'Character01',
            'guard_name' => 'ShortChar01',
            'created_at' => 'Date01',
            'updated_at' => 'Date01',
        ]);
        static::creating(function ($model) {
            $model->uuid = \Illuminate\Support\Str::uuid();
        });
    }
}
