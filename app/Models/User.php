<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @property int id
 * @property string name
 * @property string lastname
 * @property string identity_type
 * @property string identity_number
 * @property string phone
 * @property string email
 * @property string password
 * @property string role
 * @property float discount
 * @property float commission
 * @property string last_logged_in
 * @property boolean status
 * @property mixed first
 * @package App\Models
 * @method static where(string $string, string $email)
 * @method static create(array $sanitized)
 */
class User extends Model
{
    /**
     * @var boolean
     */
    public const STATUS_ACTIVE = true;

    /**
     * @var string
     */
    public const ADMIN_ROLE = 'Administrador';

    /**
     * @var string
     */
    public const WHOLESALER_ROLE = 'Mayorista';

    /**
     * @var string
     */
    public const COMMERCE_ROLE = 'Comercio';

    /**
     * @var string
     */
    public const USER_ROLE = 'Usuario';

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'lastname',
        'identity_type',
        'identity_number',
        'phone',
        'email',
        'password',
        'status',
        'role',
        'discount',
        'commission',
        'last_logged_in',
    ];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $dates = [
        'last_logged_in',
        'created_at',
        'updated_at',
    ];
}
