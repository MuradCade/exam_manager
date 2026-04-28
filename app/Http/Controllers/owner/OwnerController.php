<?php

namespace App\Http\Controllers\Owner;

use App\Models\examform;
use App\Models\participants;
use Illuminate\Support\Facades\Auth;

class OwnerController
{

    public function index()
    {
        // fetch (sum created examform), (sum active examform) , (sum disabled examform)
        $user = Auth::user();

        $user->loadCount([
            'examforms as total_exams', // Total count
            'examforms as active_exams' => function ($query) {
                $query->where('status', 'active');
            },
            'examforms as disabled_exams' => function ($query) {
                $query->where('status', 'disabled');
            }
        ]);

        // latest participants that submitted exams
        // Added ->take(5)->get() to get the actual records
        $participants = participants::with('examForm')
            ->whereHas('examForm', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', ['user' => $user, 'participants' => $participants]);
    }



    public function settingpage()
    {
        return view('owner.setting');
    }
}
