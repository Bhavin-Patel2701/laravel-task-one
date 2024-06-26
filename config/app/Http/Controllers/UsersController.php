<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_entries = User::all();
        return view('users.index', compact('all_entries'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trashrecord()
    {
        $trash_users = User::onlyTrashed()->get();
        return view('trash', compact('trash_users'));
    }

    public function restore($id)
    {
        $restore_user = User::withTrashed()->findOrFail($id);
        $restore_user->restore();
        return redirect()->route('users.trash');
    }

    public function delete($id)
    {
        $delete_user = User::withTrashed()->findOrFail($id);
        $delete_user->forceDelete();
        return redirect()->route('users.trash');
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
        $data = $request->validate([
            'firstname' => 'required|alpha|min:2|max:50',
            'lastname' => 'required|alpha|min:2|max:50',
            'email' => 'required|email|string|min:2|max:255|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $singale_entry = User::findOrFail($id);
        return view('users.show', compact('singale_entry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $singale_entry = User::findOrFail($id);
        return view('users.edit', compact('singale_entry'));
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
        $singale_info = User::findOrFail($id);

        $rules = [
            'firstname' => 'required|alpha|min:2|max:50',
            'lastname' => 'required|alpha|min:2|max:50',
            'email' => 'required|email|string|min:2|max:255',
        ];

        if ($request->input('email') !== $singale_info->email) {
            $rules['email'] .= '|unique:users';
        }

        if ($request->filled('password') || $request->filled('confirm_password')) {
            $rules['password'] = 'required|min:8';
            $rules['confirm_password'] = 'required|min:8|same:password';
        }

        $data = $request->validate($rules);

        $singale_info->firstname = $data['firstname'];
        $singale_info->lastname = $data['lastname'];
        $singale_info->email = $data['email'];

        if (!empty($data['password'])) {
            $singale_info->password = Hash::make($data['password']);
        }

        $singale_info->save();
        return redirect()->route('users.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
        return redirect()->route('users.list');
    }
}
