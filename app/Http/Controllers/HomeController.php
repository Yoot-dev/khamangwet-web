<?php
namespace App\Http\Controllers;
use App\Models\{Episode,Character,LoreCategory};
class HomeController extends Controller { public function __invoke(){return view('home',['episodes'=>Episode::published()->latest('published_at')->take(5)->get(),'characters'=>Character::where('is_published',true)->orderBy('sort_order')->take(6)->get(),'categories'=>LoreCategory::orderBy('sort_order')->take(5)->get()]);}}
