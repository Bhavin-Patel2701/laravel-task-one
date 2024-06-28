<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
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
        $rules = [
            'firstname' => 'required|alpha|min:2|max:50',
            'lastname' => 'required|alpha|min:2|max:50',
            'email' => [
                'required', 'email', 'string', 'min:5', 'max:255', 'unique:users,email',
                'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,5}$/'
            ],
            'role' => 'required|in:admin,vendor,user',
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ]
        ];

        if ($request->filled('mobile_number')) {
            $rules['mobile_number'] = [ 
                'string', 'size:10', 'regex:/^[0-9]{10}$/'
            ];
        }

        $data = $request->validate($rules, [
            'password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'mobile_number.size' => 'The mobile number must be exactly 10 digits.',
            'mobile_number.regex' => 'The mobile number must contain only digits.'
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        Session::flash('success', 'New User created successfully.');

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
            'role' => 'required|in:admin,vendor,user',
            'email' => 'required|email|string|min:5|max:255'
        ];

        if ($request->input('email') !== $singale_info->email) {
            $rules['email'] .= '|unique:users,email|regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,5}$/';
        }

        if ($request->filled('mobile_number') || $request->input('mobile_number') !== $singale_info->mobile_number) {
            $rules['mobile_number'] = [ 
                'string', 'size:10', 'regex:/^[0-9]{10}$/'
            ];
        }

        if ($request->filled('password') || $request->filled('password_confirmation')) {
            $rules['password'] = [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ];
        }

        $data = $request->validate($rules, [
            'password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'mobile_number.size' => 'The mobile number must be exactly 10 digits.',
            'mobile_number.regex' => 'The mobile number must contain only digits.'
        ]);

        $singale_info->firstname = $data['firstname'];
        $singale_info->lastname = $data['lastname'];
        $singale_info->email = $data['email'];
        $singale_info->role = $data['role'];

        if (!empty($data['mobile_number'])) {
            $singale_info->mobile_number = $data['mobile_number'];
        }

        if (!empty($data['password'])) {
            $singale_info->password = Hash::make($data['password']);
        }

        $singale_info->save();

        Session::flash('success', 'User details update successfully.');

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

        Session::flash('success', 'User move to trash successfully.');

        return redirect()->route('users.list');
    }
}
