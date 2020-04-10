<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SaveSchemaTableColumnRequest extends FormRequest
{

    protected $schemaTableColumns;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    public function validationData()
    {
        // remove last empty row from validation
        $this->schemaTableColumns = removeLastRow(request()->schema_table_columns);
        return $this->schemaTableColumns;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name.*' => 'required|alpha_dash|max:255',
            'datatype.*' => 'required|alpha_dash|max:50',
            'length.*' => 'required|max:255',
            'default_value.*' => 'max:255',
            'comment.*' => 'max:255',
        ];
    }

    public function attributes()
    {
        $attributes = [];
        for($i = 0, $j = 1; $i < count($this->schemaTableColumns['id']); $i++, $j++) {
            $attributes['name.'.$i] = __('form.name').' '.__('form.in_row').' '.$j;
            $attributes['datatype.'.$i] = __('form.datatype').' '.__('form.in_row').' '.$j;
            $attributes['length.'.$i] = __('form.length').' '.__('form.in_row').' '.$j;
            $attributes['default_value.'.$i] = __('form.default_value').' '.__('form.in_row').' '.$j;
            $attributes['comment.'.$i] = __('form.comment').' '.__('form.in_row').' '.$j;
        }

        return $attributes;
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
