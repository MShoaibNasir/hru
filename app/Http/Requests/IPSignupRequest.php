<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IPSignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
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
        ];
    }
}
