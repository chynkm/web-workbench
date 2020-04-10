<?php
$dataTypes = [
    'Numbers' => ['tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'decimal', 'float', 'double',],
    'Date and time' => ['date', 'datetime', 'timestamp', 'time', 'year',],
    'Strings' => ['char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'json',],
    'Lists' => ['enum', 'set',],
    'Binary' => ['bit', 'binary', 'varbinary', 'tinyblob', 'blob', 'mediumblob', 'longblob',],
    'Geometry' => ['geometry', 'point', 'linestring', 'polygon', 'multipoint', 'multilinestring', 'multipolygon', 'geometrycollection',],
];
?>

<select name="schema_table_columns[datatype][]" class="form-control datatype" aria-labelledby="label-type">
    @foreach($dataTypes as $typeGroup => $dataTypes)
    <optgroup label="{{ $typeGroup }}">
        @foreach($dataTypes as $type)
        <option value="{{ $type }}" {{ (isset($schemaTableColumn) && $schemaTableColumn->datatype == $type) || (! isset($schemaTableColumn) && $type == 'tinyint') ? ' selected' : null }}>{{ $type }}</option>
        @endforeach
    </optgroup>
    @endforeach
</select>
