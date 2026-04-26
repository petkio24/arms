<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Workstation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComponentController extends Controller
{
    public function index(Request $request)
    {
        $query = Component::query();

        // Фильтр по типу
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Поиск по названию, инвентарному номеру, серийному номеру
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('inventory_number', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('manufacturer', 'like', "%{$search}%");
            });
        }

        // Сортировка
        $sortBy = $request->get('sort_by', 'type');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Количество на странице
        $perPage = $request->get('per_page', 20);
        $components = $query->paginate($perPage)->withQueryString();

        $types = Component::getTypes();
        $statuses = Component::getStatuses();

        return view('components.index', compact('components', 'types', 'statuses'));
    }

    public function create()
    {
        $types = Component::getTypes();
        $statuses = Component::getStatuses();

        return view('components.create', compact('types', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'required|string|max:255|unique:components',
            'inventory_number' => 'required|string|max:255|unique:components',
            'manufacturer' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'purchase_date' => 'required|date',
            'status' => 'required|string',
            'socket' => 'nullable|string',
            'ram_type' => 'nullable|string',
            'form_factor' => 'nullable|string',
            'power' => 'nullable|integer',
        ]);

        Component::create($validated);

        return redirect()->route('components.index')
            ->with('success', 'Комплектующее успешно добавлено.');
    }

    public function show(Component $component)
    {
        $component->load(['workstations' => function ($query) {
            $query->orderBy('installed_at', 'desc');
        }]);

        $types = Component::getTypes();
        $statuses = Component::getStatuses();
        $workstations = Workstation::where('status', 'active')->get();

        return view('components.show', compact('component', 'types', 'statuses', 'workstations'));
    }

    public function edit(Component $component)
    {
        $types = Component::getTypes();
        $statuses = Component::getStatuses();

        return view('components.edit', compact('component', 'types', 'statuses'));
    }

    public function update(Request $request, Component $component)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'required|string|max:255|unique:components,serial_number,' . $component->id,
            'inventory_number' => 'required|string|max:255|unique:components,inventory_number,' . $component->id,
            'manufacturer' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'purchase_date' => 'required|date',
            'status' => 'required|string',
            'socket' => 'nullable|string',
            'ram_type' => 'nullable|string',
            'form_factor' => 'nullable|string',
            'power' => 'nullable|integer',
        ]);

        $component->update($validated);

        return redirect()->route('components.index')
            ->with('success', 'Комплектующее успешно обновлено.');
    }

    public function destroy(Component $component)
    {
        if ($component->current_workstation) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить комплектующее, которое установлено в рабочую станцию.');
        }

        $component->delete();

        return redirect()->route('components.index')
            ->with('success', 'Комплектующее успешно удалено.');
    }

    public function install(Request $request, Component $component)
    {
        $validated = $request->validate([
            'workstation_id' => 'required|exists:workstations,id',
            'notes' => 'nullable|string',
        ]);

        if ($component->status !== 'in_stock') {
            return redirect()->back()
                ->with('error', 'Комплектующее недоступно для установки.');
        }

        $workstation = Workstation::find($validated['workstation_id']);

        $compatibility = $workstation->checkComponentCompatibility($component);

        if (!$compatibility['compatible']) {
            return redirect()->back()
                ->with('error', 'Невозможно установить компонент: ' . implode(', ', $compatibility['errors']));
        }

        DB::transaction(function () use ($component, $workstation, $validated) {
            $workstation->components()->attach($component->id, [
                'installed_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            $component->status = 'installed';
            $component->save();

            \App\Models\ConfigHistory::create([
                'workstation_id' => $workstation->id,
                'change_description' => "Установлен компонент: {$component->name} ({$component->inventory_number})",
                'components_before' => $workstation->current_config,
                'components_after' => $workstation->fresh()->current_config,
                'change_type' => 'assembly',
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->back()
            ->with('success', 'Комплектующее успешно установлено.');
    }

    public function remove(Request $request, Component $component)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $currentWorkstation = $component->current_workstation;

        if (!$currentWorkstation) {
            return redirect()->back()
                ->with('error', 'Комплектующее не установлено.');
        }

        DB::transaction(function () use ($component, $currentWorkstation, $validated) {
            DB::table('workstation_components')
                ->where('workstation_id', $currentWorkstation->id)
                ->where('component_id', $component->id)
                ->whereNull('removed_at')
                ->update([
                    'removed_at' => now(),
                    'notes' => $validated['notes'] ?? 'Удалено',
                ]);

            $component->status = 'in_stock';
            $component->save();

            \App\Models\ConfigHistory::create([
                'workstation_id' => $currentWorkstation->id,
                'change_description' => "Удален компонент: {$component->name} ({$component->inventory_number})" . ($validated['notes'] ? " - {$validated['notes']}" : ''),
                'components_before' => $currentWorkstation->current_config,
                'components_after' => Workstation::find($currentWorkstation->id)->current_config,
                'change_type' => 'replacement',
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->back()
            ->with('success', 'Комплектующее успешно удалено.');
    }

    public function checkCompatibility(Request $request, Component $component)
    {
        $workstationId = $request->get('workstation_id');
        $workstation = Workstation::find($workstationId);

        if (!$workstation) {
            return response()->json([
                'compatible' => false,
                'errors' => ['Рабочая станция не найдена']
            ]);
        }

        $result = $workstation->checkComponentCompatibility($component);
        return response()->json($result);
    }
}
