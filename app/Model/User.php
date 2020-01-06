<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $primaryKey='id';
    protected $guarded = [];
    protected $table = 'user';
    public $timestamps = false;
}
