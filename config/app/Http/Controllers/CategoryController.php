<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_entries = Category::all();
        return view('category.index', compact('all_entries'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trashrecord()
    {
        $all_entries = Category::all();
        $trash_category = Category::onlyTrashed()->get();
        return view('trash', compact('trash_category', 'all_entries'));
    }

    public function restore($id)
    {
        $restore_category = Category::withTrashed()->findOrFail($id);
        $restore_category->restore();
        return redirect()->route('category.trash');
    }

    public function delete($id)
    {
        $delete_category = Category::withTrashed()->findOrFail($id);
        $delete_category->forceDelete();
        return redirect()->route('category.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active_entries = Category::where('status', 'active')->get();
        return view('category.create', compact('active_entries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'parent_id' => '',
            'title' => 'required|string|min:2|max:50',
            'status' => 'required|in:active,inactive'  // Validation rule for enum
        ];

        $data = $request->validate($rules);
        Category::create($data);

        return redirect()->route('category.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $singale_entry = Category::findOrFail($id);
        $parent_category_name = null;

        if (!empty($singale_entry->parent_id)) {
            $parent_category_name = Category::where('id', $singale_entry->parent_id)->first();
        }

        return view('category.show', compact('singale_entry', 'parent_category_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $all_entries = Category::all();
        $singale_entry = Category::findOrFail($id);
        return view('category.edit', compact('singale_entry', 'all_entries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'parent_id' => '',
            'title' => 'required|string|min:2|max:50',
            'status' => 'required|in:active,inactive',
        ];

        $data = $request->validate($rules);

        $singale_info = Category::findOrFail($id);

        $singale_info->parent_id = $data['parent_id'];
        $singale_info->title = $data['title'];
        $singale_info->status = $data['status'];

        $singale_info->save();
        return redirect()->route('category.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Category::findOrFail($id);
        $data->delete();
        return redirect()->route('category.list');
    }
}
