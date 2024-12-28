<?php

namespace App\Http\Controllers\Api;

use App\Models\Cast;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CastController extends Controller
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
        $data = Cast::all();
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
            'age' => 'required',
            'bio' => 'required'
        ], [
            'required' => 'input :attribute required'
        ]);


        $newCast = Cast::create([
            'id' => Str::uuid(),
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'bio' => $request->input('bio'),
            'created_at' => Carbon::now()
        ]);

        return response([
            'message' => 'Tambah Cast Berhasil',
            'name' => $newCast->name,
            'bio' => $newCast->bio,
            'age' => $newCast->age
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = Cast::find($id);
        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        return response([
            'message' => 'Detail Data Cast',
            'name' => $data->name,
            'bio' => $data->bio,
            'age' => $data->age
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = Cast::find($id);
        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $data->update([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'bio' => $request->input('bio'),
            'updated_at' => Carbon::now()
        ]);
        return response([
            'message' => 'Update Cast Berhasil',
            'name' => $data->name,
            'bio' => $data->bio,
            'age' => $data->age
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = Cast::find($id);
        if (!$data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $data->delete();
        return response([
            'message' => 'Berhasil menghapus cast'
        ], 200);
    }
}
