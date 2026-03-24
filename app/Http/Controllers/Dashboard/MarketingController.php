<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Domains\Marketing\Models\Campaign;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function index()
    {
        return view('tyro-dashboard::marketing.index');
    }

    public function create()
    {
        return view('tyro-dashboard::marketing.create');
    }

    public function show(Campaign $campaign)
    {
        return view('tyro-dashboard::marketing.show', compact('campaign'));
    }

    public function templates()
    {
        return view('tyro-dashboard::marketing.templates');
    }

    public function settings()
    {
        return view('tyro-dashboard::marketing.settings');
    }
}
