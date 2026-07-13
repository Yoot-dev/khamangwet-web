<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EpisodeController extends Controller
{
    public function index()
    {
        return view('admin.episodes.index', ['episodes' => Episode::orderByDesc('episode_number')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.episodes.form', ['episode' => new Episode]);
    }

    public function store(Request $request)
    {
        $episode = Episode::create($this->data($request));

        return redirect()->route('admin.episodes.edit', $episode)->with('status', 'บันทึกตอนใหม่แล้ว');
    }

    public function edit(Episode $episode)
    {
        return view('admin.episodes.form', compact('episode'));
    }

    public function update(Request $request, Episode $episode)
    {
        $episode->update($this->data($request, $episode));

        return back()->with('status', 'บันทึกการแก้ไขแล้ว');
    }

    public function destroy(Episode $episode)
    {
        $episode->delete();

        return redirect()->route('admin.episodes.index')->with('status', 'ลบตอนแล้ว');
    }

    private function data(Request $request, ?Episode $episode = null): array
    {
        $data = $request->validate([
            'episode_number' => ['required', 'integer', 'min:1', 'unique:episodes,episode_number,'.($episode?->id)],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['required', 'in:draft,scheduled,published'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($episode && Str::startsWith($episode->cover_image_path, 'storage/episode-covers/')) {
                Storage::disk('public')->delete(Str::after($episode->cover_image_path, 'storage/'));
            }

            $data['cover_image_path'] = 'storage/'.$request->file('cover_image')->store('episode-covers', 'public');
        }

        unset($data['cover_image']);
        $data['slug'] = ($episode?->episode_number !== $data['episode_number'] || $episode?->title !== $data['title'])
            ? $data['episode_number'].'-'.Str::slug($data['title'])
            : $episode->slug;
        $data['created_by'] = $episode?->created_by ?? $request->user()->id;

        return $data;
    }
}
