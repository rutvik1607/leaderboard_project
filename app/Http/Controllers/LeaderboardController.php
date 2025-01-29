<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $query = User::select('id', 'name', 'total_points', 'rank');
        $searchId = $request->has('search') ? $request->search : null;

        if ($request->has('filter')) {
            $filter = $request->filter;
            $query->whereHas('activities', function ($q) use ($filter) {
                if ($filter === 'day') {
                    $q->whereDate('performed_at', today());
                } elseif ($filter === 'month') {
                    $q->whereMonth('performed_at', now()->month);
                } elseif ($filter === 'year') {
                    $q->whereYear('performed_at', now()->year);
                }
            });
        }
        $users = collect();

        if ($request->has('search')) {
            $searchId = $request->search;

            $user = User::select('id', 'name', 'total_points', 'rank')
                ->where('id', $searchId)
                ->first();

            if ($user) {
                $users->push($user);
            }

            $otherUsers = $query->where('id', '!=', $searchId)
                ->orderByDesc('total_points')
                ->get();

            $users = $users->concat($otherUsers);
        } else {
            $users = $query->orderByDesc('total_points')->get();
        }

        return view('leaderboard.index', compact('users', 'searchId'));
    }
    public function recalculate()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->total_points = Activity::where('user_id', $user->id)->sum('points');
            $user->save();
        }

        $this->updateRanks();

        return redirect()->route('leaderboard.index')->with('success', 'Leaderboard recalculated successfully.');
    }

    private function updateRanks()
    {
        $users = User::orderByDesc('total_points')->get();
        $rank = 1;
        $prevPoints = null;

        foreach ($users as $index => $user) {
            if ($prevPoints !== null && $user->total_points < $prevPoints) {
                $rank = $index + 1;
            }
            $user->rank = $rank;
            $user->save();
            $prevPoints = $user->total_points;
        }
    }
}
