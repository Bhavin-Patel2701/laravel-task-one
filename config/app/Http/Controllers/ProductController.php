<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allproduct_entries = Product::select('product.*','category.title as category_title')
        ->leftJoin('category','category.id','=','product.category_id')->get();

        return view('product.index', compact('allproduct_entries'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trashrecord()
    {
        $trash_product = Product::select('product.*','category.title as category_title')
        ->leftJoin('category','category.id','=','product.category_id')
        ->onlyTrashed()->get();

        return view('trash', compact('trash_product'));
    }
    public function restore($id)
    {
        $restore_product = Product::select('product.*','category.title as category_title')
        ->leftJoin('category','category.id','=','product.category_id')
        ->withTrashed()->findOrFail($id);
        $restore_product->restore();

        return redirect()->route('product.trash');
    }
    public function delete($id)
    {
        $delete_product = Product::select('product.*','category.title as category_title')
        ->leftJoin('category','category.id','=','product.category_id')
        ->withTrashed()->findOrFail($id);
        $delete_product->forceDelete();

        return redirect()->route('product.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active_category = Category::where('status', 'active')->get();
        return view('product.create', compact('active_category'));
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
            'category_id' => 'required',
            'title' => 'required|string|min:2|max:255',
            'status' => 'required|in:active,inactive',
            'quantity' => 'required|numeric|between:1,100',
            'price' => 'required|numeric|between:10,100000'
        ];

        $messages = [
            'description.min' => 'The description must be at least 2 characters.',
            'image.mimes' => 'The image must be a file of type: JPEG, PNG or JPG.',
            'image.max' => 'The image may not be greater than 1 MB.',
        ];

        if ($request->filled('description')) {
            $rules['description'] = 'required|string|min:2';
        }

        if ($request->file('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        }

        $data = $request->validate($rules, $messages);

        if (!empty($data['image'])) {
            $img_fileName = time()."_img.".$data['image']->getClientOriginalExtension();
            $request->file('image')->storeAs('public/upload/image', $img_fileName);

            $img_pathName = "upload/image/".$img_fileName;
            $data['image'] = $img_pathName;
        }

        Product::create($data);

        return redirect()->route('product.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product_entries = Product::select('product.*','category.title as category_title')
        ->leftJoin('category','category.id','=','product.category_id')
        ->where('product.id',$id)->first();

        return view('product.show', compact('product_entries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $active_category = Category::where('status', 'active')->get();

        $product_entries = Product::select('product.*','category.title as category_title')
        ->leftJoin('category','category.id','=','product.category_id')
        ->where('product.id',$id)->first();

        return view('product.edit', compact('active_category','product_entries'));
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
            'category_id' => 'required',
            'title' => 'required|string|min:2|max:255',
            'status' => 'required|in:active,inactive',
            'quantity' => 'required|numeric|between:1,100',
            'price' => 'required|numeric|between:10,100000'
        ];

        $messages = [
            'description.min' => 'The description must be at least 2 characters.',
            'image.mimes' => 'The image must be a file of type: JPEG, PNG or JPG.',
            'image.max' => 'The image may not be greater than 1 MB.',
        ];

        if ($request->filled('description')) {
            $rules['description'] = 'required|string|min:2';
        }

        if ($request->file('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        }

        $data = $request->validate($rules, $messages);

        if (isset($data['image'])) {
            $img_fileName = time()."_img.".$data['image']->getClientOriginalExtension();
            $request->file('image')->storeAs('public/upload/image', $img_fileName);

            $img_pathName = "upload/image/".$img_fileName;
            $data['image'] = $img_pathName;
        }

        $singale_info = Product::findOrFail($id);

        $singale_info->category_id = $data['category_id'];
        $singale_info->title = $data['title'];
        $singale_info->status = $data['status'];
        $singale_info->quantity = $data['quantity'];
        $singale_info->price = $data['price'];

        if (!empty($data['description'])) {
            $singale_info->description = $data['description'];
        }

        if (!empty($data['image'])) {
            $singale_info->image = $data['image'];
        }

        $singale_info->save();
        return redirect()->route('product.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeimg($id)
    {
        $singale_info = Product::findOrFail($id);

        if (Storage::disk('public')->exists($singale_info->image))
        {
            Storage::disk('public')->delete($singale_info->image);
            $singale_info->image = null;
            $singale_info->save();
        }

        return redirect()->route('product.list');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Product::findOrFail($id);
        $data->delete();
        return redirect()->route('product.list');
    }
}
