<?php

namespace App\Http\Controllers;

use App\Services\SimulationService;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function store(Request $request,SimulationService $simulationService){
        $startup = auth()->user()->startup;
        if(!$startup){
            return redirect()->route('startup.create')->with('error','Please create your startup first');
        }
        $validated = $request->validate([
            'duration' => ['required','in:6_month,12_month'],
        ]);
        $data= $simulationService->run($startup,$validated['duration']);
        $simulation= $startup->simulations()->create($data);
        
        return redirect()->route('simulations.show', $simulation->id)->with('success','Simulation created successfully');
    }

    public function show($id){
        $startup=auth()->user()->startup;
        if(!$startup){
            return redirect()->route('startup.create')->with('error','Please create your startup first');
        } 
        $simulation= $startup->simulations()->findOrFail($id);
        return view('simulations.show', compact('simulation'));
    }
}
