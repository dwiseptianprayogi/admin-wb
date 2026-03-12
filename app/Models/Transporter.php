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
 * Class Transporter
 *
 * @property string $uuid
 * @property string $name
 * @property string $code
 * @property string $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Vehicle[] $vehicles
 *
 * @package App\Models
 */
class Transporter extends Model
{
	use AuditableWithDeletesTrait, Auditable, DynamicAttributeMapper;
	protected $table = 'Ice.UD102';
	protected $primaryKey = 'Key1';
	public $incrementing = false;
	const CREATED_AT = 'Date01';
	const UPDATED_AT = 'Date02';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'code',
		'address',
		'area_uuid',
    'contact_name',
    'phone'
	];

	public function vehicles()
	{
		return $this->hasMany(Vehicle::class, 'transporter_uuid');
	}

	public function area()
	{
		return $this->hasOne(Area::class, 'Key1', 'Key2');
	}

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'uuid' => 'Key1',
			'name' => 'Character01',
			'code' => 'ShortChar01',
			'address' => 'Character02',
      'contact_name' => 'Character03',
      'phone' => 'Character04',
			'area_uuid' => 'Key2',
			'created_at' => 'Date01',
			'updated_at' => 'Date02',
			'deleted_at' => 'Date03',
			'created_by' => 'Key2',
			'updated_by' => 'Key3',
			'deleted_by' => 'Key4',
		]);
		static::creating(function ($model) {
			$model->uuid = \Illuminate\Support\Str::uuid();
			if (Transporter::where('ShortChar01', $model->code)->exists()) {
				throw new \Exception('Data already exist. Details: code ' . $model->code);
			}
		});
	}
}
