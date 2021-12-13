<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'position', 'email', 'phone', 'address', 'salary', 'start_date'];

    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }

    public function visitors()
    {
        return $this->hasMany("App\Models\Visitor");
    }
}
