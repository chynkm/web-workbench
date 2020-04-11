<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SaveSchemaTableRequest extends FormRequest
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
        if (isset(request()->schemaTable)) {
            $schemaId = request()->schemaTable->schema->id;
            $schemaTableId = request()->schemaTable->id;
        } else {
            $schemaId = request()->schema->id;
            $schemaTableId = null;
        }

        return [
            'name' => 'required|alpha_dash|max:100|unique:schema_tables,name,'.$schemaTableId.',id,schema_id,'.$schemaId,
            'engine' => 'required|max:20',
            'collation' => 'required|max:40',
            'description' => 'max:255',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('form.name'),
            'engine' => __('form.engine'),
            'collation' => __('form.collation'),
            'description' => __('form.description'),
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => __('form.table_with_name_already_exists'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if (request()->ajax()) {
            $errors = [];
            foreach ($validator->errors()->all() as $error) {
                $errors[] = $error;
            }

            $alert = [
                'class' => 'danger',
                'message' => implode('<br/>', $errors)
            ];

            throw new HttpResponseException(
                response()->json([
                    'errors' => $validator->errors(),
                    'html' => view('layouts.alert', compact('alert'))->render(),
                ], 422)
            );
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
