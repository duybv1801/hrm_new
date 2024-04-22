<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InOutForgetRequest extends FormRequest
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
            'reason' => 'required|string|max: 255',
            'evident' => 'image|mimes:jpg,png,svg|max:1024',
            'approver_id' => 'required|numeric',
            'comment' => 'text',
            'date' => 'required|date_format:' . config('define.date_show'),
            'in_time' => 'required|date_format:' . config('define.time'),
            'out_time' => 'required|after:in_time|date_format:' . config('define.time'),
        ];
    }
}
