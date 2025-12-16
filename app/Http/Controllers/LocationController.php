<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('workstations')
            ->orderBy('building')
            ->orderBy('floor')
            ->orderBy('room')
            ->paginate(20);

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        $redirectTo = request()->get('redirect_to', 'locations.index');

        return view('locations.create', compact('redirectTo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'building' => 'nullable|string|max:50',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'responsible_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $location = Location::create($validated);

        // Редирект после создания
        $redirectTo = $request->get('redirect_to', 'locations.index');

        if ($redirectTo == 'workstations.create') {
            // Редирект на создание станции с новым ID помещения
            return redirect()->route('workstations.create')
                ->with('success', 'Помещение успешно добавлено.')
                ->with('new_location_id', $location->id); // Передаем ID нового помещения
        }

        if ($redirectTo == 'workstations.edit' && $request->has('workstation_id')) {
            // Редирект на редактирование станции с новым ID помещения
            return redirect()->route('workstations.edit', ['workstation' => $request->workstation_id])
                ->with('success', 'Помещение успешно добавлено.')
                ->with('new_location_id', $location->id); // Передаем ID нового помещения
        }

        // Обычный редирект на список помещений
        return redirect()->route('locations.index')
            ->with('success', 'Помещение успешно добавлено.');
    }

    public function show(Location $location)
    {
        $location->load(['workstations' => function ($query) {
            $query->withCount('currentComponents')
                ->orderBy('name');
        }]);

        return view('locations.show', compact('location'));
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'building' => 'nullable|string|max:50',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'responsible_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Помещение успешно обновлено.');
    }

    public function destroy(Location $location)
    {
        // Проверяем, нет ли рабочих станций в этом помещении
        if ($location->workstations()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить помещение, в котором есть рабочие станции.');
        }

        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Помещение успешно удалено.');
    }
}
