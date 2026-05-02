<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidates;

class CandidatesController extends Controller
{
    public function index()
    {
        $candidates = Candidates::orderBy('no_undi', 'asc')->get();
        return response()->json(['candidates' => $candidates], 200);
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'no_undi' => 'required|integer|unique:candidates,no_undi',
            'path_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'calon_ketua' => 'required|string',
            'calon_wakil' => 'required|string',
            'visi' => 'string|nullable',
            'misi' => 'string|nullable',
            'proker_unggulan' => 'string|nullable',
        ]);

       $path = $request->file('path_photo')->store('photos', 'public');

        $candidate = Candidates::create([
            'no_undi' => $validation['no_undi'],
            'path_photo' => $path,
            'calon_ketua' => $validation['calon_ketua'],
            'calon_wakil' => $validation['calon_wakil'],
        ]);

        return response()->json(['message' => 'Candidate created successfully', 'candidate' => $candidate], 201);
    }

    public function show($id)
    {
        $candidate = Candidates::find($id);
        if (!$candidate) {
            return response()->json(['message' => 'Candidate not found'], 404);
        }
        return response()->json(['candidate' => $candidate], 200);
    }

    public function update(Request $request, $id)
    {
        $candidate = Candidates::find($id);
        if (!$candidate) {
            return response()->json(['message' => 'Candidate not found'], 404);
        }

        $validation = $request->validate([
            'no_undi' => 'integer|unique:candidates,no_undi,' . $id,
            'path_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'calon_ketua' => 'string',
            'calon_wakil' => 'string',
            'visi' => 'string|nullable',
            'misi' => 'string|nullable',
            'proker_unggulan' => 'string|nullable',
        ]);

        if ($request->hasFile('path_photo')) {
            $path = $request->file('path_photo')->store('photos', 'public');
            $candidate->path_photo = $path;

            $candidate->update([
                'no_undi' => $validation['no_undi'] ?? $candidate->no_undi,
                'calon_ketua' => $validation['calon_ketua'] ?? $candidate->calon_ketua,
                'calon_wakil' => $validation['calon_wakil'] ?? $candidate->calon_wakil,
                'path_photo' => $path,
            ]);
        } else {
            $candidate->update([
                'no_undi' => $validation['no_undi'] ?? $candidate->no_undi,
                'calon_ketua' => $validation['calon_ketua'] ?? $candidate->calon_ketua,
                'calon_wakil' => $validation['calon_wakil'] ?? $candidate->calon_wakil,
            ]);
        }

        return response()->json(['message' => 'Candidate updated successfully', 'candidate' => $candidate], 200);
    }

    public function destroy($id)
    {
        $candidate = Candidates::find($id);
        if (!$candidate) {
            return response()->json(['message' => 'Candidate not found'], 404);
        }

        $candidate->delete();

        return response()->json(['message' => 'Candidate deleted successfully'], 200);
    }
}
