<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allproduct_entries = Product::select('product.*','category.title as category_title','child_category.title as child_category_title', 'users.firstname as product_username', 'users.role as product_username_role')
        ->leftJoin('users','users.id','=','product.user_id')
        ->leftJoin('category','category.id','=','product.category_id')
        ->leftJoin('category as child_category','child_category.id','=','product.child_category_id');

        if (Auth::user()->role === "vendor") {
            $allproduct_entries = $allproduct_entries->where('product.user_id', Auth::user()->id)->get();
        }
        else {
            $allproduct_entries = $allproduct_entries->get();
        }

        /* $all = Product::with('category')->get();
        foreach ($all as $a){
            dd($a->category->title);
        } */

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
            'category_id' => 'required|exists:category,id',
            'title' => 'required|string|min:2|max:255',
            'sku' => 'required|string|min:8|max:20',
            'quantity' => 'required|numeric|between:0,100',
            'price' => 'required|numeric|between:1,100000'
        ];

        if (Auth::user()->role === "admin") {
            $rules['status'] = 'required|in:active,inactive';  // Validation rule for enum
        }

        if ($request->filled('description')) {
            $rules['description'] = 'required|string|min:2';
        }

        if ($request->filled('child_category_id')) {
            $rules['child_category_id'] = 'required|exists:category,id';
        }

        if ($request->file('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        }
        
        if ($request->file('multi_image')) {
            $rules['multi_image.*'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        }

        $messages = [
            'description.min' => 'The description must be at least 2 characters.',
            'image.mimes' => 'The image must be a file of type: JPEG, PNG or JPG.',
            'image.max' => 'The image may not be greater than 1 MB.',
            'multi_image.*.mimes' => 'Each image must be a file of type: JPEG, PNG, or JPG.',
            'multi_image.*.max' => 'Each image may not be greater than 1 MB.'
        ];

        $data = $request->validate($rules, $messages);

        if (Auth::user()->role !== "admin") {
            $data['status'] = "inactive";
        }

        if (!empty($data['image'])) {
            $img_fileName = time()."_img.".$data['image']->getClientOriginalExtension();
            $request->file('image')->storeAs('public/upload/image', $img_fileName);

            $img_pathName = "upload/image/".$img_fileName;
            $data['image'] = $img_pathName;
        }

        if (!empty($data['multi_image'])) {
            $multi_img_fileName = [];
            $i = 1;
            foreach ($request->file('multi_image') as $multi_image)
            {
                $setmulti_img_fileName = time().$i++."_img.".$multi_image->getClientOriginalExtension();
                $multi_image->storeAs('public/upload/multiple_images', $setmulti_img_fileName);
                $multi_img_fileName[] = $setmulti_img_fileName;
            }
            $data['multi_image'] = implode(', ', $multi_img_fileName);
        }

        $data['user_id'] = Auth::user()->id;

        Product::create($data);
        Session::flash('success', 'New Product created successfully.');

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
        $product_entries = Product::select('product.*','category.title as category_title','child_category.title as child_category_title', 'users.firstname as product_username', 'users.role as product_username_role')
        ->leftJoin('users','users.id','=','product.user_id')
        ->leftJoin('category','category.id','=','product.category_id')
        ->leftJoin('category as child_category','child_category.id','=','product.child_category_id')
        ->where('product.id',$id)->first();

        if ($product_entries->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            return view('product.show', compact('product_entries'));
        }
        else {
            Session::flash('error', 'You are not authorized to show this Product.');
            return redirect()->route('product.list');
        }
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

        $product_entries = Product::select('product.*','category.title as category_title','child_category.title as child_category_title', 'users.firstname as product_username', 'users.role as product_username_role')
        ->leftJoin('users','users.id','=','product.user_id')
        ->leftJoin('category','category.id','=','product.category_id')
        ->leftJoin('category as child_category','child_category.id','=','product.child_category_id')
        ->where('product.id',$id)->first();

        if ($product_entries->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            return view('product.edit', compact('active_category','product_entries'));
        }
        else {
            Session::flash('error', 'You are not authorized to edit this Product.');
            return redirect()->route('product.list');
        }
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
            'sku' => 'required|string|min:8|max:20',
            'quantity' => 'required|numeric|between:0,100',
            'price' => 'required|numeric|between:10,100000'
        ];

        $messages = [
            'description.min' => 'The description must be at least 2 characters.',
            'image.mimes' => 'The image must be a file of type: JPEG, PNG or JPG.',
            'image.max' => 'The image may not be greater than 1 MB.',
            'multi_image.*.mimes' => 'Each image must be a file of type: JPEG, PNG, or JPG.',
            'multi_image.*.max' => 'Each image may not be greater than 1 MB.'
        ];

        if (Auth::user()->role === "admin") {
            $rules['status'] = 'required|in:active,inactive';
        }

        if ($request->filled('description')) {
            $rules['description'] = 'required|string|min:2';
        }

        if ($request->filled('child_category_id')) {
            $rules['child_category_id'] = 'required|exists:category,id';
        }

        if ($request->file('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        }

        if ($request->file('multi_image')) {
            $rules['multi_image.*'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        }

        $data = $request->validate($rules, $messages);

        if (isset($data['image'])) {
            $img_fileName = time()."_img.".$data['image']->getClientOriginalExtension();
            $request->file('image')->storeAs('public/upload/image', $img_fileName);

            $img_pathName = "upload/image/".$img_fileName;
            $data['image'] = $img_pathName;
        }

        if (!empty($data['multi_image'])) {
            $multi_img_fileName = [];
            $i = 1;
            foreach ($request->file('multi_image') as $multi_image)
            {
                $setmulti_img_fileName = time().$i++."_img.".$multi_image->getClientOriginalExtension();
                $multi_image->storeAs('public/upload/multiple_images', $setmulti_img_fileName);
                $multi_img_fileName[] = $setmulti_img_fileName;
            }
            $new_imgname = implode(', ', $multi_img_fileName);
            $singale_product_info = Product::findOrFail($id);

            if ($singale_product_info->multi_image === null) {
                $data['multi_image'] = $new_imgname;
            } else {
                $data['multi_image'] = $singale_product_info->multi_image.", ".$new_imgname;
            }
        }

        $singale_info = Product::findOrFail($id);

        $singale_info->category_id = $data['category_id'];
        $singale_info->title = $data['title'];
        $singale_info->sku = $data['sku'];
        $singale_info->quantity = $data['quantity'];
        $singale_info->price = $data['price'];

        $singale_info->status = Auth::user()->role !== "admin" ? 'inactive' : $data['status'];

        if (!empty($data['description'])) {
            $singale_info->description = $data['description'];
        }

        if (!empty($data['child_category_id'])) {
            $singale_info->child_category_id = $data['child_category_id'];
        }

        if (!empty($data['image'])) {
            $singale_info->image = $data['image'];
        }

        if (!empty($data['multi_image'])) {
            $singale_info->multi_image = $data['multi_image'];
        }

        $singale_info->save();

        Session::flash('success', 'Product details update successfully.');
        return redirect()->route('product.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeimg($id, $img_name = null)
    {
        $singale_info = Product::findOrFail($id);

        if ($singale_info->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            if ($img_name === null) 
            {
                if (Storage::disk('public')->exists($singale_info->image))
                {
                    Storage::disk('public')->delete($singale_info->image);
                    $singale_info->image = null;
                    $singale_info->status = Auth::user()->role === "vendor" ? 'inactive' : $singale_info->status;
                    $singale_info->save();
                }
                Session::flash('success', 'Product image delete successfully.');
                return redirect()->route('product.list');
            }
            else
            {
                if (Storage::disk('public')->exists("upload/multiple_images/".$img_name))
                {
                    Storage::disk('public')->delete("upload/multiple_images/".$img_name);
                    $multi_image_name = explode(', ', $singale_info->multi_image);
                    $multi_img_fileName = [];
                    
                    foreach ($multi_image_name as $multi_img_name) {
                        if ($multi_img_name !== $img_name) {
                            $multi_img_fileName[] = $multi_img_name;
                        }
                    }
                    $multi_imgname = implode(', ', $multi_img_fileName);
                    $singale_info->multi_image = $multi_imgname;
                    $singale_info->status = Auth::user()->role === "vendor" ? 'inactive' : $singale_info->status;
                    $singale_info->save();
                }
                Session::flash('success', 'Side Product Image delete successfully.');
                return redirect()->route('product.edit', $id);
            }
        }
        else {
            Session::flash('error', 'You are not authorized to remove this Product image.');
            return redirect()->route('product.list');
        }
    }

    public function multiremoveimg(Request $request)
    {
        $singale_info = Product::findOrFail($request->input('id'));
        if ($request->has('imgNames')) {

            $remove_multi_image = $request->input('imgNames');
            foreach ($remove_multi_image as $remove_image) {

                if (Storage::disk('public')->exists("upload/multiple_images/".$remove_image)) {
                    Storage::disk('public')->delete("upload/multiple_images/".$remove_image);
                    $multi_image_name = explode(', ', $singale_info->multi_image);

                    $multi_imgname = array_filter($multi_image_name, function ($multi_img_name) use ($remove_image) {
                        return $multi_img_name !== $remove_image;
                    });

                    $singale_info->multi_image = empty($multi_imgname) ? null : implode(', ', $multi_imgname);
                    $singale_info->status = Auth::user()->role === "vendor" ? 'inactive' : $singale_info->status;
                    $singale_info->save();
                }
            }
            return response()->json(['status' => 'Side Product Images delete successfully.']);
        }
        else {
            return response()->json(['error' => 'Please select images to delete!'], 403);
        }
    }

    public function status($id)
    {
        $singale_info = Product::findOrFail($id);

        if ($singale_info->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            $singale_info->status = $singale_info->status === 'active' ? 'inactive' : 'active';

            $singale_info->save();
            return response()->json(['status' => $singale_info->status]);
        }
        else {
            return response()->json(['error' => 'You are not authorized to active or inactive this Product.'], 403);
        }
    }

    public function childcategory(Request $request)
    {
        $category_id = $request->input('category_id');

        $child_category = Category::where('status', 'active')
        ->where('parent_id', $category_id)->get();

        return response()->json(['status' => $child_category]);
    }

    public function export(Request $request)
    {
        $user_id = Auth::user()->id;
        $user_role = Auth::user()->role;

        return Excel::download(new ProductExport($user_id, $user_role), 'products.csv');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $data = $request->file('csv_file');

        $user_id = Auth::user()->id;
        $user_role = Auth::user()->role;

        $import = new ProductImport($user_id, $user_role);

        try {
            Excel::import($import, $data);

            $skippedRowNumbers = $import->getSkippedRowNumbers();
            if (!empty($skippedRowNumbers)) {
                Session::flash('error', 'The following rows were skipped because they did not meet the necessary criteria: '.implode(', ', $skippedRowNumbers).'. Please review and try again.');
            }

            $getDuplicateRowNumbers = $import->getDuplicateRowNumbers();
            if (!empty($getDuplicateRowNumbers)) {
                Session::flash('alert', 'The following rows contain duplicate entries and were not imported: '.implode(', ', $getDuplicateRowNumbers).'.');
            }

            Session::flash('success', 'All Products are import successfully.');
            // return redirect()->route('product.list');
        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            dd($failures);
            foreach ($failures as $failure) {

                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }

            // Handle validation failures
            // return back()->withFailures($failures);
            // return back()->withErrors(['import_errors' => $failures])->withInput();
            // return Redirect::back()->withErrors($failures)->withInput();
            return redirect()->back()
            ->withErrors(['import_errors' => $failures])
            ->withInput();
        }
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

        if ($data->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            $data->delete();
            Session::flash('success', 'Product move to trash successfully.');
        }
        else {
            Session::flash('error', 'You are not authorized to delete this Product.');
        }

        return redirect()->route('product.list');
    }
}
