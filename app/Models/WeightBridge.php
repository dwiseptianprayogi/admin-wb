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

class WeightBridge extends Model
{
	use SoftDeletes, AuditableWithDeletesTrait, Auditable, DynamicAttributeMapper;
	protected $table = 'Ice.UD100';
	protected $primaryKey = 'Key1';
	public $incrementing = false;
	const CREATED_AT = 'Date04';
	const UPDATED_AT = 'Date05';
	public $timestamps = false;

	protected $casts = [
		'weight_out' => 'integer',
		'weight_in' => 'integer',
	];

	protected $fillable = [
		'slip_no',
		'vehicle_no',
		'arrival_date',
		'weight_type',
		'vehicle_uuid',
		'weight_in',
		'weight_in_date',
		'weight_out',
		'weight_out_date',
		'weight_netto',
		'weight_standart',
		'transporter_name',
		'po_do',
		'difference',
		'weight_in_by',
		'weight_out_by',
		'remark',
		'company',
		'status'
	];

	public function vehicle()
	{
		return $this->hasOne(Vehicle::class, 'Key1', 'Key2');
	}

	// Override the SoftDeletes deleted_at column to Date04
	public function getDeletedAtColumn()
	{
		return 'Date06';  // Use your custom soft delete column
	}

	protected static function boot()
	{
		parent::boot();

		static::setAttributeMapping([
			'uuid' => 'Key1',
			'vehicle_no' => 'Character08',
			'slip_no' => 'Character01',
			'weight_in_by' => 'Character04',
			'weight_out_by' => 'Character05',
			'arrival_date' => 'Character09',
			'transporter_name' => 'Character10',
			'weight_type' => 'ShortChar01',
			'vehicle_uuid' => 'Key2',
			'weight_in' => 'Number01',
			'weight_in_date' => 'Character06',
			'weight_out' => 'Number02',
			'weight_out_date' => 'Character07',
			'weight_netto' => 'Number03',
			'weight_standart' => 'Number04',
			'po_do' => 'Character02',
			'status' => 'ShortChar02',
			'difference' => 'Number05',
			'remark' => 'Character03',
			'created_at' => 'Date04',
			'updated_at' => 'Date05',
			'deleted_at' => 'Date06',
			'created_by' => 'Key3',
			'updated_by' => 'Key4',
			'deleted_by' => 'Key5',
			'company' => 'Company',
		]);
		static::creating(function ($model) {
			$model->uuid = \Illuminate\Support\Str::uuid();
			$model->company = auth()->user()?->company;
		});
	}
}
