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

/**
 * Class VehicleType
 *
 * @property string $uuid
 * @property string $name
 * @property string $code
 * @property string $tolerance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Vehicle[] $vehicles
 *
 * @package App\Models
 */
class VehicleType extends Model
{
	use AuditableWithDeletesTrait, Auditable, DynamicAttributeMapper;
	protected $table = 'Ice.UD101';
	protected $primaryKey = 'Key1';
	public $incrementing = false;
	const CREATED_AT = 'Date01';
	const UPDATED_AT = 'Date02';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'code',
		'tolerance',
	];

	protected $casts = [
		'tolerance' => 'integer',
		'weight_standart' => 'integer',
	];

	public function vehicles()
	{
		return $this->hasMany(Vehicle::class, 'vehicle_type_uuid');
	}

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'uuid' => 'Key1',
			'name' => 'Character01',
			'code' => 'ShortChar01',
			'tolerance' => 'Number01',
			'weight_standart' => 'Number02',
			'created_at' => 'Date01',
			'updated_at' => 'Date02',
			'deleted_at' => 'Date03',
			'created_by' => 'Key2',
			'updated_by' => 'Key3',
			'deleted_by' => 'Key4',
		]);
		static::creating(function ($model) {
			$model->uuid = \Illuminate\Support\Str::uuid();
			if (VehicleType::where('ShortChar01', $model->code)->exists()) {
				throw new \Exception('Data already exist. Details: code ' . $model->code);
			}
		});
	}
}
