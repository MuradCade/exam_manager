<?php

namespace App\Http\Controllers\Readonly;

class ReadonlyController
{

    public function index()
    {
        return view('readonly.dashboard');
    }
}
