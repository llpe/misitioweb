<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //protected $primarykey = "id";
    protected $table = "productos";
    protected $fillable = ['nombre','img','stock','codigo'];
}
