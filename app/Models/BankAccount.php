<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class BankAccount
 * @property int id
 * @property int user_id
 * @property string user_type
 * @property string owner_name
 * @property string owner_document
 * @property string account
 * @property string account_type
 * @property string bank
 *
 * @package App\Models
 * @method static where(string $string, int $userID)
 * @method static findOrFail(int $bankAccountID)
 */
class BankAccount extends Model
{
    /**
     * @var string
     */
    protected $table = 'bank_accounts';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'owner_name',
        'owner_document',
        'account',
        'account_type',
        'bank'
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
        return url('/admin/bank-account/'.$this->getKey());
    }
}
