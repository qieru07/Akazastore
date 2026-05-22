<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
    public function index()
    {
        $games = Game::with(['products' => function($query) {
            $query->orderBy('price', 'asc');
        }])->where('status', 1)->get();

        $games->map(function ($game) {
            if (str_starts_with($game->thumbnail, 'http')) {
                $game->thumbnail_url = $game->thumbnail;
            } else {
                $game->thumbnail_url = url('images/games/' . $game->thumbnail);
            }
            $game->video_url = $game->video ? url('videos/games/' . $game->video) : null;
            $game->slug = $game->slug ?? 'item'; // Fallback ke item.php jika slug kosong
            return $game;
        });

        return response()->json([
            'status' => 'success',
            'data' => $games
        ], 200);
    }

        public function getPaymentMethods() {
        $methods = \App\Models\PaymentMethod::where('status', 1)->get();

        return response()->json([
            'status' => 'success',
            'data' => $methods->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'type' => $item->type,
                    'account_number' => $item->account_number,
                    'image_url' => $item->image ? url('images/payments/' . $item->image) : null
                ];
            })
        ]);
    }

    public function getBanners() {
        $banners = \App\Models\Banner::where('status', 1)->get();

        return response()->json([
            'status' => 'success',
            'data' => $banners->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => $item->type,
                    'image_url' => $item->image ? url('images/banners/' . $item->image) : null,
                    'video_url' => $item->video_url ? url('videos/banners/' . $item->video_url) : null
                ];
            })
        ]);
    }
}