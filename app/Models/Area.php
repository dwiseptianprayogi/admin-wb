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
 * Class Area
 * 
 * @property string $uuid
 * @property string $name
 * @property string $code
 * @property string $region_uuid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Region $region
 * @property Collection|TransporterRate[] $transporter_rates
 *
 * @package App\Models
 */
class Area extends Model
{
	use AuditableWithDeletesTrait, Auditable, DynamicAttributeMapper;
	protected $table = 'Ice.UD103A';
	protected $primaryKey = 'Key1';
	public $incrementing = false;
	const CREATED_AT = 'Date01';
	const UPDATED_AT = 'Date02';
	public $timestamps = false;
	protected $fillable = [
		'name',
		'code',
		'region_uuid'
	];

	public function region()
	{
		return $this->hasOne(Region::class, 'Key1', 'ChildKey1');
	}
	public function region_single()
	{
		return $this->hasOne(Region::class, 'Key1', 'ChildKey1');
	}

	public function transporter_rates()
	{
		return $this->hasMany(TransporterRate::class, 'area_uuid');
	}

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'uuid' => 'Key1',
			'name' => 'Character01',
			'code' => 'ShortChar01',
			'region_uuid' => 'ChildKey1',
			'created_at' => 'Date01',
			'updated_at' => 'Date02',
			'deleted_at' => 'Date03',
			'created_by' => 'Key2',
			'updated_by' => 'Key3',
			'deleted_by' => 'Key4',
		]);
		static::creating(function ($model) {
			$model->uuid = \Illuminate\Support\Str::uuid();
			if (Area::where('ShortChar01', $model->code)->exists()) {
				throw new \Exception('Data already exist. Details: code ' . $model->code);
			}
		});
	}
}
