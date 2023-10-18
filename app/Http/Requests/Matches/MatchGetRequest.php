<?php

namespace App\Http\Requests\Matches;

use Illuminate\Foundation\Http\FormRequest;

class MatchGetRequest extends FormRequest
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
            'limit' => 'int|min:1',
            'page' => 'int|min:1',
        ];
    }
}