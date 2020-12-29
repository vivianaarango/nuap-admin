<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class PayuPayment
 * @property int id
 * @property int order_id
 *
 * @package App\Models
 */
class PayuPayment extends Model
{
    /**
     * @var string
     */
    protected $table = 'payu_payments';

    /**
     * @var string[]
     */
    protected $fillable = [
        'order_id'
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
        return url('/admin/payu-payment/'.$this->getKey());
    }
}
