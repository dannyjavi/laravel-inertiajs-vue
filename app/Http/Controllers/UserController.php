<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserForm;
use App\Models\Appointment;
use Inertia\Inertia;
use App\Models\User;
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
        $data = User::all();
        return Inertia::render('Admin/users', ['data' => $data]);
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
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required'],
        ])->validate();
  
        if ($request->has('id')) {
            User::find($request->input('id'))->update($request->all());
            return redirect()
                    ->back()
                    ->with('message', 'Usuario modificado correctamente');
        }
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            User::find($request->id)->delete();
            return redirect()
                ->back()
                ->with('message', 'Paciente creado!!');

        }
    }
}