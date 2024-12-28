<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\CastMovie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CastMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->except('index', 'show');
    }
    public function index()
    {
        //
        $data = CastMovie::all();
        return response([
            'message' => 'Tampil data berhasil',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required',
            'cast_id' => 'required',
            'movie_id' => 'required'
        ], [
            'required' => 'input :attribute required'
        ]);


        $newCastMovie = CastMovie::create([
            'name' => $request->input('name'),
            'cast_id' => $request->input('cast_id'),
            'movie_id' => $request->input('movie_id'),
            'created_at' => Carbon::now()
        ]);

        return response([
            'message' => 'Tambah Cast Movie Berhasil',
            'data' => $newCastMovie,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = CastMovie::with(['movie', 'cast'])->where('id', $id)->first();

        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        return response([
            'message' => 'Detail Data Cast Movie',
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validated = $request->validate([
            'name' => 'required',
            'cast_id' => 'required',
            'movie_id' => 'required'
        ], [
            'required' => 'input :attribute required'
        ]);

        $data = CastMovie::find($id);

        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->name = $request->input('name');
        $data->cast_id = $request->input('cast_id');
        $data->movie_id = $request->input('movie_id');
        $data->save();

        return response([
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = CastMovie::find($id);
        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $data->delete();
        return response([
            'message' => 'Berhasil menghapus Cast Movie'
        ], 200);
    }
}
