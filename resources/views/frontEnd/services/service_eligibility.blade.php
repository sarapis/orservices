<div class="card all_form_field">
    <div class="card-block">
        <h4 class="title_edit text-left mb-25 mt-10">
            Eligibility
            <div class="d-inline float-right" id="addServiceEligibilityTr">
                <a href="javascript:void(0)" id="addServiceEligibilityData" class="plus_delteicon bg-primary-color">
                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                </a>
            </div>
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="">
                        <table class="table table_border_none" id="ServiceEligibilityTable">
                            <thead>
                                <th>Type</th>
                                <th>Term</th>
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>
                                        {!! Form::select('service_eligibility_type[]',$service_eligibility_types,null,['class' => 'form-control selectpicker service_eligibility_type','placeholder' => 'select service eligibility type','id' => 'service_eligibility_type_0']) !!}

                                    </td>
                                    <td>
                                        {!! Form::select('service_eligibility_term[]',[],null,['class' => 'form-control selectpicker service_eligibility_term','placeholder' => 'select service eligibility term','id' => 'service_eligibility_term_0']) !!}
                                        <input type="hidden" name="service_eligibility_term_type[]" id="service_eligibility_term_type_0" value="old">
                                    </td>
                                    <td></td>
                                </tr> --}}
                                @if (count($service_eligibility_term_data) > 0 )
                                @foreach ($service_eligibility_type_data as $key => $value)
                                <tr>
                                    <td>
                                        {!!
                                        Form::select('service_eligibility_type[]',$service_eligibility_types,$value->taxonomy_recordid,['class'
                                        => 'form-control selectpicker service_eligibility_type','placeholder' => 'Select
                                        Type','id' => 'service_eligibility_type_'.$key]) !!}

                                    </td>
                                    <td class="create_btn">
                                        @php
                                        $taxonomy_parent_name = \App\Model\Taxonomy::where('taxonomy_recordid', $value->taxonomy_recordid)->first();
                                        $taxonomy_info_list = \App\Model\Taxonomy::where('taxonomy_parent_name', 'LIKE','%'. $value->taxonomy_recordid.'%')->get();

                                        $taxonomy_array = [];
                                        foreach ($taxonomy_info_list as $value1) {
                                        $taxonomy_array[$value1->taxonomy_recordid] = '- ' . $value1->taxonomy_name;
                                        $taxonomy_child_list = \App\Model\Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value1->taxonomy_recordid . '%')->get();
                                        if ($taxonomy_child_list) {
                                        foreach ($taxonomy_child_list as $value2) {
                                        $taxonomy_array[$value2->taxonomy_recordid] = '-- ' . $value2->taxonomy_name;
                                        $taxonomy_child_list1 = \App\Model\Taxonomy::where('taxonomy_parent_name',
                                        'LIKE', '%' . $value2->taxonomy_recordid . '%')->get();
                                        if ($taxonomy_child_list1) {
                                        foreach ($taxonomy_child_list1 as $value3) {
                                        $taxonomy_array[$value3->taxonomy_recordid] = '--- ' . $value3->taxonomy_name;
                                        }
                                        }
                                        }
                                        }
                                        }
                                        $taxonomy_array['create_new'] = '+ Create New';
                                        @endphp
                                        {!!
                                        Form::select('service_eligibility_term['.$key.'][]',$taxonomy_array,isset($selected_eligibility_ids[$value->taxonomy_recordid]) ? $selected_eligibility_ids[$value->taxonomy_recordid] : [],['class'
                                        => 'form-control selectpicker service_eligibility_term','id' => 'service_eligibility_term_'.$key,'multiple' => true,'data-size' => '5','data-live-search' => 'true']) !!}
                                        <input type="hidden" name="service_eligibility_term_type[]"
                                            id="service_eligibility_term_type_{{ $key }}" value="old">
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData">
                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                        </a>
                                    </td>
                                    {{-- <td class="text-center">
                                        <a href="javascript:void(0)" class="removePhoneData" style="color:red;"> <i class="fa fa-minus-circle" aria-hidden="true"></i> </a>
                                    </td> --}}
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>
                                        {!!
                                        Form::select('service_eligibility_type[]',$service_eligibility_types,null,['class'
                                        => 'form-control selectpicker service_eligibility_type','placeholder' => 'Select
                                        Type','id' => 'service_eligibility_type_0']) !!}

                                    </td>
                                    <td class="create_btn">
                                        {!! Form::select('service_eligibility_term[0][]',[],null,['class' => 'form-control selectpicker service_eligibility_term','id' => 'service_eligibility_term_0','multiple' => 'true','data-size' => '5','data-live-search' => 'true']) !!}
                                        <input type="hidden" name="service_eligibility_term_type[]"
                                            id="service_eligibility_term_type_0" value="old">
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a href="#" class="plus_delteicon btn-button ">
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
{{-- service eligibility term modal --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
    id="create_new_service_eligibility_term">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close serviceEligibilityTermCloseButton"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Service Eligibility Term</h4>
                </div>
                <div class="modal-body all_form_field">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Service Eligibility Term</label>
                                <input type="text" class="form-control" placeholder="Service Eligibility Term"
                                    id="service_eligibility_term_p">
                                <input type="hidden" name="service_eligibility_term_index_p" value=""
                                    id="service_eligibility_term_index_p">
                                <span id="service_eligibility_term_error" style="display: none;color:red">Service
                                    Eligibility Term is required!</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger btn-lg btn_delete red_btn serviceEligibilityTermCloseButton">Close</button>
                    <button type="button" id="serviceEligibilityTermSubmit"
                        class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                </div>
        </div>
    </div>
</div>
{{-- End here --}}
<script>
    $(document).on("change",'div > .service_eligibility_type', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[3] : ''
        if(value == ''){
            $('#service_eligibility_term_'+index).empty()
            $('#service_eligibility_term_'+index).val('')
            $('#service_eligibility_term_'+index).selectpicker('refresh')
            return false
        }
        $.ajax({
            url : '{{ route("getTaxonomyTerm") }}',
            method : 'get',
            data : {value},
            success: function (response) {
                let data = response.data
                $('#service_eligibility_term_'+index).empty()
                // $('#service_eligibility_term_'+index).append('<option value="">Select term</option>');
                $.each(data,function(i,v){
                    $('#service_eligibility_term_'+index).append('<option value="'+i+'">'+v+'</option>');
                })
                $('#service_eligibility_term_'+index).append('<option value="create_new">+ Create New</option>');
                $('#service_eligibility_term_'+index).val('')
                $('#service_eligibility_term_'+index).selectpicker('refresh')
            },
            error : function (error) {
                console.log(error)
            }
        })
    })
    $(document).on("change",'div >.service_eligibility_term', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let text = $( "#"+id+" option:selected" ).text();
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[3] : ''

        if(value.includes('create_new')){
            $('#service_eligibility_term_index_p').val(index)
            $('#create_new_service_eligibility_term').modal('show')
        }else if(value.includes(text)){
            $('#service_eligibility__type_'+index).val('new')
        }else{
            $('#service_eligibility__type_'+index).val('old')
        }
    })
    $('#serviceEligibilityTermSubmit').click(function () {

        // let service_eligibility_term = $('#service_eligibility_term_p').val()
        // let index = $('#service_eligibility_term_index_p').val()
        // if($('#service_eligibility_term_p').val() == ''){
        //         $('#service_eligibility_term_error').show()
        //     setTimeout(() => {
        //         $('#service_eligibility_term_error').hide()
        //     }, 5000);
        //     return false
        // }
        // $('#service_eligibility_term_type_'+index).val('new')
        // // $('#service_eligibility_term_'+index+" option:last").prev().append('<option value="'+service_eligibility_term+'">'+service_eligibility_term+'</option>');
        // $('<option value="'+service_eligibility_term+'">'+service_eligibility_term+'</option>').insertAfter($('#service_eligibility_term_'+index+" option:last").prev());
        // $('#service_eligibility_term_'+index).val(service_eligibility_term)
        // $('#service_eligibility_term_'+index).selectpicker('refresh')
        // $('#create_new_service_eligibility_term').modal('hide')
        // $('#service_eligibility_term_p').val('')
        // $('#service_eligibility_term_index_p').val('')

        $('#loading').show()
        let service_eligibility_term = $('#service_eligibility_term_p').val()
        let index = $('#service_eligibility_term_index_p').val()
        let eligibility_type_recordid = $('#service_eligibility_type_'+index).val()
        let _token = "{{ csrf_token() }}"
        let service_recordid = "{{ $service->service_recordid }}"
        let organization_recordid = "{{ $service->service_organization }}"
        $.ajax({
            url : '{{ route("saveEligibilityTaxonomyTerm") }}',
            method : 'post',
            data : {eligibility_type_recordid,service_eligibility_term,_token,service_recordid,organization_recordid},
            success: function (response) {
                $('#loading').hide()
                alert('Thank you for submitting a new term. It is being evaluated by the system administrators. We will let you know if it becomes available.');
                $('#service_eligibility_type_'+index).val('')
                $('#service_eligibility_term_'+index).empty()
                $('#service_eligibility_term_'+index).selectpicker('refresh')
                $('#service_eligibility_type_'+index).selectpicker('refresh')
                $('#create_new_service_eligibility_term').modal('hide')
                $('#service_eligibility_term_p').val('')
            },
            error : function (error) {
                $('#loading').hide()
                $('#create_new_service_eligibility_term').modal('hide')
                console.log(error)
            }
        })
    })
    $('.serviceEligibilityTermCloseButton').click(function () {

        let detail_term = $('#service_eligibility_term_p').val()
        let index = $('#service_eligibility_term_index_p').val()
        $('#service_eligibility_term_type_'+index).val('old')
        $('#service_eligibility_term_'+index).val('');
        $('#service_eligibility_term_'+index).selectpicker('refresh')
        $('#create_new_service_eligibility_term').modal('hide')
        $('#service_eligibility_term_p').val('')
        $('#service_eligibility_term_index_p').val('')
    })
    let se = {{ count($service_eligibility_type_data) > 0 ? count($service_eligibility_type_data) : 1  }}
    $('#addServiceEligibilityTr').click(function(){
        $('#ServiceEligibilityTable tr:last').before('<tr><td><select name="service_eligibility_type[]" id="service_eligibility_type_'+se+'" class="form-control selectpicker service_eligibility_type"><option value="">Select Type</option> @foreach ($service_eligibility_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td class="create_btn"> <select name="service_eligibility_term['+se+'][]" id="service_eligibility_term_'+se+'" class="form-control selectpicker service_eligibility_term" data-size="5" data-live-search="true" multiple></select><input type="hidden" name="service_eligibility_term_type[]" id="service_eligibility_term_type_'+se+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        se++;
    })
</script>
