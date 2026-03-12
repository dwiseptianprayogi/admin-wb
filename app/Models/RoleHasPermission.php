<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\DynamicAttributeMapper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
class RoleHasPermission extends Model
{
	use HasUuids, DynamicAttributeMapper;
	protected $table = 'Ice.UD06';
	protected $primaryKey = 'Key1';
	public $incrementing = false;

	protected $fillable = [
		'role_uuid',
		'permission_uuid',
	];

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'role_uuid' => 'Key2',
			'permission_uuid' => 'Key1',
			'created_at' => 'Date01',
			'updated_at' => 'Date02',
		]);
	}
}
