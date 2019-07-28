<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_name', 'email', 'password', 'contact_no', 'user_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'user_type',
    ];

    public function hasAnyRole($roles)
    {
        $user_role = User::where('user_id', Auth::user()->user_id)                            
                            ->get();
                            
        foreach($roles as $role){
            if($role==$user_role[0]['user_type'])
                return true;
        }

        return false;

    }

}
