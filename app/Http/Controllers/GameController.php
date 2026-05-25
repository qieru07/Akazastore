<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();

        return view('games.index', compact('games'));
    }

    public function create()
    {
        return view('games.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|string',
            'thumbnail' => 'required_without:thumbnail_url|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'thumbnail_url' => 'required_without:thumbnail|nullable|string',
            'video' => 'nullable|mimes:mp4|max:20480', // Max 20MB
        ]);

        $game = new \App\Models\Game();
        $game->name = $request->name;
        $game->slug = $request->slug;
        $game->status = $request->status;

        try {
            if ($request->hasFile('thumbnail')) {
                $imgName = time() . '_img.' . $request->thumbnail->extension();
                $request->thumbnail->move(public_path('images/games'), $imgName);
                $game->thumbnail = $imgName;
            } elseif ($request->filled('thumbnail_url')) {
                $game->thumbnail = $request->thumbnail_url;
            } else {
                return back()->withInput()->withErrors(['thumbnail' => 'Silakan upload gambar atau isi URL gambar eksternal.']);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['thumbnail' => 'Gagal mengunggah file gambar ke server (sistem serverless Vercel bersifat read-only). Silakan gunakan opsi URL Gambar Eksternal di bawah!']);
        }

        try {
            if ($request->hasFile('video')) {
                $vidName = time() . '_vid.' . $request->video->extension();
                $request->video->move(public_path('videos/games'), $vidName);
                $game->video = $vidName;
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['video' => 'Gagal mengunggah file video ke server (sistem serverless Vercel bersifat read-only).']);
        }

        $game->save();

        return redirect()->route('games.index')->with('success', 'Game berhasil disimpan!');
    }

    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'thumbnail_url' => 'nullable|string',
        ]);

        $game->name = $request->name;
        $game->slug = $request->slug;

        try {
            if ($request->hasFile('thumbnail')) {
                if ($game->thumbnail && !str_starts_with($game->thumbnail, 'http') && file_exists(public_path('images/games/' . $game->thumbnail))) {
                    unlink(public_path('images/games/' . $game->thumbnail));
                }
                $imgName = time() . '_img.' . $request->thumbnail->extension();
                $request->thumbnail->move(public_path('images/games'), $imgName);
                $game->thumbnail = $imgName;
            } elseif ($request->filled('thumbnail_url')) {
                // If there was a local image previously, delete it
                if ($game->thumbnail && !str_starts_with($game->thumbnail, 'http') && file_exists(public_path('images/games/' . $game->thumbnail))) {
                    unlink(public_path('images/games/' . $game->thumbnail));
                }
                $game->thumbnail = $request->thumbnail_url;
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['thumbnail' => 'Gagal mengunggah file gambar ke server (sistem serverless Vercel bersifat read-only). Silakan gunakan opsi URL Gambar Eksternal!']);
        }

        $game->save();

        return redirect()->route('games.index')->with('success', 'Game berhasil diupdate!');
    }

    public function destroy($id)
    {
        $game = \App\Models\Game::findOrFail($id);
        
        if (file_exists(public_path('images/games/' . $game->thumbnail))) {
            unlink(public_path('images/games/' . $game->thumbnail));
        }

        $game->delete();
        return back()->with('success', 'Game berhasil dihapus!');
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();
        $products = $game->products;
        $paymentMethods = \App\Models\PaymentMethod::where('status', 'aktif')->get();
        
        return view('games.show', compact('game', 'products', 'paymentMethods'));
    }
}