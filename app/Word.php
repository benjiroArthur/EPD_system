<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    //fillable
    protected $fillable = ['name', 'meaning'];
}
