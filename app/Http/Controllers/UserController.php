<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withTrashed()->get();
        return response()->json(['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(null, 501);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'email_verified_at' => 'sometimes|date_format:Y-m-d H:i:s',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id User ID
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user)
            return response()->json(['error' => 'Unknown user'], 404);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id User ID
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response(null, 501);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param int $id User ID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user)
            return response()->json(['error' => 'Unknown user'], 404);

        $this->validate($request, [
            'name' => 'sometimes|max:100|unique:users,name,' . $user->id,
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'email_verified_at' => 'sometimes|date_format:Y-m-d H:i:s',
            'password' => $request->has('password') != null ? 'sometimes|required|min:6|confirmed' : '',
        ]);

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->email_verified_at = $request->input('email_verified_at', $user->email_verified_at);
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id User ID
     * @return \Illuminate\Http\Response
     * @throws \Exception No primary key defined on model.
     */
    public function trash($id)
    {
        $user = User::find($id);

        if (!$user)
            return response()->json(['error' => 'Unknown user'], 404);

        $user->delete();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id User ID
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);

        if (!$user)
            return response()->json(['error' => 'Unknown user'], 404);

        $user->restore();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id User ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user)
            return response()->json(['error' => 'Unknown user'], 404);

        $user->forceDelete();

        return response(null, 204);
    }
}
