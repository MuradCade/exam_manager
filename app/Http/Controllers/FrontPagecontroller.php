<?php

namespace App\Http\Controllers;

use App\Models\examform;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FrontPagecontroller
{

    public function index()
    {
        return view('frontend.home');
    }

    public function studentform($exam_id)
    {
        $examid = base64_decode($exam_id);
        $examform = examform::find($examid);

        return view('frontend.exam_frontend.studentform', ['examform' => $examform]);
    }
}
