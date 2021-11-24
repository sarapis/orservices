{!! Form::open(['method'=>'DELETE', 'route' => [$route.'.destroy', $id], 'style' => 'display:inline','onsubmit' => 'return confirm("Are you sure want to delete this record?")']) !!}
{{Form::button('<i class="fa fa-trash" style="color: #c3211c;"></i>', array('type' => 'submit', 'data-placement' => 'top', 'data-original-title' => 'Delete', 'id'=>'delete-confirm'))}}

{!! Form::close() !!}
