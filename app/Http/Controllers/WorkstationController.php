<?php

namespace App\Http\Controllers;

use App\Models\Workstation;
use App\Models\Component;
use App\Models\Location;
use App\Models\ConfigHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkstationController extends Controller
{
    public function index()
    {
        $workstations = Workstation::with('location')
            ->withCount(['components as current_components_count' => function ($query) {
                $query->whereNull('removed_at');
            }])
            ->orderBy('name')
            ->paginate(20);

        $statuses = Workstation::getStatuses();

        return view('workstations.index', compact('workstations', 'statuses'));
    }

    public function create()
    {
        $statuses = Workstation::getStatuses();
        $locations = Location::orderBy('name')->get();

        return view('workstations.create', compact('statuses', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'inventory_number' => 'required|string|max:255|unique:workstations',
            'location_id' => 'nullable|exists:locations,id',  // Добавляем location_id
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Workstation::create($validated);

        return redirect()->route('workstations.index')
            ->with('success', 'Рабочая станция успешно добавлена.');
    }

    public function show(Workstation $workstation)
    {
        $workstation->load(['location', 'currentComponents', 'configHistory' => function ($query) {
            $query->with('user')->latest()->take(20);
        }]);

        $availableComponents = Component::where('status', 'in_stock')
            ->orderBy('type')
            ->orderBy('name')
            ->get();  // Получаем все компоненты на складе

        $componentTypes = Component::getTypes();
        $statuses = Workstation::getStatuses();

        return view('workstations.show', compact(
            'workstation',
            'availableComponents',
            'componentTypes',
            'statuses'
        ));
    }

    public function edit(Workstation $workstation)
    {
        $statuses = Workstation::getStatuses();
        $locations = Location::orderBy('name')->get();

        return view('workstations.edit', compact('workstation', 'statuses', 'locations'));
    }

    public function update(Request $request, Workstation $workstation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'inventory_number' => 'required|string|max:255|unique:workstations,inventory_number,' . $workstation->id,
            'location_id' => 'nullable|exists:locations,id',  // Добавляем location_id
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $workstation->update($validated);

        return redirect()->route('workstations.index')
            ->with('success', 'Рабочая станция успешно обновлена.');
    }

    public function destroy(Workstation $workstation)
    {
        if ($workstation->currentComponents()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить рабочую станцию с установленными комплектующими.');
        }

        $workstation->delete();

        return redirect()->route('workstations.index')
            ->with('success', 'Рабочая станция успешно удалена.');
    }

    public function compare(Workstation $workstation)
    {
        $initial = $workstation->initial_config ?? [];
        $current = $workstation->current_config;
        $statuses = Workstation::getStatuses();

        return view('workstations.compare', compact('workstation', 'initial', 'current', 'statuses'));
    }

    public function saveInitialConfig(Request $request, Workstation $workstation)
    {
        $request->validate([
            'config' => 'required|json',
        ]);

        $workstation->initial_config = json_decode($request->config, true);
        $workstation->save();

        return redirect()->back()
            ->with('success', 'Первоначальная конфигурация сохранена.');
    }

    public function history(Workstation $workstation)
    {
        $history = ConfigHistory::with('user')
            ->where('workstation_id', $workstation->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('workstations.history', compact('workstation', 'history'));
    }

    public function changeStatus(Request $request, Workstation $workstation)
    {
        $request->validate([
            'status' => 'required|in:active,maintenance,decommissioned',
        ]);

        $workstation->status = $request->status;
        $workstation->save();

        return redirect()->back()
            ->with('success', 'Статус успешно изменен.');
    }
}
