<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Workstation;
use App\Models\ConfigHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_workstations' => Workstation::count(),
            'active_workstations' => Workstation::where('status', 'active')->count(),
            'total_components' => Component::count(),
            'in_stock_components' => Component::where('status', 'in_stock')->count(),
        ];

        $recentChanges = ConfigHistory::with(['workstation', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $workstations = Workstation::withCount('currentComponents')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentChanges', 'workstations'));
    }
}
