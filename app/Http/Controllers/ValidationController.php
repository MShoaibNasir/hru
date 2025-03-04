<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidationController extends Controller
{
      public function validateSignup(Request $request)
    {
        dd($request);
         $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'lot_id' => 'required',
                'district_id' => 'required',
                'tehsil_id' => 'required',
                'uc_id' => 'required',
                'role' => 'required',
                'password' => 'required',
                'number' => 'required',
                'organization' => 'required',
                'section' => 'required',
                'designation' => 'required',
                'supervisor_name' => 'required',
                'role' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
    }
}
