<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Kreait\Firebase\Factory;
use Google\Cloud\Core\Timestamp;

class HomeController extends Controller
{


    public function index(Request $request)
    {
        session(['tab' => "dashboard"]);

        // $selectedYear = $request->input('year', now()->year);

        // $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
        // $db = $firestore->database();
        // $usersSnap = $db->collection('users')->documents();

        // $totalUsers = 0;
        // $activeUsers = 0;
        // $blockedUsers = 0;

        // $signupCounts = array_fill(0, 12, 0); // 0 = Jan, 11 = Dec

        // foreach ($usersSnap as $userDoc) {
        //     $data = $userDoc->data();
        //     $totalUsers++;

        //     if (($data['status'] ?? null) == 1) $activeUsers++;
        //     elseif (($data['status'] ?? null) == 0) $blockedUsers++;

        //     if (!empty($data['createdOn']) && $data['createdOn'] instanceof Timestamp) {
        //         $createdAt = Carbon::parse($data['createdOn']->formatAsString());

        //         if ($createdAt->year == $selectedYear) {
        //             $signupCounts[$createdAt->month - 1]++; // Month starts from 1, array index from 0
        //         }
        //     }
        // }

        // return view('home', [
        //     'totalUsers' => $totalUsers,
        //     'activeUsers' => $activeUsers,
        //     'blockedUsers' => $blockedUsers,
        //     'signupCounts' => $signupCounts,
        //     'selectedYear' => $selectedYear
        // ]);
        return view('home', [
            'totalUsers' => 220,
            'activeUsers' => 180,
            'blockedUsers' => 40,
            'signupCounts' => [12, 9, 5, 7, 11, 3, 6, 4, 10, 8, 6, 2],
            'earningsCounts' => [100, 120, 80, 150, 130, 200, 170, 140, 160, 110, 90, 180],
            'selectedYear' => now()->year
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
