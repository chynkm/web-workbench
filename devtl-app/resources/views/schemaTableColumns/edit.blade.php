@foreach($schemaTableColumns as $schemaTableColumn)
@include('schemaTableColumns.exampleRow', compact('schemaTableColumn'))
@endforeach
