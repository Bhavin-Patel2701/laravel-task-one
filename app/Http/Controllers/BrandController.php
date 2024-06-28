<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search === null) {
            $allbrand_entries = Brand::paginate(5);
        }
        else {
            $allbrand_entries = Brand::where('title', 'LIKE', "%{$search}%")
            ->orWhere('status', 'LIKE', "%{$search}%")->paginate(5);
        }

        return view('brand.index', compact('allbrand_entries', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'title' => 'required|string|min:2|max:255',
            'status' => 'required|in:active,inactive'
        ];

        $data = $request->validate($rules);

        Brand::create($data);

        Session::flash('success', 'New Brand created successfully.');

        return redirect()->route('brand.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand_entries = Brand::findOrFail($id);

        return view('brand.show', compact('brand_entries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand_entries = Brand::findOrFail($id);

        return view('brand.edit', compact('brand_entries'));
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
            'title' => 'required|string|min:2|max:255',
            'status' => 'required|in:active,inactive'
        ];

        $data = $request->validate($rules);

        $singale_info = Brand::findOrFail($id);

        $singale_info->title = $data['title'];
        $singale_info->status = $data['status'];

        $singale_info->save();

        Session::flash('success', 'Brand details update successfully.');

        return redirect()->route('brand.list');
    }

    public function status($id)
    {
        $singale_info = Brand::findOrFail($id);
        $singale_info->status = $singale_info->status === 'active' ? 'inactive' : 'active';
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
        $data = Brand::findOrFail($id);
        $data->delete();

        Session::flash('success', 'Brand move to trash successfully.');

        return redirect()->route('brand.list');
    }
}
