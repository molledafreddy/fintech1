<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserImportRequest extends FormRequest
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
            'import_file'         => 'required|mimes:xls,xlsx,csv',
        ];
    }

     public function messages()
    {
     return [
            'import_file.mimes' => 'Debe seleccionar un archivo con las siguientes extensiones xls, xlsx, csv',
                      
        ];
    }
}
