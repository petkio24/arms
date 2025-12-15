<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\ConfigHistory;
use App\Models\Workstation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComponentController extends Controller
{
    public function index()
    {
        $components = Component::with(['currentWorkstation' => function ($query) {
            $query->orderBy('pivot_installed_at', 'desc');
        }])
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(20);

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
        ]);

        Component::create($validated);

        return redirect()->route('components.index')
            ->with('success', 'Комплектующее успешно добавлено.');
    }

    public function show(Component $component)
    {
        $component->load(['workstations' => function ($query) {
            $query->orderByPivot('installed_at', 'desc');
        }]);

        return view('components.show', compact('component'));
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
        ]);

        $component->update($validated);

        return redirect()->route('components.index')
            ->with('success', 'Комплектующее успешно обновлено.');
    }

    public function destroy(Component $component)
    {
        // Проверяем, не установлено ли комплектующее
        if ($component->currentWorkstation()) {
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

        // Проверяем, что комплектующее доступно
        if ($component->status !== 'in_stock') {
            return redirect()->back()
                ->with('error', 'Комплектующее недоступно для установки.');
        }

        DB::transaction(function () use ($component, $validated) {
            $workstation = Workstation::find($validated['workstation_id']);

            // Устанавливаем комплектующее
            $workstation->components()->attach($component->id, [
                'installed_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Обновляем статус
            $component->status = 'installed';
            $component->save();

            // Записываем в историю
            ConfigHistory::create([
                'workstation_id' => $workstation->id,
                'change_description' => "Установлен компонент: {$component->name} ({$component->inventory_number})",
                'components_before' => $workstation->current_config,
                'components_after' => Workstation::find($workstation->id)->current_config,
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

        $workstation = $component->currentWorkstation();

        if (!$workstation) {
            return redirect()->back()
                ->with('error', 'Комплектующее не установлено.');
        }

        DB::transaction(function () use ($component, $workstation, $validated) {
            // Отмечаем как удаленное
            DB::table('workstation_components')
                ->where('workstation_id', $workstation->id)
                ->where('component_id', $component->id)
                ->whereNull('removed_at')
                ->update([
                    'removed_at' => now(),
                    'notes' => ($validated['notes'] ?? '') . ' (удалено)',
                ]);

            // Возвращаем на склад
            $component->status = 'in_stock';
            $component->save();

            // Записываем в историю
            ConfigHistory::create([
                'workstation_id' => $workstation->id,
                'change_description' => "Удален компонент: {$component->name} ({$component->inventory_number})",
                'components_before' => $workstation->current_config,
                'components_after' => Workstation::find($workstation->id)->current_config,
                'change_type' => 'replacement',
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->back()
            ->with('success', 'Комплектующее успешно удалено.');
    }
}
