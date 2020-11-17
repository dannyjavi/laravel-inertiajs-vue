<?php

namespace App\Http\Controllers;

use App\Models\MyEvent;

class MyEventController extends Controller
{    

    protected $table = 'my_events';

    /**
     * Show the private events.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        $memories = MyEvent::get(['title','start','end']);
        return response()->json($memories);
    }
}