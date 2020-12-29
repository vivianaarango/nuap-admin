<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Balance
 * @property int id
 * @property int user_id
 * @property string user_type
 * @property float balance
 * @property float paid_out
 * @property float total
 * @property float requested_value
 *
 * @package App\Models
 * @method static where(string $string, int $userID)
 * @method static findOrFail(int $balanceID)
 */
class Balance extends Model
{
    /**
     * @var string
     */
    protected $table = 'balances';

    /**
     * @var string[]
     */

    protected $fillable = [
        'user_id',
        'user_type',
        'balance',
        'paid_out',
        'requested_value',
        'total'
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
        return url('/admin/balance/'.$this->getKey());
    }
}