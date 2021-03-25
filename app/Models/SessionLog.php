<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class SessionLog
 * @property int id
 * @property int user_id
 * @property string user_type
 * @property string login_date
 *
 * @package App\Models
 * @method static where(string $string, string $ADMIN_ROLE)
 */
class SessionLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'session_logs';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'login_date'
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
        return url('/admin/session-log/'.$this->getKey());
    }
}
