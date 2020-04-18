@foreach($relationships as $relationship)
@include('relationships.exampleRelationshipRow', compact('schemaTableColumns', 'schemaTables', 'relationship'))
@endforeach
