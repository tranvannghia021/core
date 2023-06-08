<?php

namespace Devtvn\Social\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Core extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var array
     */
    public $customsFill = [];


    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('social.db_connection'));
        $this->setTable(config('social.models.table.name'));
        $this->customsFill = (array)config('social.models.table.customs');
        self::addColumnCustom();
        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'internal_id',
        'platform',
        'email',
        'email_verified_at',
        'password',
        'first_name',
        'last_name',
        'avatar',
        'gender',
        'status',
        'phone',
        'birthday',
        'address',
        'refresh_token',
        'access_token',
        'expire_token',
        'is_disconnect',
        'settings',
        'domain',
        'raw_domain'
    ];

    /**
     * auto add custom field in fillable
     * @return void
     */
    private function addColumnCustom()
    {
        foreach ($this->customsFill as $value) {
            $this->fillable[] = $value['column'];
        }
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // notice that here the attribute name is in snake_case
    protected $appends = ['full_name'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the user's avatar.
     *
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        $url = null;
        if (!is_null($value) && !str_contains($value, 'http')) {
            $url = config('app.url') . '/storage/app/' . $value;
        }
        return $url;
    }
}
