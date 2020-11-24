<?php

namespace App\Http\Controllers;

use App\Models\MyEvent;
use Illuminate\Support\Facades\Auth;

class MyEventController extends Controller
{    

    protected $table = 'my_events';

    public function __construct() {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    /**
     * Show the private events.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        $user = Auth::user()->isAdmin;

        if($user){
            $memories = MyEvent::get(['title','start','end']);
            return response()->json($memories);
        }
        return response()->json(['No tienes acceso a ésta zona'], 403);
    }
}