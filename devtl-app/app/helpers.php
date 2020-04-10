<?php

if(! function_exists('removeLastRow')) {

    function removeLastRow($schemaTableColumns)
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
