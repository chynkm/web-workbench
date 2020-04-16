<?php

namespace App\Http\Requests;

use App\Models\Relationship;
use App\Models\SchemaTable;
use App\Models\SchemaTableColumn;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;

class SaveRelationshipRequest extends FormRequest
{
    protected $relationships;

    public function __construct(Factory $factory)
    {
        $this->additionalValidation($factory);
    }

    public function additionalValidation(Factory $factory)
    {
        $factory->extend('datatype_check', function($attribute, $value, $parameters) {
            $primarySchemaTableColumn = SchemaTableColumn::find($this->relationships['primary_table_column_id'][$parameters[0]]);
            $foreignSchemaTableColumn = SchemaTableColumn::find($value);

            if($primarySchemaTableColumn === null || $foreignSchemaTableColumn === null) {
                return false;
            }

            return $primarySchemaTableColumn->datatype == $foreignSchemaTableColumn->datatype;
        });

        $factory->extend('same_pk_fk_column', function($attribute, $value, $parameters) {
            return ! ($this->relationships['primary_table_column_id'][$parameters[0]] == $value);
        });

        $factory->extend('unique_check', function($attribute, $value, $parameters) {
            $relationship = Relationship::select('id')
                ->where('primary_table_column_id', $this->relationships['primary_table_column_id'][$parameters[0]])
                ->where('foreign_table_column_id', $value)
                ->first();

            if($relationship == null) {
                return true;
            }

            return $relationship->id == $this->relationships['id'][$parameters[0]];
        });

        $factory->extend('same_schema', function($attribute, $value, $parameters) {
            $foreignSchemaTable = SchemaTable::find($value);
            return request('schemaTable') && request('schemaTable')->schema_id == $foreignSchemaTable->schema_id;
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
        $this->relationships = removeLastRelationshipRow(request()->relationships);
        return $this->relationships;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'primary_table_column_id.*' => 'required',
            'foreign_table_id.*' => 'required|same_schema',
        ];

        for($i = 0; $i < count($this->relationships['id']); $i++) {
            $rules['foreign_table_column_id.'.$i] = "required|datatype_check:$i|same_pk_fk_column:$i|unique_check:$i";
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];
        for($i = 0, $j = 1; $i < count($this->relationships['id']); $i++, $j++) {
            $attributes['primary_table_column_id.'.$i] = __('form.column').' '.__('form.in_row').' '.$j;
            $attributes['foreign_table_id.'.$i] = __('form.referenced_table').' '.__('form.in_row').' '.$j;
            $attributes['foreign_table_column_id.'.$i] = __('form.referenced_column').' '.__('form.in_row').' '.$j;
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
