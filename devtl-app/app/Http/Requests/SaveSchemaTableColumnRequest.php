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
        $this->schemaTableColumns = removeLastTableColumnRow(request()->schema_table_columns);
        return $this->schemaTableColumns;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'datatype.*' => 'required|alpha_dash|max:50',
            'length.*' => 'max:255',
            'default_value.*' => 'max:255',
            'comment.*' => 'max:255',
            'nullable.*' => 'in:0,1',
            'unsigned.*' => 'in:0,1',
            'unique.*' => 'in:0,1',
            'auto_increment.*' => 'in:0,1',
            'primary_key.*' => 'in:0,1',
            'zero_fill.*' => 'in:0,1',
        ];

        for($i = 0; $i < count($this->schemaTableColumns['id']); $i++) {
            $existingSchemaTableColumn = request()->schemaTable
                ->schemaTableColumns()
                ->find($this->schemaTableColumns['id'][$i]);

            $unique = '|unique:schema_table_columns,name,'.($existingSchemaTableColumn ? $existingSchemaTableColumn->id : 'null').',id,schema_table_id,'.request()->schemaTable->id;
            $rules['name.'.$i] = 'required|alpha_dash|max:255'.$unique;
        }

        return $rules;
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
            $attributes['nullable.'.$i] = __('form.null').' '.__('form.in_row').' '.$j;
            $attributes['unsigned.'.$i] = __('form.un').' '.__('form.in_row').' '.$j;
            $attributes['unique.'.$i] = __('form.uq').' '.__('form.in_row').' '.$j;
            $attributes['auto_increment.'.$i] = __('form.ai').' '.__('form.in_row').' '.$j;
            $attributes['primary_key.'.$i] = __('form.pk').' '.__('form.in_row').' '.$j;
            $attributes['zero_fill.'.$i] = __('form.zf').' '.__('form.in_row').' '.$j;
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
