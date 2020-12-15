<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Product
 * @property int id
 * @property int user_id
 * @property int category_id
 * @property string name
 * @property string brand
 * @property string description
 * @property boolean status
 * @property boolean is_featured
 * @property integer stock
 * @property float weight
 * @property float length
 * @property float width
 * @property float height
 * @property float purchase_price
 * @property float sale_price
 * @property float special_price
 * @property boolean has_special_price
 * @property string image
 * @property string position
 * @property string resource_url
 *
 * @package App\Models
 * @method static create(array $data)
 * @method static findOrFail(int $productID)
 */
class Product extends Model
{
    /**
     * @var boolean
     */
    public const STATUS_ACTIVE = true;

    /**
     * @var boolean
     */
    public const STATUS_INACTIVE = false;

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'brand',
        'description',
        'status',
        'is_featured',
        'stock',
        'weight',
        'length',
        'width',
        'height',
        'purchase_price',
        'sale_price',
        'special_price',
        'has_special_price',
        'image',
        'position'
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
        return url('/admin/product/'.$this->getKey());
    }
}
