<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class CreateOTRequest extends FormRequest
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
            'evident' => 'required|image|mimes:jpg,png,svg|max:1024',
            'approver_id' => 'required|numeric',
            'comment' => 'text',
            'from_datetime' => 'required|date_format:' . config('define.datetime'),
            'to_datetime' =>
            'required|after:from_datetime|date_format:' . config('define.datetime'),
        ];
    }
}
