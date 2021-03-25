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
 * @property integer account_admin_id
 * @property float value
 * @property string status
 * @property string voucher
 * @property string request_date
 * @property string payment_date
 *
 * @package App\Models
 * @method static where(string $string, int $userID)
 * @method static findOrFail(int $distributorID)
 * @method static create($message)
 * @method static select(string $string, string $string1, string $string2)
 */
class Payment extends Model
{
    /**
     * @var string
     */
    public const STATUS_CANCEL = 'Cancelado';

    /**
     * @var string
     */
    public const STATUS_APPROVED = 'Aprobado';

    /**
     * @var string
     */
    public const STATUS_PENDING = 'Pendiente';

    /**
     * @var string
     */
    public const STATUS_REJECTED = 'Rechazado';

    /**
     * @var string
     */
    protected $table = 'payments';

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
        'account_admin_id',
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
