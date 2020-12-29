<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Order
 * @property int id
 * @property int user_id
 * @property string user_type
 * @property string status
 * @property string cancel_reason
 * @property int client_id
 * @property string client_type
 * @property int total_products
 * @property float total_amount
 * @property float delivery_amount
 * @property float total_discount
 * @property float total
 *
 * @package App\Models
 * @method static where(string $string, int $userID)
 * @method static findOrFail(int $orderID)
 */
class Order extends Model
{
    /**
     * @var string
     */
    public const STATUS_CANCEL = 'Cancelado';

    /**
     * @var string
     */
    public const STATUS_INITIATED = 'Iniciado';

    /**
     * @var string
     */
    public const STATUS_ACCEPTED = 'Aceptado';

    /**
     * @var string
     */
    public const STATUS_ENLISTMENT = 'Alistamiento';

    /**
     * @var string
     */
    public const STATUS_CIRCULATION = 'Circulación';

    /**
     * @var string
     */
    public const STATUS_DELIVERED = 'Entregado';

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var string[]
     */

    protected $fillable = [
        'user_id',
        'user_type',
        'status',
        'cancel_reason',
        'client_id',
        'client_type',
        'total_products',
        'total_amount',
        'delivery_amount',
        'total_discount'.
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
        return url('/admin/order/'.$this->getKey());
    }
}
