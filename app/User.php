<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\PasswordForgot($token));
    }

    public static function getUserFromRequest($request) {
        $email = urldecode($request->get('email'));
        $password = urldecode($request->get('password'));
        $user = User::where([
            'email' => $email, // email is unique
        ])->get()->first();
        if ($user == null || !(\Hash::check($password, $user->password)))
            return false;
        return $user;
    }

}
