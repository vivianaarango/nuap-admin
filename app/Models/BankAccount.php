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
 * @property string owner_document_type
 * @property string account
 * @property string account_type
 * @property string bank
 * @property string certificate,
 * @property boolean status
 *
 * @package App\Models
 * @method static where(string $string, int $userID)
 * @method static findOrFail(int $bankAccountID)
 * @method static create(array $account)
 */
class BankAccount extends Model
{
    /**
     * @var boolean
     */
    public const ACCOUNT_INACTIVE = false;

    /**
     * @var boolean
     */
    public const ACCOUNT_ACTIVE = true;

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
        'owner_document_type',
        'account',
        'account_type',
        'bank',
        'certificate',
        'status'
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