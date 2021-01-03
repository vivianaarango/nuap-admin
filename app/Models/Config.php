<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class PayuPayment
 * @property int id
 * @property float distance
 * @property float shipping_cost
 * @property int account_id
 *
 * @package App\Models
 * @method static first()
 * @method static create(array $data)
 * @method static where(string $string, mixed $config_id)
 */
class Config extends Model
{
    /**
     * @var string
     */
    protected $table = 'config';

    /**
     * @var string[]
     */
    protected $fillable = [
        'shipping_cost',
        'distance',
        'account_id'
    ];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['resource_url'];

    /**
     * @return UrlGenerator|Application|string
     */
    public function getResourceUrlAttribute()
    {
        return url('/admin/config/'.$this->getKey());
    }
}
