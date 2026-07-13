<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\{Episode,Character,LoreEntry};
class DashboardController extends Controller { public function __invoke(){ return view('admin.dashboard',['totalEpisodes'=>Episode::count(),'publishedEpisodes'=>Episode::published()->count(),'draftEpisodes'=>Episode::where('status','draft')->count(),'characters'=>Character::count(),'loreEntries'=>LoreEntry::count()]);}}
