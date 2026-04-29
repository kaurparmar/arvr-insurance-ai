<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'age' => 'required|integer',
            'salary' => 'required|integer',
            'dependents' => 'required|integer'
        ]);

        $premium = ($request->age * 100) +
                   ($request->dependents * 500) -
                   ($request->salary * 0.01);

        return view('calculator', compact('premium'));
    }
}