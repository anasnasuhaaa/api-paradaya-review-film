<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
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
        $data = Genre::all();
        return response([
            'message' => 'Tampil Data Berhasil',
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
        ], [
            'required' => 'input :attribute required'
        ]);


        $newCast = Genre::create([
            'id' => Str::uuid(),
            'name' => $request->input('name'),
            'created_at' => Carbon::now()
        ]);

        return response([
            'message' => 'Tambah Genre Berhasil',
            'name' => $newCast->name,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = Genre::with('listMovies')->find($id);

        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        return response([
            'message' => 'Detail Data Genre',
            'name' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = Genre::find($id);
        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $data->update([
            'name' => $request->input('name'),
            'updated_at' => Carbon::now()
        ]);
        return response([
            'message' => 'Update Genre Berhasil',
            'name' => $data->name,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = Genre::find($id);
        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $data->delete();
        return response([
            'message' => 'Berhasil menghapus Genre'
        ], 200);
    }
}
