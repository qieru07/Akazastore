<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('banners.index', compact('banners'));
    }

    public function create()
    {
        return view('banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:image,video',
            'image' => 'required_if:type,image|nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video' => 'required_if:type,video|nullable|mimes:mp4,mov,ogg,qt|max:20480', // Max 20MB
        ]);

        $fileName = null;
        if ($request->type === 'image' && $request->hasFile('image')) {
            $bannerPath = public_path('images/banners');
            if (!file_exists($bannerPath)) mkdir($bannerPath, 0755, true);
            
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($bannerPath, $fileName);
        } elseif ($request->type === 'video' && $request->hasFile('video')) {
            $videoPath = public_path('videos/banners');
            if (!file_exists($videoPath)) mkdir($videoPath, 0755, true);

            $file = $request->file('video');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($videoPath, $fileName);
        }

        Banner::create([
            'title'     => $request->title,
            'type'      => $request->type,
            'image'     => ($request->type === 'image') ? $fileName : null,
            'video_url' => ($request->type === 'video') ? $fileName : null,
            'status'    => 1,
        ]);

        return redirect()->route('banners.index')
                         ->with('success', 'Banner berhasil disimpan!');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:image,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
        ]);

        $banner = Banner::findOrFail($id);
        $banner->title = $request->title;
        $banner->type = $request->type;

        if ($request->type === 'image') {
            if ($request->hasFile('image')) {
                if ($banner->image && file_exists(public_path('images/banners/' . $banner->image))) {
                    unlink(public_path('images/banners/' . $banner->image));
                }
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/banners'), $fileName);
                $banner->image = $fileName;
            }
            $banner->video_url = null; 
        } elseif ($request->type === 'video') {
            if ($request->hasFile('video')) {
                if ($banner->video_url && file_exists(public_path('videos/banners/' . $banner->video_url))) {
                    unlink(public_path('videos/banners/' . $banner->video_url));
                }
                $file = $request->file('video');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('videos/banners'), $fileName);
                $banner->video_url = $fileName;
            }
            $banner->image = null; 
        }

        $banner->save();

        return redirect()->route('banners.index')
                         ->with('success', 'Banner berhasil diupdate!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image && file_exists(public_path('images/banners/' . $banner->image))) {
            unlink(public_path('images/banners/' . $banner->image));
        }

        if ($banner->video_url && file_exists(public_path('videos/banners/' . $banner->video_url))) {
            unlink(public_path('videos/banners/' . $banner->video_url));
        }

        $banner->delete();

        return redirect()->route('banners.index')
                         ->with('success', 'Banner berhasil dihapus!');
    }
}