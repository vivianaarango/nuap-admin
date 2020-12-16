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
 * @property boolean is_closed
 *
 * @package App\Models
 * @method static create(array $ticket)
 */
class Ticket extends Model
{
    /**
     * @var boolean
     */
    public const CLOSED= true;

    /**
     * @var boolean
     */
    public const NOT_CLOSED = false;

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
        'is_closed',
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
