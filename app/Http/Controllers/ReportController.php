<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $reports= $startup->reports()->latest()->get();
        return view('reports.index',compact('reports'));
    }
    public function show($id){
        
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $report = $startup->reports()->findOrFail($id);
        return view('reports.show', compact('report'));
    }
    public function destroy($id){
        
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $report = $startup->reports()->findOrFail($id);
        $report->delete();
        return redirect()->route('reports.index')->with('success','Report deleted successfully.');
    }
        
}
