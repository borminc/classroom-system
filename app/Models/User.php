<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

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

    /**
     * Get full name of user
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * One-to-many relationship with courses for instructor
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instructor_courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Many-to-many relationship with courses for student
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function student_courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'user_id', 'course_id');
    }
}
