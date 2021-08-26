<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use App\Models\Course;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }

    public function instructor_courses() {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function student_courses() {
        return $this->belongsToMany(Course::class, 'course_student', 'user_id', 'course_id');
    }

    // public function courses() {
    //     if ($this->hasRole('instructor'))
    //         return $this->instructor_courses();
    //     return $this->student_courses();
    // }

}
