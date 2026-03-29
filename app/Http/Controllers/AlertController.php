<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alerts = $startup->alerts()->latest()->get();
        return view('alerts.index', compact('alerts'));
    }
    public function show($id) {
        
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);
        return view('alerts.show',compact('alert'));
    }
    public function edit($id){
        
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);
        return view('alerts.edit', compact('alert'));
    }
    public function update(Request $request,$id){
        
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);
        $validated = $request->validate([
            'status' => ['required','in:new,reviewed,resolved'],
            'message' => ['nullable','string'],
        ]);
        $alert->update($validated);
        return redirect()->route('alerts.index')->with('success','Alert updated successfully.');
    }
    public function destroy($id){
        
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);
        $alert->delete();
        return redirect()->route('alerts.index')->with('success','Alert deleted successfully.');
    }
}
