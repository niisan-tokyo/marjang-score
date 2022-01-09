<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBattleRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'season' => 'required',
            'share_url' => 'nullable',
            'comment' => 'nullable',
            'battle' => 'required|array',
            'battle.*.user' => 'required|distinct'
        ];
    }
}
