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
        $factory->extend('one_relationship', function($attribute, $value, $parameters) {
            $relationship = Relationship::select('id')
                ->where('foreign_table_id', request('schemaTable')->id)
                ->where('foreign_table_column_id', $value)
                ->first();

            if($relationship === null) {
                return true;
            }

            return $relationship->id == $this->relationships['id'][$parameters[0]];
        });

        $factory->extend('same_schema', function($attribute, $value, $parameters) {
            $foreignSchemaTable = SchemaTable::find($value);
            return request('schemaTable') && request('schemaTable')->schema_id == $foreignSchemaTable->schema_id;
        });

        $factory->extend('datatype_check', function($attribute, $value, $parameters) {
            $foreignSchemaTableColumn = SchemaTableColumn::find($this->relationships['foreign_table_column_id'][$parameters[0]]);
            $primarySchemaTableColumn = SchemaTableColumn::find($value);

            if($primarySchemaTableColumn === null || $foreignSchemaTableColumn === null) {
                return false;
            }

            return $primarySchemaTableColumn->datatype == $foreignSchemaTableColumn->datatype;
        });

        $factory->extend('same_pk_fk_column', function($attribute, $value, $parameters) {
            return ! ($this->relationships['foreign_table_column_id'][$parameters[0]] == $value);
        });

        $factory->extend('unique_check', function($attribute, $value, $parameters) {
            $relationship = Relationship::select('id')
                ->where('foreign_table_column_id', $this->relationships['foreign_table_column_id'][$parameters[0]])
                ->where('primary_table_column_id', $value)
                ->first();

            if($relationship == null) {
                return true;
            }

            return $relationship->id == $this->relationships['id'][$parameters[0]];
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
        $rules['primary_table_id.*'] = 'required|same_schema';

        for($i = 0; $i < count($this->relationships['id']); $i++) {
            $rules['foreign_table_column_id.'.$i] = "required|one_relationship:$i";
            $rules['primary_table_column_id.'.$i] = "required|datatype_check:$i|same_pk_fk_column:$i|unique_check:$i";
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];
        for($i = 0, $j = 1; $i < count($this->relationships['id']); $i++, $j++) {
            $attributes['foreign_table_column_id.'.$i] = __('form.column').' '.__('form.in_row').' '.$j;
            $attributes['primary_table_id.'.$i] = __('form.referenced_table').' '.__('form.in_row').' '.$j;
            $attributes['primary_table_column_id.'.$i] = __('form.referenced_column').' '.__('form.in_row').' '.$j;
        }

        return $attributes;
    }

    public function messages()
    {
        $messages = [];
        for($i = 0, $j = 1; $i < count($this->relationships['id']); $i++, $j++) {
            $messages['primary_table_id.'.$i.'.same_schema'] = __('form.the_tables_arent_part_of_same_schema', ['row_no' => $j]);
            $messages['foreign_table_column_id.'.$i.'.one_relationship'] = __('form.the_column_can_only_be_referenced_once', ['row_no' => $j]);
            $messages['primary_table_column_id.'.$i.'.datatype_check'] = __('form.the_referenced_column_should_have_same_type', ['row_no' => $j]);
            $messages['primary_table_column_id.'.$i.'.same_pk_fk_column'] = __('form.similar_columns_cannot_be_referenced', ['row_no' => $j]);
            $messages['primary_table_column_id.'.$i.'.unique_check'] = __('form.the_foreign_key_already_exists', ['row_no' => $j]);
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
