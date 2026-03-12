<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\DynamicAttributeMapper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;

/**
 * Class Vehicle
 *
 * @property string $uuid
 * @property string $register_number
 * @property string $status
 * @property string $type
 * @property string $vehicle_type_uuid
 * @property string $description
 * @property string $transporter_uuid
 * @property string $ownership
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property VehicleType $vehicle_type
 * @property TransporterRate $transporter_rate
 * @property Transporter $transporter
 *
 * @package App\Models
 */
class Vehicle extends Model
{
	use AuditableWithDeletesTrait, Auditable, DynamicAttributeMapper;
	protected $table = 'Ice.UD101A';
	protected $primaryKey = 'Key1';
	public $incrementing = false;
	const CREATED_AT = 'Date01';
	const UPDATED_AT = 'Date02';
	public $timestamps = false;

	protected $fillable = [
		'register_number',
    'status',
		'type',
		'vehicle_type_uuid',
		'description',
		'ownership',
		'transporter_uuid',
	];

	public function vehicle_type()
	{
		return $this->belongsTo(VehicleType::class, 'Key2', 'Key1');
	}

	public function vehicle_transporters()
	{
		return $this->hasMany(VehicleTransporter::class, 'Key3', 'Key1');
	}

	public function transporter()
	{
		return $this->hasOne(Transporter::class, 'Key1', 'Key3');
	}

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'uuid' => 'Key1',
			'register_number' => 'Character01',
			'status' => 'ShortChar02',
			'type' => 'ShortChar04',
			'vehicle_type_uuid' => 'Key2',
			'description' => 'Character02',
			'ownership' => 'ShortChar03',
			'transporter_uuid' => 'Key3',
			'created_at' => 'Date01',
			'updated_at' => 'Date02',
			'deleted_at' => 'Date03',
			'created_by' => 'Key5',
			'updated_by' => 'ChildKey1',
			'deleted_by' => 'ChildKey2',
		]);
		static::creating(function ($model) {
			$model->uuid = \Illuminate\Support\Str::uuid();
			if (Vehicle::where('Character01', $model->register_number)->exists()) {
				throw new \Exception('Data already exist. Details: register number ' . $model->register_number);
			}
		});
	}
}
