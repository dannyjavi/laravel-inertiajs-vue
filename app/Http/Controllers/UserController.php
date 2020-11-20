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
        return Inertia::render('Admin/users', ['userList' => User::paginate()]);
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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        try {
            $find = User::findOrFail($user->id);

            $find->delete();
            
            return redirect()
                ->back()
                ->with('message', 'Paciente eliminado!!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'No se puede borrar el usuario intentalo mas tarde');
        }        
        
    }
}