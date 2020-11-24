<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserForm;
use App\Models\Appointment;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        $profile =  Auth::user()->isAdmin;

        if ($profile) {
            return Inertia::render('Admin/users', ['userList' => User::paginate()]);
        }

        return Inertia::render('Agenda/Books');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $find = User::query()->select('name')
            ->where('id', $id)
            ->get();

        return response()->json($find);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(StoreUserForm $request)
    {
        User::create($request->all());
        return redirect()
            ->back()
            ->with('message', 'Paciente creado!!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(StoreUserForm $request)
    {
        if ($request->has('id')) {
            User::find($request->input('id'))->update($request->all());

            return redirect()
                ->back()
                ->with('message', 'Usuario modificado correctamente');
        }
    }

    /**
     * Remove the especific resource form storage.
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->back()
            ->with('message', 'Paciente eliminado!!');
    }
}
