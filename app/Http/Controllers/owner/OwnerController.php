<?php

namespace App\Http\Controllers\Owner;

class OwnerController
{

    public function index()
    {

        return view('owner.dashboard');
    }



    public function settingpage()
    {
        return view('owner.setting');
    }
}
