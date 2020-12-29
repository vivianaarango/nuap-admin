<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class OrderProduct
 * @property int id
 * @property int order_id
 * @property int product_id
 * @property int quantity
 * @property float price
 *
 * @package App\Models
 */
class OrderProduct extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_products';

    /**
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
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
        return url('/admin/order-product/'.$this->getKey());
    }
}
