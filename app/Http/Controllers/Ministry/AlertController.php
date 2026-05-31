<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Alert;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::with(['graduate', 'type'])->latest()->limit(20)->get();

        return view('ministry.alerts', compact('alerts'));
    }
}
