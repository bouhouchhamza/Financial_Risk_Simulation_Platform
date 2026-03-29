<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function create(){
        $user = auth()->user();
        if($user->startup){
            return redirect()->route('startup.show');
        }
        return view('startups.create');

    }
    public function store(Request $request){
        $user= auth()->user();
            if($user->startup){
                return redirect()->route('startup.show')->with('error','You already have a startup');
            }
            $validated = $request->validate([
                'name'=>['required','string','max:255'],
                'activity_type'=>['required','string','max:255'],
                'initial_budget' => ['required','numeric','min:0'],
                'monthly_revenue' => ['required','numeric','min:0'],
                'monthly_expenses' => ['required','numeric','min:0'],
                'employees_count' => ['required','integer','min:1'],
            ]);
            $user->startup()->create($validated);
            return redirect()->route('startup.show')->with('success','startup created successfully.');
    }
    public function show(){
        $startup = auth()->user()->startup;
        if(!$startup){
            return redirect()->route('startup.create')->with('error','Please Create your startup first.');
        }
        return view('startups.show',compact('startup'));
    }
}
