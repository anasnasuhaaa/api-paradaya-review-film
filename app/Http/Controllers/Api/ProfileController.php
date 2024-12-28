<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function storeupdate(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'age' => 'required',
            'biodata' => 'required',
            'address' => 'required',
        ], [
            'required' => 'input :attribute required'
        ]);

        $profile = Profile::updateOrCreate([
            'user_id' => $user->id
        ], [
            'age' => $request->input('age'),
            'biodata' => $request->input('biodata'),
            'address' => $request->input('address'),
        ]);

        return response([
            'message' => 'Profile berhasil dibuat/diupdate',
            'data' => $profile
        ], 200);
    }
}
