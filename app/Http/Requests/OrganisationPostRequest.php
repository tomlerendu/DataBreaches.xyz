<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class OrganisationPostRequest extends Request
{
    protected $redirectAction = 'User\SubmissionController@submitOrganisation';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'styled_name' => 'required|max:255',
            'registration_number' => 'required|numeric|digits:8',
            'incorporated_on' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'registration_number.*' => 'The registration number you entered is invalid.',
            //TODO: more rules
        ];
    }
}
