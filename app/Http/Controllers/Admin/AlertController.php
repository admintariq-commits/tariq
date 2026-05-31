<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::with(['graduate', 'type'])->orderBy('created_at', 'desc')->get();
        return view('admin.alerts', compact('alerts'));
    }

    public function send(Alert $alert)
    {
        $alert->update([
            'status' => 'disabled',
            'response_message' => 'Integration disabled. To enable, set MINISTRY_API_ENABLED=true in .env'
        ]);
        
        return redirect()->back()->with('info', 'Alert marked as disabled. Integration not active.');
    }
}