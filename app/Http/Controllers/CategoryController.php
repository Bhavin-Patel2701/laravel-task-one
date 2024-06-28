<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_entries = Category::select('category.*', 'parent.title as parent_category')
        ->leftJoin('category as parent', 'category.parent_id', '=', 'parent.id')->get();

        return view('category.index', compact('all_entries'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trashrecord()
    {
        // $trash_category = Category::select('category.*', 'parent.title as parent_category')
        // ->leftJoin('category as parent', 'category.parent_id', '=', 'parent.id')
        // ->onlyTrashed()->get();

        $category = Category::select('category.*', 'parent.title as parent_category')
        ->leftJoin('category as parent', 'category.parent_id', '=', 'parent.id')->get();

        return view('trash', compact('category'));
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
            'title' => 'required|string|min:2|max:50',
            'status' => 'required|in:active,inactive'  // Validation rule for enum
        ];

        if ($request->filled('parent_id')) {
            $rules['parent_id'] = 'required|exists:category,id';
        }

        $data = $request->validate($rules);
        Category::create($data);

        Session::flash('success', 'New Category created successfully.');

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
        $singale_entry = Category::select('category.*', 'parent.title as parent_category')
        ->leftJoin('category as parent', 'category.parent_id', '=', 'parent.id')
        ->where('category.id', $id)->first();

        return view('category.show', compact('singale_entry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $singale_entry = Category::findOrFail($id);
        $all_entries = Category::where('id', '!=', $id)->where('status', 'active')->get();

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
            'title' => 'required|string|min:2|max:50',
            'status' => 'required|in:active,inactive',
        ];

        if ($request->filled('parent_id')) {
            if ($request->input('parent_id') === "remove") {
                $rules['parent_id'] = 'required';
            } else {
                $rules['parent_id'] = 'required|exists:category,id';
            }
        }

        $data = $request->validate($rules);

        if ($data['status'] === "inactive") {
            $child_category = Category::where('parent_id', $id)->get();
            
            foreach ($child_category as $category)
            {
                $category->status = "inactive";
                $category->save();
            }

            $product_category = Product::where('category_id', $id)->get();
            
            foreach ($product_category as $product_cat)
            {
                $product_cat->category_id = null;
                $product_cat->status = "inactive";
                $product_cat->save();
            }
        }

        $singale_info = Category::findOrFail($id);

        if ($request->input('parent_id') === "remove") {
            $singale_info->parent_id = null;
        } elseif ($request->filled('parent_id')) {
            $singale_info->parent_id = $data['parent_id'];
        }

        $singale_info->title = $data['title'];
        $singale_info->status = $data['status'];
        $singale_info->save();

        Session::flash('success', 'Category details update successfully.');

        return redirect()->route('category.list');
    }

    public function status($id)
    {
        $singale_info = Category::findOrFail($id);
        $singale_info->status = $singale_info->status === 'active' ? 'inactive' : 'active';

        if ($singale_info->status === "inactive") {
            $product_category = Product::where('category_id', $id)->get();

            foreach ($product_category as $product_cat)
            {
                $product_cat->category_id = null;
                $product_cat->status = "inactive";
                $product_cat->save();
            }
        }
        $singale_info->save();

        return response()->json(['status' => $singale_info->status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $child_category = Category::where('parent_id', $id)->get();

        foreach ($child_category as $category)
        {
            $category->parent_id = null;
            $category->status = "inactive";
            $category->save();
        }

        $data = Category::findOrFail($id);
        $data->delete();

        Session::flash('success', 'Category move to trash successfully.');

        return redirect()->route('category.list');
    }
}
