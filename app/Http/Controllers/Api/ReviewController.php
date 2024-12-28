<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //
    public function storeupdate(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'critic' => 'required',
            'rating' => 'required',
            'movie_id' => 'required',
        ], [
            'required' => 'input :attribute required'
        ]);

        $review = Review::updateOrCreate([
            'user_id' => $user->id
        ], [
            'critic' => $request->input('critic'),
            'rating' => $request->input('rating'),
            'movie_id' => $request->input('movie_id'),
        ]);

        return response([
            'message' => 'Review berhasil dibuat/diupdate',
            'data' => $review
        ], 200);
    }
}
