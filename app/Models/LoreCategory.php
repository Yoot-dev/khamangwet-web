<?php namespace App\Models; use Illuminate\Database\Eloquent\Model; class LoreCategory extends Model {protected $guarded=[]; public function entries(){return $this->hasMany(LoreEntry::class);}}
