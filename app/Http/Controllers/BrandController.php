<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

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

        /* if (Auth::user()->role === "vendor") {
            if ($search === null) {
                // $allbrand_entries = Brand::where('user_id', Auth::user()->id)
                // ->paginate(5);
                $allbrand_entries = Brand::where('status', 'active')
                ->orWhere(function($query) {
                    $query->where('status', 'inactive')
                          ->where('user_id', Auth::user()->id);
                })->paginate(5);
            }
            else {
                $allbrand_entries = Brand::where('user_id', Auth::user()->id)
                ->where('title', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")->paginate(5);
            }
        }
         */

        if (Auth::user()->role === "vendor") {
            $allbrand_entries = Brand::where(function($query) use ($search) {
                if ($search === null) {
                    $query->where('status', 'active')->orWhere(function($query) {
                        $query->where('status', 'inactive')
                        ->where('user_id', Auth::user()->id);
                    });
                } else {
                    $query->where(function($query) use ($search) {
                        $query->where('user_id', Auth::user()->id)
                        ->where('title', 'LIKE', "%{$search}%");
                    })
                    ->orWhere(function($query) use ($search) {
                        $query->where('user_id', Auth::user()->id)
                        ->where('status', 'LIKE', "%{$search}%");
                    })
                    ->orWhere(function($query) use ($search) {
                        $query->where('status', 'active')
                        ->where('title', 'LIKE', "%{$search}%");
                    });
                }
            })->paginate(5);
        } else {
            if ($search === null) {
                $allbrand_entries = Brand::paginate(5);
            }
            else {
                $allbrand_entries = Brand::where('title', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")->paginate(5);
            }
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
            'title' => 'required|string|min:2|max:255'
        ];

        if (Auth::user()->role === "admin") {
            $rules['status'] = 'required|in:active,inactive';  // Validation rule for enum
        }

        $data = $request->validate($rules);

        if (Auth::user()->role !== "admin") {
            $data['status'] = "inactive";
        }

        $data['user_id'] = Auth::user()->id;

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

        if ($brand_entries->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            return view('brand.show', compact('brand_entries'));
        }
        else {
            Session::flash('error', 'You are not authorized to show this Brand.');
            return redirect()->route('brand.list');
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
        $brand_entries = Brand::findOrFail($id);

        if ($brand_entries->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            return view('brand.edit', compact('brand_entries'));
        }
        else {
            Session::flash('error', 'You are not authorized to edit this Brand.');
            return redirect()->route('brand.list');
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
        if ($singale_info->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            $singale_info->status = $singale_info->status === 'active' ? 'inactive' : 'active';
            $singale_info->save();
            return response()->json(['status' => $singale_info->status]);
        }
        else {
            return response()->json(['error' => 'You are not authorized to active or inactive this Brand.'], 403);
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
        $data = Brand::findOrFail($id);

        if ($data->user_id === Auth::user()->id && Auth::user()->role === "vendor" || Auth::user()->role === "admin") {
            $data->delete();
            Session::flash('success', 'Brand move to trash successfully.');
            return redirect()->route('brand.list');
        }
        else {
            Session::flash('error', 'You are not authorized to delete this Brand.');
            return redirect()->route('brand.list');
        }
    }
}
