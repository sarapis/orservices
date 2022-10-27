<div class="card all_form_field">
    <div class="card-block">
        <h4 class="title_edit text-left mb-25 mt-10">
            Category
            <div class="d-inline float-right" id="addServiceCategoryTr">
                <a href="javascript:void(0)" id="addServiceCategoryData" class="plus_delteicon bg-primary-color">
                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                </a>
            </div>
        </h4>
        <div class="row">
            {{-- taxonomy section --}}
            <div class="col-md-12">
                <div class="form-group">
                    <div class="">
                        <table class="table table_border_none" id="ServiceCategoryTable">
                            <thead>
                                <th>Type</th>
                                <th>Term</th>
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody>
                                @if (count($service_category_type_data) > 0 )
                                    @foreach ($service_category_type_data as $key => $value)
                                        <tr>
                                            <td>
                                                {!! Form::select('service_category_type[]', $service_category_types, $value->taxonomy_recordid, ['class' => 'form-control selectpicker service_category_type', 'placeholder' => 'Select Type', 'id' => 'service_category_type_' . $key]) !!}
                                                {{-- @error('service_category_type') --}}
                                                {{-- @enderror --}}
                                            </td>
                                            <td class="create_btn">
                                                @php
                                                    $taxonomy_parent_name = \App\Model\Taxonomy::where('taxonomy_recordid', $value->taxonomy_recordid)->first();
                                                    $taxonomy_info_list = \App\Model\Taxonomy::where('taxonomy_parent_name','LIKE','%'. $value->taxonomy_recordid.'%')->get();
                                                    // $taxonomy_array = [];
                                                    // foreach ($taxonomy_info_list as $value1) {
                                                    // $taxonomy_array[$value1->taxonomy_recordid] = '- ' .
                                                    // }
                                                    $taxonomy_array = [];
                                                    foreach ($taxonomy_info_list as $value1) {
                                                        $taxonomy_parent_name->taxonomy_name . ' : ' . $value1->taxonomy_name;
                                                        $taxonomy_array[$value1->taxonomy_recordid] = '- ' . $value1->taxonomy_name;
                                                        $taxonomy_child_list = \App\Model\Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value1->taxonomy_recordid . '%')->get();
                                                        if ($taxonomy_child_list) {
                                                            foreach ($taxonomy_child_list as $value2) {
                                                                $taxonomy_array[$value2->taxonomy_recordid] = '-- ' . $value2->taxonomy_name;
                                                                $taxonomy_child_list1 = \App\Model\Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value2->taxonomy_recordid . '%')->get();
                                                                if ($taxonomy_child_list1) {
                                                                    foreach ($taxonomy_child_list1 as $value3) {
                                                                        $taxonomy_array[$value3->taxonomy_recordid] = '--- ' . $value3->taxonomy_name;
                                                                        $taxonomy_child_list2 = \App\Model\Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value3->taxonomy_recordid . '%')->get();
                                                                        if (count($taxonomy_child_list2)) {
                                                                            foreach ($taxonomy_child_list2 as $value4) {
                                                                                $taxonomy_array[$value4->taxonomy_recordid] = '---- ' . $value4->taxonomy_name;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $taxonomy_array['create_new'] = '+ Create New';
                                                @endphp
                                                {!! Form::select('service_category_term['.$key.'][]', $taxonomy_array, isset($selected_category_ids[$value->taxonomy_recordid]) ? $selected_category_ids[$value->taxonomy_recordid] : [], ['class' => 'form-control selectpicker service_category_term', 'id' => 'service_category_term_' . $key,'multiple' => true,'data-size' => '5','data-live-search' => 'true']) !!}
                                                <input type="hidden" name="service_category_term_type[]"
                                                    id="service_category_term_type_{{ $key }}" value="old">
                                            </td>
                                            {{-- <td class="text-center">
                                        <a href="javascript:void(0)" class="removePhoneData" style="color:red;"> <i class="fa fa-minus-circle" aria-hidden="true"></i> </a>
                                    </td> --}}
                                            <td style="vertical-align: middle">
                                                <a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData">
                                                    <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr></tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            {!! Form::select('service_category_type[]', $service_category_types, null, ['class' => 'form-control selectpicker service_category_type', 'placeholder' => 'Select Type', 'id' => 'service_category_type_0']) !!}

                                        </td>
                                        <td class="create_btn">
                                            {!! Form::select('service_category_term[0][]',[],null,['class' => 'form-control selectpicker service_category_term','id' => 'service_category_term_0','multiple' => true,'data-size' => '5','data-live-search' => 'true']) !!}
                                            <input type="hidden" name="service_category_term_type[]"
                                                id="service_category_term_type_0" value="old">
                                        </td>
                                        <td style="vertical-align: middle">
                                            <a href="javascript:void(0)" class="plus_delteicon btn-button">
                                                <img src="/frontend/assets/images/delete.png" alt="" title="">
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- service category term modal --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
    id="create_new_service_category_term">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close serviceCategoryTermCloseButton"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Service Category Term</h4>
                </div>
                <div class="modal-body all_form_field">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{-- <label>Service Category Term</label> --}}
                                <input type="text" class="form-control" placeholder="Service Category Term"
                                    id="service_category_term_p">
                                <input type="hidden" name="service_category_term_index_p" value=""
                                    id="service_category_term_index_p">
                                <span id="service_category_term_error" style="display: none;color:red">Service Category
                                    Term is required!</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger btn-lg btn_delete red_btn serviceCategoryTermCloseButton">Close</button>
                    <button type="button" id="serviceCategoryTermSubmit"
                        class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                </div>
        </div>
    </div>
</div>
{{-- End here --}}
<script>
    $(document).on("change",'div > .service_category_type', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[3] : ''
        if(value == ''){
            $('#service_category_term_'+index).empty()
            $('#service_category_term_'+index).val('')
            $('#service_category_term_'+index).selectpicker('refresh')
            return false
        }
        $.ajax({
            url : '{{ route("getTaxonomyTerm") }}',
            method : 'get',
            data : {value},
            success: function (response) {
                let data = response.data
                $('#service_category_term_'+index).empty()
                // $('#service_category_term_'+index).append('<option value="">Select term</option>');
                $.each(data,function(i,v){
                    $('#service_category_term_'+index).append('<option value="'+i+'">'+v+'</option>');
                })
                $('#service_category_term_'+index).append('<option value="create_new">+ Create New</option>');
                $('#service_category_term_'+index).val('')
                $('#service_category_term_'+index).selectpicker('refresh')
            },
            error : function (error) {
                console.log(error)
            }
        })
    })
    $(document).on("change",'div >.service_category_term', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let text = $( "#"+id+" option:selected" ).text();
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[3] : ''

        // if(value == 'create_new'){
        //     $('#service_category_term_index_p').val(index)
        //     $('#create_new_service_category_term').modal('show')
        // }else if(text == value){
        //     $('#service_category__type_'+index).val('new')
        // }else{
        //     $('#service_category__type_'+index).val('old')
        // }
        if(value.includes('create_new')){
            $('#service_category_term_index_p').val(index)
            $('#create_new_service_category_term').modal('show')
        }else if(value.includes(text)){
            $('#service_category_term_type_'+index).val('new')
        }else{
            $('#service_category_term_type_'+index).val('old')
        }
    })
    $('#serviceCategoryTermSubmit').click(function () {

        // if($('#service_category_term_p').val() == ''){
        //         $('#service_category_term_error').show()
        //     setTimeout(() => {
        //         $('#service_category_term_error').hide()
        //     }, 5000);
        //     return false
        // }
        // $('#service_category_term_type_'+index).val('new')
        // $('#service_category_term_'+index).append('<option value="'+service_category_term+'">'+service_category_term+'</option>');
        // $('#service_category_term_'+index).val(service_category_term)
        // $('#service_category_term_'+index).selectpicker('refresh')
        // $('#create_new_service_category_term').modal('hide')
        // $('#service_category_term_p').val('')
        // $('#service_category_term_index_p').val('')
        $('#loading').show()
        let service_category_term = $('#service_category_term_p').val()
        let index = $('#service_category_term_index_p').val()
        let category_type_recordid = $('#service_category_type_'+index).val()
        let _token = "{{ csrf_token() }}"
        let service_recordid = "{{ $service->service_recordid }}"
        let organization_recordid = "{{ $service->service_organization }}"
        $.ajax({
            url : '{{ route("saveTaxonomyTerm") }}',
            method : 'post',
            data : {category_type_recordid,service_category_term,_token,service_recordid,organization_recordid},
            success: function (response) {
                $('#loading').hide()
                alert('Thank you for submitting a new term. It is being evaluated by the system administrators. We will let you know if it becomes available.');
                $('#service_category_type_'+index).val('')
                $('#service_category_term_'+index).empty()
                $('#service_category_term_'+index).selectpicker('refresh')
                $('#service_category_type_'+index).selectpicker('refresh')
                $('#create_new_service_category_term').modal('hide')
                $('#service_category_term_p').val('')
            },
            error : function (error) {
                $('#loading').hide()
                $('#create_new_service_category_term').modal('hide')
                console.log(error)
            }
        })
    })
    $('.serviceCategoryTermCloseButton').click(function () {

        let detail_term = $('#service_category_term_p').val()
        let index = $('#service_category_term_index_p').val()
        $('#service_category_term_type_'+index).val('old')
        $('#service_category_term_'+index).val('');
        $('#service_category_term_'+index).selectpicker('refresh')
        $('#create_new_service_category_term').modal('hide')
        $('#service_category_term_p').val('')
        $('#service_category_term_index_p').val('')
    })

    let sc = {{ count($service_category_type_data) > 0 ? count($service_category_type_data) : 1  }}
    $('#addServiceCategoryTr').click(function(){
        $('#ServiceCategoryTable tr:last').before('<tr><td><select name="service_category_type[]" id="service_category_type_'+sc+'" class="form-control selectpicker service_category_type"><option value="">Select Type</option> @foreach ($service_category_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td class="create_btn"> <select name="service_category_term['+sc+'][]" id="service_category_term_'+sc+'" class="form-control selectpicker service_category_term" data-size="5" data-live-search="true" multiple></select><input type="hidden" name="service_category_term_type[]" id="service_category_term_type_'+sc+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        sc++;
    })

</script>
