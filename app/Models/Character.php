<?php
namespace App\Models; use Illuminate\Database\Eloquent\{Model,SoftDeletes}; class Character extends Model {use SoftDeletes; protected $guarded=[];}
