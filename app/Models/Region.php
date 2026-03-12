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
 * @property string $name
 * @property string $code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Area[] $areas
 *
 * @package App\Models
 */
class Region extends Model
{
	use AuditableWithDeletesTrait, Auditable, DynamicAttributeMapper;
	protected $table = 'Ice.UD103';
	protected $primaryKey = 'Key1';
	public $incrementing = false;
	const CREATED_AT = 'Date01';
	const UPDATED_AT = 'Date02';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'code'
	];

	public function areas()
	{
		return $this->hasMany(Area::class, 'region_uuid');
	}

	// Override the SoftDeletes deleted_at column to Date04
	public function getDeletedAtColumn()
	{
		return 'Date04';  // Use your custom soft delete column
	}

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'uuid' => 'Key1',
			'name' => 'Character01',
			'code' => 'ShortChar01',
			'created_at' => 'Date01',
			'updated_at' => 'Date02',
			'deleted_at' => 'Date03',
			'created_by' => 'Key2',
			'updated_by' => 'Key3',
			'deleted_by' => 'Key4',
		]);
		static::creating(function ($model) {
			$model->uuid = \Illuminate\Support\Str::uuid();
            if (Region::where('ShortChar01', $model->code)->exists()) {
                throw new \Exception('Data already exist. Details: code ' . $model->code);
            }
		});
	}
}
