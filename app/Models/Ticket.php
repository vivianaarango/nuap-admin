<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Ticket
 * @property int id
 * @property int user_id
 * @property int description
 * @property string user_type
 * @property string issues
 * @property string init_date
 * @property string finish_date
 * @property string status
 *
 * @package App\Models
 * @method static create(array $ticket)
 * @method static findOrFail(int $ticket)
 */
class Ticket extends Model
{
    /**
     * @var string
     */
    public const CLOSED= 'Cerrado';

    /**
     * @var string
     */
    public const OPEN = 'Abierto';

    /**
     * @var string
     */
    public const PENDING_ADMIN = 'Pendiente Administrador';

    /**
     * @var string
     */
    public const PENDING_USER = 'Pendiente Usuario';

    /**
     * @var string
     */
    protected $table = 'tickets';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'issues',
        'init_date',
        'finish_date',
        'status',
        'description'
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
        return url('/admin/ticket/'.$this->getKey());
    }
}
