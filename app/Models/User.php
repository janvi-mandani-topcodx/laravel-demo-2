<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , HasRoles , Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    use InteractsWithMedia;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'gender',
        'hobbies',
        'phone_number',
        'email_verified_at',
        'credit',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getImageUrlAttribute()
    {
        $img = [];
        $userImage = $this->getMedia('user');
        if($userImage){
            foreach ($userImage as $image) {
                $img[] =  $image->getUrl();
            }
            return $img;
        }
        return null;
    }



}
