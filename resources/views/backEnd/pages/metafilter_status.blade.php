    
<table id="example" class="table table-striped jambo_table display" cellspacing="0" width="100%">
   <thead>
      <tr>
        <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
        <th class="text-center">Service Status</th> 
      </tr>
   </thead>
   <tbody>
    @foreach($status_option_list as $key => $status_option)
      <tr>
        <td class="a-center ">
          <input type="checkbox" class="flat" name="checked_status_list[]" value="{{$status_option}}">
        </td>
        <td class="text-center">{{$status_option}}</td>
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