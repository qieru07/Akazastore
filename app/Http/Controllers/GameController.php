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
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|mimes:mp4|max:20480', // Max 20MB
        ]);

        $game = new \App\Models\Game();
        $game->name = $request->name;
        $game->slug = $request->slug;
        $game->status = $request->status;

        if ($request->hasFile('thumbnail')) {
            $imgName = time() . '_img.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('images/games'), $imgName);
            $game->thumbnail = $imgName;
        }

        if ($request->hasFile('video')) {
            $vidName = time() . '_vid.' . $request->video->extension();
            $request->video->move(public_path('videos/games'), $vidName);
            $game->video = $vidName;
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
        ]);

        $game->name = $request->name;
        $game->slug = $request->slug;

        if ($request->hasFile('thumbnail')) {
           
            if ($game->thumbnail && !str_starts_with($game->thumbnail, 'http') && file_exists(public_path('images/games/' . $game->thumbnail))) {
                unlink(public_path('images/games/' . $game->thumbnail));
            }
            $imgName = time() . '_img.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('images/games'), $imgName);
            $game->thumbnail = $imgName;
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