<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'instructor_id'
    ];

    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    public function students() {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'user_id');
    }
}
