<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ekspetasi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'skp_id',
        'category_id',
        'ekspetasi',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the SKP associated with the Ekspetasi.
     */
    public function skp()
    {
        return $this->belongsTo(Skp::class);
    }

    /**
     * Get the category associated with the Ekspetasi.
     */
    public function category()
    {
        return $this->belongsTo(CategoryPerilaku::class);
    }
}