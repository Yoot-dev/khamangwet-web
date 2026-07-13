<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Episode; use Illuminate\Http\Request; use Illuminate\Support\Str;
class EpisodeController extends Controller {
 public function index(){return view('admin.episodes.index',['episodes'=>Episode::orderByDesc('episode_number')->paginate(20)]);}
 public function create(){return view('admin.episodes.form',['episode'=>new Episode]);}
 public function store(Request $r){$episode=Episode::create($this->data($r));return redirect()->route('admin.episodes.edit',$episode)->with('status','บันทึกตอนใหม่แล้ว');}
 public function edit(Episode $episode){return view('admin.episodes.form',compact('episode'));}
 public function update(Request $r,Episode $episode){$episode->update($this->data($r,$episode));return back()->with('status','บันทึกการแก้ไขแล้ว');}
 public function destroy(Episode $episode){$episode->delete();return redirect()->route('admin.episodes.index')->with('status','ลบตอนแล้ว');}
 private function data(Request $r,?Episode $episode=null):array{$d=$r->validate(['episode_number'=>['required','integer','min:1','unique:episodes,episode_number,'.($episode?->id)],'title'=>['required','string','max:255'],'excerpt'=>['nullable','string','max:1000'],'content'=>['nullable','string'],'status'=>['required','in:draft,scheduled,published'],'published_at'=>['nullable','date']]);$d['slug']=($episode?->episode_number!==$d['episode_number']||$episode?->title!==$d['title'])?$d['episode_number'].'-'.Str::slug($d['title']):$episode->slug;$d['created_by']=$episode?->created_by??$r->user()->id;return $d;}
}
