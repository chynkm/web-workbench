<?php

namespace App\Http\Requests;

use App\Models\Relationship;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;

class SaveSchemaTableColumnRequest extends FormRequest
{

    protected $schemaTableColumns;

    public function __construct(Factory $factory)
    {
        $this->additionalValidation($factory);
    }

    public function additionalValidation(Factory $factory)
    {
        $factory->extend('fk_check', function($attribute, $value, $parameters) {
            $relationship = Relationship::select('datatype')
                ->join('schema_table_columns', 'schema_table_id', 'primary_table_column_id')
                ->where('foreign_table_column_id', $parameters[0])
                ->first();

            if($relationship === null) {
                return true;
            }

            return $relationship->datatype == $value;
        });
    }

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

            if ($existingSchemaTableColumn) {
                $rules['datatype.'.$i] = 'required|alpha_dash|max:50|fk_check:'.$existingSchemaTableColumn->id;
                $unique = '|unique:schema_table_columns,name,'.$existingSchemaTableColumn->id.',id,schema_table_id,'.request()->schemaTable->id;
            } else {
                $rules['datatype.'.$i] = 'required|alpha_dash|max:50';
                $unique = '|unique:schema_table_columns,name,null,id,schema_table_id,'.request()->schemaTable->id;
            }

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

    public function messages()
    {
        $messages = [];
        for($i = 0, $j = 1; $i < count($this->schemaTableColumns['id']); $i++, $j++) {
            $messages['datatype.'.$i.'.fk_check'] = __('form.foreign_key_datatype_mismatch', ['row_no' => $j]);
        }

        return $messages;
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
