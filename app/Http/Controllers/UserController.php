<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function data()
    {
        $user = User::isNotAdmin()->get();

        return datatables()
            ->of($user)
            ->addIndexColumn()
            ->addColumn('action', function ($user) {
                return '<div class="btn-group">
                            <a onclick="editForm(`' . route('user.update', $user->id) . '`)" class="btn btn-sm btn-primary">Perbarui</a>
                            <a onclick="deleteData(`' . route('user.destroy', $user->id) . '`)" class="btn btn-sm btn-danger">Hapus</a>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $validator['password'] = bcrypt($request->password);

        $user = new User();
        $user->create($validator);

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $users = User::findOrFail($id);

        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $users = User::findOrFail($id);
        $users->name = $request->name;
        $users->email = $request->email;
        if ($request->has('password') && !empty($request->get('password'))) {
            $users->password = bcrypt($request->password);
        }

        $users->update();

        return response()->json(
            'Data berhasil diperbarui',
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}