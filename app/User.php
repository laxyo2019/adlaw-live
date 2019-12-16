<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cache;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait; // add this trait to your user model
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password','user_catg_id','status', 'gender',
    // ];
    protected $guarded = [] ;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
   
    public function specializations(){
        return $this->belongsToMany('App\Models\Specialization', 'user_specialization','user_id','catg_code');
    }
    public function u_qualifications(){

         return $this->belongsToMany('App\Models\UserQualification', 'user_qual','user_id','qual_code');
    }
    
    public function courts(){
        return $this->belongsToMany('App\Models\Court','user_courts', 'user_id','court_code');
    }
    public function state(){
        return $this->belongsTo('App\Models\State','state_code');
    }
    public function country(){
        return $this->belongsTo('App\Models\Country','country_code');
    }
    public function city(){
        return $this->belongsTo('App\Models\City','city_code');
    }

    public function reviews(){
        return $this->hasMany('App\Models\Review','user_id');
    }
  
    public function specialities(){
        return $this->hasMany('App\Models\Specialization','user_id');
    }
    public function qualifications(){
        return $this->hasMany('App\Models\UserQualification','user_id');
    }
    public function role(){
        return $this->belongsTo('App\Role','user_catg_id');
    }
    public function appointments(){  //For lawyer
        return $this->hasMany('App\Models\Booking','user_id');
    }
    public function bookings(){     //For guest
        return $this->hasMany('App\Models\Booking','client_id');
    }
    
    public function isOnline(){
        return Cache::has('user-is-online-'.$this->id);
    }
    public function user_courts(){
        return $this->hasMany('App\Models\Court','user_id');
    }
    public function clients(){
        return $this->hasMany('App\Models\Customer','user_id');
    }
    public function messages(){
        return $this->hasMany('App\Models\MessageTalk','recv_id');
    }
    public function members(){
        return $this->hasMany('App\User','parent_id');
    }
    public function teams(){
        return $this->hasMany('App\Models\Team','user_id');
    }
    // public function todos(){
    //     return $this->hasMany('App\Models\Todo','user_id1');
    // }

    public function courses(){
        return $this->hasMany('App\Models\CollegeCourse','user_id');
    }
    public function students(){
        return $this->hasMany('App\Models\StudentMast','user_id');
    }
    public function batches(){
        return $this->hasMany('App\Models\BatchMast','user_id');
    }
}
