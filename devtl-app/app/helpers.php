<?php

if(! function_exists('removeLastTableColumnRow')) {

    function removeLastTableColumnRow($schemaTableColumns)
    {
        $totalRows = count($schemaTableColumns['id']) - 1;
        unset($schemaTableColumns['id'][$totalRows]);
        unset($schemaTableColumns['name'][$totalRows]);
        unset($schemaTableColumns['datatype'][$totalRows]);
        unset($schemaTableColumns['length'][$totalRows]);
        unset($schemaTableColumns['default_value'][$totalRows]);
        unset($schemaTableColumns['comment'][$totalRows]);

        return $schemaTableColumns;
    }
}

if(! function_exists('removeLastRelationshipRow')) {

    function removeLastRelationshipRow($relationships)
    {
        $totalRows = count($relationships['id']) - 1;
        unset($relationships['id'][$totalRows]);
        unset($relationships['primary_table_column_id'][$totalRows]);
        unset($relationships['foreign_table_id'][$totalRows]);
        unset($relationships['foreign_table_column_id'][$totalRows]);

        return $relationships;
    }
}


