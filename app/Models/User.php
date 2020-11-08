<?php
namespace App\Models;

use Brackets\Media\Exceptions\Collections\MediaCollectionAlreadyDefined;
use Brackets\Media\HasMedia\HasMediaCollections;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Brackets\Media\HasMedia\HasMediaThumbsTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

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
 * @property string image_url
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
class User extends Model implements HasMediaCollections, HasMediaConversions
{
    use HasMediaCollectionsTrait;
    use HasMediaThumbsTrait;

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
        'image_url',
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

    /**
     * @var string[]
     */
    protected $appends = ['resource_url'];

    /**
     * @return UrlGenerator|Application|string
     */
    public function getResourceUrlAttribute()
    {
        return url('/admin/users/'.$this->getKey());
    }


    /**********************************************************************/
    /**
     * @return array|void
     * @throws MediaCollectionAlreadyDefined
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')
            ->accepts('image/*');
    }

    /**
     * @param Media|null $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->autoRegisterThumb200();

        $this->addMediaConversion('thumb_75')
            ->width(75)
            ->height(75)
            ->fit('crop', 75, 75)
            ->optimize()
            ->performOnCollections('avatar')
            ->nonQueued();

        $this->addMediaConversion('thumb_150')
            ->width(150)
            ->height(150)
            ->fit('crop', 150, 150)
            ->optimize()
            ->performOnCollections('avatar')
            ->nonQueued();
    }

    public function autoRegisterThumb200()
    {
        $this->getMediaCollections()->filter->isImage()->each(function ($mediaCollection) {
            $this->addMediaConversion('thumb_200')
                ->width(200)
                ->height(200)
                ->fit('crop', 200, 200)
                ->optimize()
                ->performOnCollections($mediaCollection->getName())
                ->nonQueued();;
        });
    }
}
