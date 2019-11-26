    
<table id="example" class="table table-striped jambo_table display" cellspacing="0" width="100%">
   <thead>
      <tr>
        <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
        @if($source_data->active == 0 )
        <th class="column-title">ID</th> 
        @endif
        <th class="column-title">Name</th>                   
        <th class="column-title">Parent name</th>                   
       
        @if($source_data->active == 1 )
        <th class="column-title">X-description</th>
        <th class="column-title">X-note</th>
        @endif        
      </tr>
   </thead>
   <tbody>
    @foreach($taxonomies as $key => $taxonomy)
      <tr>
        <td class="a-center ">
          <input type="checkbox" class="flat" name="table_records[]" value="{{$taxonomy->taxonomy_recordid}}" @if(in_array($taxonomy->taxonomy_recordid, $checked_taxonomies)) checked @endif>
        </td>

        @if($source_data->active == 0)
        <td class="text-center">{{$taxonomy->taxonomy_id}}</td>
        @endif
        <td>{{$taxonomy->taxonomy_name}}</td>

        <td>@if($taxonomy->taxonomy_parent_name!='')
          <span class="badge bg-blue">{{$taxonomy->parent()->first()->taxonomy_name}}</span>
        @endif
        </td>
        

        @if($source_data->active == 1 )
        <td class="text-center">{{$taxonomy->taxonomy_x_description}}</td>
        <td class="text-center">{{$taxonomy->taxonomy_x_notes}}</td>
        @endif
      </tr>
    @endforeach  
   </tbody>
</table>

<script type="text/javascript">
  $(document).ready(function (){   
   var table = $('#example').DataTable({
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'searching': true,
         'orderable':false,
         'className': 'dt-body-center',
      }],
      'order': [1, 'asc'],
        "paging": false,
        "pageLength": 20,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": true
   });


   // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });

   // Handle click on checkbox to set state of "Select all" control
   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });
});
</script>