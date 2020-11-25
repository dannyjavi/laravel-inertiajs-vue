<?php

namespace App\Http\Controllers;

use App\Http\Resources\Appointment as AppointmentResources;
use App\Models\Appointment;

use App\Http\Requests\Appointment as AppointmentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller
{

    protected $apt;

    public function __construct(Appointment $appointment)
    {
        $this->apt = $appointment;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->apt::select('id', 'title', 'start', 'end', 'status', 'session', 'color', 'user_id')->orderBy('end', 'asc')->get();

        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentRequest $request)
    {
        // Retrieve the validated input data...
        if ($request->has('session') == 1800) {
            $request->price = 30;
        }

        if ($request->has('session') == 3600) {
            $request->price = 45;
        }

        Appointment::create([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'session' => $request->session,
            'price' => $request->price,
            'user_id' => $request->user_id
        ]);

        return redirect()->back()->with('message', 'Cita creada con éxito');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        //$appointment->update($request->all());

        if ($request->has('id')) {
            Appointment::find($request->id)->update($request->all());
            return redirect()->back()->with('message', 'evento modificado!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->back()->with('message', 'Cita borrada con éxito');
    }
}
