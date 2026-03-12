<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\DynamicAttributeMapper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;

class Device extends Model
{
	use DynamicAttributeMapper;
	protected $table = 'Ice.UD05';
	protected $primaryKey = 'Key1';
	public $incrementing = false;
	const CREATED_AT = 'Date02';
	const UPDATED_AT = 'Date03';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'secret',
		'current_weight',
		'previous_weight',
		'tolerance',
		'status',
		'used_at',
	];

	protected $casts = [
		'updated_at' => 'date:Y-m-d',
		'created_at' => 'date:Y-m-d',
		'current_weight' => 'integer',
	];

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'uuid' => 'Key1',
			'name' => 'Character01',
			'secret' => 'Character02',
			'status' => 'Character03',
			'tolerance' => 'Number03',
			'current_weight' => 'Number01',
			'previous_weight' => 'Number02',
			'used_at' => 'Character04',
			'last_update' => 'Character05',
			'created_at' => 'Date02',
			'updated_at' => 'Date03',
			'deleted_at' => 'Date04',
			'created_by' => 'Key1',
			'updated_by' => 'Key2',
			'deleted_by' => 'Key3',
		]);
		static::creating(function ($model) {
			$model->uuid = \Illuminate\Support\Str::uuid();
		});
	}
}
