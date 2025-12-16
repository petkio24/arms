<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'children'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно добавлена.');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'components' => function ($query) {
            $query->orderBy('name');
        }]);

        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Нельзя сделать категорию родителем самой себе
        if ($validated['parent_id'] == $category->id) {
            return redirect()->back()
                ->with('error', 'Категория не может быть родителем самой себе.');
        }

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно обновлена.');
    }

    public function destroy(Category $category)
    {
        // Проверяем, нет ли дочерних категорий
        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить категорию с дочерними категориями.');
        }

        // Проверяем, нет ли комплектующих в этой категории
        if ($category->components()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить категорию, в которой есть комплектующие.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно удалена.');
    }
}
