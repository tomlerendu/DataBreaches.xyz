<?php

namespace App\Http\Requests;

use App\BreachData;
use Illuminate\Support\Facades\Auth;

class BreachPostRequest extends Request
{
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
        $methods = arrayKeyString(BreachData::$methods);
        $dataTypes = arrayKeyString(BreachData::$dataTypes);

        $responseStance = arrayKeyString(BreachData::$responseStances);
        $responsePatched = arrayKeyString(BreachData::$responsePatched);
        $responseCustomersInformed = arrayKeyString(BreachData::$responseCustomersInformed);

        return [
            'previous_id' => 'numeric',
            'method' => 'required|in:' . $methods,
            'date_occurred' => 'required|date',
            'summary' => 'required|string|max:1000',
            'people_affected' => 'numeric',
            'data_leaked' => 'array|in:' . $dataTypes,
            'previously_known' => '',
            'response_stance' => 'required|in:' . $responseStance,
            'response_patched' => 'required|in:' . $responsePatched,
            'response_customers_informed' => 'required|in:' . $responseCustomersInformed,
            'more_url' => 'url|max:500',
            'source_name' => 'required|string|max:100',
            'source_url' => 'required|url|max:500',
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
            'response_stance' => 'You didn\'t select an oranisation response.',
            'response_patched' => 'You didn\'t select an organisation patch timespan.',
            'response_customers_informed' => 'You didn\'t select a customer informed timespan.',
            'more_url' => 'You didn\'t enter a valid URL.',
            'source_name' => 'You didn\'t enter a valid source name.',
            'source_url' => 'You didn\'t enter a valid source URL.',
        ];
    }
}
