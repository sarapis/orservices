
$(document).ready(function(){

    //get base URL *********************
    var url = $('#url').val();

    //display modal form for creating new product *********************
    $('#btn_add').click(function(){
        $('#btn-save').val("add");
        $('#frmProducts').trigger("reset");
        $('#myModal').modal('show');
    });



    //display modal form for product EDIT ***************************
    $(document).on('click','.open_modal',function(){
        var id = $(this).val();

        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: url + '/' + id,
            success: function (data) {
                // console.log(data);
                $('#id').val(data.location_recordid);
                $('#location_name').val(data.location_name);
                $('#location_alternate_name').val(data.location_alternate_name);
                $('#location_transportation').val(data.location_transportation);
                $('#location_latitude').val(data.location_latitude);
                $('#location_longitude').val(data.location_longitude);
                $('#location_description').val(data.location_description);
                
                $('#btn-save').val("update");
                $('#myModal').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });



    //create new product / update existing product ***************************
    $( "#myModal" ).submit(function(e) {
    // $("#btn-save").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

        e.preventDefault(); 
        var formData = {
            location_name: $('#location_name').val(),
            location_alternate_name: $('#location_alternate_name').val(),
            location_transportation: $('#location_transportation').val(),
            location_latitude: $('#location_latitude').val(),
            location_longitude: $('#location_longitude').val(),
            location_description: $('#location_description').val()
        }

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btn-save').val();
        var type = "POST"; //for creating new resource
        var id = $('#id').val();
        var my_url = url;
        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + id;
        }

        // console.log(formData);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                // var product = '<tr id="project' + data.id + '"><td class="text-center">' + data.project_projectid + '</td><td class="text-center">' + data.project_managingagency + '</td>';
                // product += '<td class="text-center"><button class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill open_modal" title="Edit details" value="' + data.bodystyleid + '"><i class="la la-edit"></i></button>';
                // product += ' <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-product" title="Delete" value="' + data.bodystyleid + '"><i class="la la-trash"></i></button></td></tr>';
                
                if (state == "add"){ //if user added a new record
                    $('#products-list').append(product);
                    $('.m-portlet.m-portlet--mobile').before(add_alert);
                   // $('.alert.alert-success.alert-dismissible.fade.show').hide(5000);
                }else{ //if user updated an existing record
                    $('#frmProducts').trigger("reset");
                    $('#myModal').modal('hide');
                    window.location.reload(); 
                    // $("#project" + project_id).replaceWith( product );
                   // $('.m-portlet.m-portlet--mobile').before(edit_alert);
                    //$('.alert.alert-brand.alert-dismissible.fade.show').hide(5000);
                }
                
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

     //display modal form for product EDIT ***************************
    $(document).on('click','.delete-product',function(){
        var product_id = $(this).val();
       
        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: url + '/' + product_id,
            success: function (data) {
                // console.log(data);
                $('#product_id').val(data.bodystyleid);
                $('#name').val(data.name);
                $('#price').val(data.abbrev);
                $('#btn-delete').val("delete");
                $('#deleteModal').modal('show');

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //delete product and remove it from TABLE list ***************************
    $(document).on('click','#btn-delete',function(){
        var product_id = $('#product_id').val();
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        $.ajax({
            type: "DELETE",
            url: url + '/' + product_id,
            success: function (data) {
                // console.log(data);
                $("#product" + product_id).remove();
                $('#deleteModal').modal('hide');
                var delete_alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>You successfully deleted Bodystyle.</div>';
                $('.m-portlet.m-portlet--mobile').before(delete_alert);
               // $('.show').hide(5000);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
    
});