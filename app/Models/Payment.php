<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Payment
 * @property int id
 * @property int user_id
 * @property string user_type
 * @property integer account_id
 * @property float value
 * @property string status
 * @property string voucher
 * @property string request_date
 * @property string payment_date
 *
 * @package App\Models
 */
class Payment extends Model
{
    /**
     * @var string
     */
    protected $table = 'balances';

    /**
     * @var string[]
     */

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'account_id',
        'value',
        'status',
        'voucher',
        'request_date',
        'payment_date',
    ];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $dates = [
        'request_date',
        'payment_date',
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
        return url('/admin/payment/'.$this->getKey());
    }
}
