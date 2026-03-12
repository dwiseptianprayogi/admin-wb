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
use Yajra\Auditable\AuditableWithDeletesTrait;

/**
 * Class Region
 * 
 * @property string $uuid
 * @property string $vehicle_uuid
 * @property string $transporter_uuid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|VehicleTransporter[] $vehicleTransporters
 *
 * @package App\Models
 */
class VehicleTransporter extends Model
{
	use AuditableWithDeletesTrait, DynamicAttributeMapper;
	protected $table = 'Ice.UD09';
	protected $primaryKey = 'Key1';
	public $incrementing = false;
	const CREATED_AT = 'Date01';
	const UPDATED_AT = 'Date02';
	public $timestamps = false;

	protected $fillable = [
		'vehicle_uuid',
		'transporter_uuid'
	];

	public function transporter()
	{
		return $this->hasOne(Transporter::class, 'Key1', 'Key2');
	}

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'uuid' => 'Key1',
			'transporter_uuid' => 'Key2',
			'vehicle_uuid' => 'Key3',
			'created_at' => 'Date01',
			'updated_at' => 'Date02',
			'deleted_at' => 'Date03',
			'created_by' => 'Key4',
			'updated_by' => 'Key5',
			'deleted_by' => 'Character01',
		]);
		static::creating(function ($model) {
			$model->uuid = \Illuminate\Support\Str::uuid();
		});
	}
}
