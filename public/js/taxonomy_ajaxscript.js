
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
            url: '/tb_taxonomy' + '/' + id,
            success: function (data) {
                if(data.taxonomy_parent_name){
                    $('#parentDiv').show()
                    $('#orderDiv').show()
                }else{
                    $('#parentDiv').hide()
                    $('#orderDiv').hide()
                }
                if(data.parentData){
                    $('#taxonomy_parent_name').empty()
                    $('#taxonomy_parent_name').append('<option value="">Select Parent</option>');
                    $.each(data.parentData,function(i,v){
                        $('#taxonomy_parent_name').append('<option value="'+i+'">'+v+'</option>');
                    })
                    $('#taxonomy_parent_name').val('')
                    $('#taxonomy_parent_name').selectpicker('refresh')
                }
                $('#id').val(data.taxonomy_recordid);
                $('#taxonomy_name').val(data.taxonomy_name);
                $('#taxonomy').val(data.taxonomy);
                $('#x_taxonomies').val(data.x_taxonomies);
                $('#taxonomy_x_description').val(data.taxonomy_x_description);
                $('#taxonomy_grandparent_name').val(data.taxonomy_parent_name);
                $('#language').val(data.language);
                $('#order').val(data.order);
                $('#taxonomy_parent_name').val(data.taxonomy_parent_name);
                $('#taxonomy_x_notes').val(data.taxonomy_x_notes);
                $('#exclude_vocabulary').val(data.exclude_vocabulary);
                $('#badge_color').val(data.badge_color);
                $('#created_at').val(data.created_at);
                $('#created_by').val(data.user ? data.user.first_name : '' );
                $('#status').val(data.status);
                $('#white_logo_image').attr('src',data.category_logo_white)
                $('#category_logo_image').attr('src',data.category_logo)
                $("#taxonomy").selectpicker('refresh');
                $("#x_taxonomies").selectpicker('refresh');
                $('#taxonomy_parent_name').selectpicker('refresh')
                $('#btn-save').val("update");
                $('#myModal').modal('show');


            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });



    //create new product / update existing product ***************************
    // $( "#frmProducts" ).submit(function(e) {
    // $("#btn-save").click(function (e) {
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //         }
    //     })

    //     e.preventDefault();
    //     // let myForm = document.getElementById('frmProducts');
    //     // console.log(myForm)
    //     var formData = new FormData();
    //         formData.append('taxonomy_name',$('#taxonomy_name').val())
    //         formData.append('taxonomy_vocabulary',$('#taxonomy_vocabulary').val())
    //         formData.append('taxonomy_x_description',$('#taxonomy_x_description').val())
    //         formData.append('taxonomy_grandparent_name',$('#taxonomy_grandparent_name').val())
    //         formData.append('taxonomy_x_notes',$('#taxonomy_x_notes').val())
    //         formData.append('category_logo',$('#category_logo').val())
    //         formData.append('category_logo_white',$('#category_logo_white').val())
    //     // var formData = {
    //     //     taxonomy_name: $('#taxonomy_name').val(),
    //     //     taxonomy_vocabulary: $('#taxonomy_vocabulary').val(),
    //     //     taxonomy_x_description: $('#taxonomy_x_description').val(),
    //     //     taxonomy_grandparent_name: $('#taxonomy_grandparent_name').val(),
    //     //     taxonomy_x_notes: $('#taxonomy_x_notes').val(),
    //     //     category_logo: $('#category_logo').val(),
    //     //     category_logo_white: $('#category_logo_white').val(),
    //     // }

    //     //used to determine the http verb to use [add=POST], [update=PUT]
    //     var state = $('#btn-save').val();
    //     var type = "POST"; //for creating new resource
    //     var id = $('#id').val();
    //     var my_url = url;
    //     if (state == "update"){
    //         type = "PUT"; //for updating existing resource
    //         my_url += '/' + id;
    //     }

    //     $.ajax({
    //         type: type,
    //         url: my_url,
    //         data: {formData},
    //         dataType: 'json',
    //         processData: false,
    //         success: function (data) {
    //             // console.log(data);
    //             // var product = '<tr id="project' + data.id + '"><td class="text-center">' + data.project_projectid + '</td><td class="text-center">' + data.project_managingagency + '</td>';
    //             // product += '<td class="text-center"><button class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill open_modal" title="Edit details" value="' + data.bodystyleid + '"><i class="la la-edit"></i></button>';
    //             // product += ' <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-product" title="Delete" value="' + data.bodystyleid + '"><i class="la la-trash"></i></button></td></tr>';

    //             if (state == "add"){ //if user added a new record
    //                 window.location.reload();
    //                // $('.alert.alert-success.alert-dismissible.fade.show').hide(5000);
    //             }else{ //if user updated an existing record
    //                 $('#frmProducts').trigger("reset");
    //                 $('#myModal').modal('hide');
    //                 window.location.reload();
    //                 // $("#project" + project_id).replaceWith( product );
    //                // $('.m-portlet.m-portlet--mobile').before(edit_alert);
    //                 //$('.alert.alert-brand.alert-dismissible.fade.show').hide(5000);
    //             }

    //         },
    //         error: function (data) {
    //             console.log('Error:', data);
    //         }
    //     });
    // });

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
function saveLanguage(id){
    let value = $('#language_'+id).val()
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        $.ajax({
            type: "POST",
            url: '/saveLanguage',
            data:{id,value},
            success: function (data) {
                location.reload();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
}
