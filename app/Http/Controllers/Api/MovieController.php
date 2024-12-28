<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Movie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MovieController extends Controller
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
        $data = Movie::all();
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
            'title' => 'required',
            'summary' => 'required',
            'year' => 'required',
            'poster' => 'required|image|mimes:jpg,jpeg,png',
            'genre_id' => 'required|exists:genres,id',
        ], [
            'required' => 'input :attribute required'
        ]);

        $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
            'folder' => 'images',
        ])->getSecurePath();

        $newMovie = Movie::create([
            'id' => Str::uuid(),
            'title' => $request->input('title'),
            'poster' => $uploadedFileUrl,
            'genre_id' => $request->input('genre_id'),
            'summary' => $request->input('summary'),
            'year' => $request->input('year'),
            'created_at' => Carbon::now()
        ]);

        return response([
            'message' => 'Tambah Movie Berhasil',
            'title' => $newMovie->title,
            'summary' => $newMovie->summary,
            'year' => $newMovie->year,
            'poster' => $newMovie->poster,
            'genre_id' => $newMovie->genre_id,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = Movie::with(['genre', 'listCast', 'listReviews'])->where('id', $id)->first();

        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        return response([
            'message' => 'Detail Data Movie',
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
            'title' => 'required',
            'poster' => 'image|mimes:jpg,jpeg,png',
            'summary' => 'required',
            'year' => 'required',
            'genre_id' => 'required|exists:genres,id'
        ], [
            'required' => 'input :attribute required'
        ]);

        $data = Movie::find($id);

        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        if ($request->hasFile('poster')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'images',
            ])->getSecurePath();
            $data->poster = $uploadedFileUrl;
        }

        $data->title = $request->input('title');
        $data->summary = $request->input('summary');
        $data->genre_id = $request->input('genre_id');
        $data->year = $request->input('year');
        $data->save();

        return response([
            'message' => 'Update Movie Berhasil',
            'title' => $data->title,
            'summary' => $data->summary,
            'year' => $data->year,
            'poster' => $data->poster,
            'genre_id' => $data->genre_id,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = Movie::find($id);
        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $data->delete();
        return response([
            'message' => 'Berhasil menghapus Movie'
        ], 200);
    }
}
