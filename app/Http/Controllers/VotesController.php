<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Votes;
use App\Models\AccessCode;
use App\Models\Candidates;

class VotesController extends Controller
{
    public function votes(Request $request)
    {
        $validation = $request->validate([
            'access_code' => 'required|exists:access_code,code,is_used,0',
            'candidate_no' => 'required|exists:candidates,no_undi',
        ]);

        AccessCode::where('code', $validation['access_code'])->update(['is_used' => true, 'used_at' => now()]);
        $candidate = Candidates::where('no_undi', $validation['candidate_no'])->first();

        $vote = Votes::create([
            'candidate_id' => $candidate->id,
        ]);

        return response()->json(['message' => 'Vote cast successfully', 'vote' => $vote], 201);
    }

    public function accessCheck(Request $request)
    {
        $request->validate([
            'access_code' => 'required|exists:access_code,code,is_used,0',
        ]);

        return response()->json(['message' => 'Access code is valid', 'success' => true], 200);
    }

    public function results()
    {
        $results = Candidates::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalVotes = Votes::count();
        return response()->json(['results' => $results, 'total_votes' => $totalVotes], 200);
    }
}
