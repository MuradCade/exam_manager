<?php

namespace App\Http\Controllers\readonly;

class ReadonlyController
{

    public function index()
    {
        return view('readonly.dashboard');
    }
}
