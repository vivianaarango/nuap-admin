<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Ticket
 * @property int id
 * @property int ticket_id
 * @property string message
 * @property int sender_id
 * @property string sender_type
 * @property string sender_date
 *
 * @package App\Models
 * @method static create(array $data)
 * @method static findOrFail(int $productID)
 */
class TicketMessage extends Model
{
    /**
     * @var string
     */
    protected $table = 'ticket_messages';

    /**
     * @var string[]
     */
    protected $fillable = [
        'ticket_id',
        'message',
        'sender_id',
        'sender_type',
        'sender_date',
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
        return url('/admin/ticket-messages/'.$this->getKey());
    }
}
