<div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="phonemodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close phoneCloseButton"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Phone</h4>
                </div>
                <div class="modal-body all_form_field">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input phoneRadio" type="radio" name="phoneRadio" id="phoneRadio2" value="new_data" checked>
                            <label class="form-check-label" for="phoneRadio2"><b style="color: #000">Create New Phone</b></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input phoneRadio" type="radio" name="phoneRadio" id="phoneRadio1" value="existing">
                            <label class="form-check-label" for="phoneRadio1"><b style="color: #000">Use Existing Phone</b></label>
                        </div>
                    </div>
                    <div class="" id="existingPhoneData" style="display: none;">
                        <select name="phones" id="phoneSelectData" class="form-control selectpicker"
                            data-live-search="true" data-size="5">
                            <option value="">Select Phone</option>
                            @foreach ($all_phones as $phone)
                            <option value="{{ $phone }}" data-id="{{ $phone->phone_recordid }}">
                                {{ $phone->phone_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="newPhoneData">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input class="form-control selectpicker" type="text" id="phone_number_p" name="phone_number" value="">
                                    <span id="phone_number_error" style="display: none;color:red">Phone Number is required!</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Extension </label>
                                    <input class="form-control selectpicker" type="text" id="phone_extension_p" name="phone_extension" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Type </label>
                                    {!! Form::select('phone_type_p',$phone_type,array_search('Voice', $phone_type->toArray()),['class' => 'form-control selectpicker','data-live-search' => 'true','id' => 'phone_type_p','data-size' => 5,'placeholder' => 'select phone type'])!!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Language </label>
                                    {!! Form::select('phone_language_p[]',$phone_languages,[],['class' => 'form-control selectpicker phone_language','data-size' => 5,'data-live-search' => 'true',"multiple" => true,"id" => "phone_language_p"]) !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Phone Description </label>
                                    <input type="text" class="form-control" name="phone_description_p" id="phone_description_p" value="">
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check form-check-inline" style="margin-top: -10px;">
                                        <input class="form-check-input " type="radio" name="main_priority_p" id="main_priority_p">
                                        <label class="form-check-label" for="main_priority_p">Main Priority</label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-lg btn_delete red_btn phoneCloseButton">Close</button>
                    <button type="button" id="phoneSubmit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let editPhoneData = false;
    let phoneRadioValue = 'new_data'
    let phone_languages = <?php echo(json_encode($phone_languages)) ?>;
    let phone_types = <?php echo(json_encode($phone_type)) ?>;
    let phone_language_data = JSON.parse($('#phone_language_data').val())
    // $(document).on('change','div > .phone_language',function () {
    //     let value = $(this).val()
    //     let id = $(this).attr('id')
    //     let idsArray = id ? id.split('_') : []
    //     let index = idsArray.length > 0 ? idsArray[2] : ''
    //     phone_language_data[index] = value
    //     $('#phone_language_data').val(JSON.stringify(phone_language_data))
    // })
    $('.phoneRadio').on('change',function () {
        let value = $(this).val()
        phoneRadioValue = value
        if(value == 'new_data'){
            $('#newPhoneData').show()
            $('#existingPhoneData').hide()
        }else{
            $('#newPhoneData').hide()
            $('#existingPhoneData').show()
        }
    })
    let sp =  "{{ isset($service) && isset( $service->phone) ? count($service->phone) : '0' }}";

    $('#phoneSubmit').click(function(){
        let phone_number_p = ''
        let phone_extension_p = ''
        let phone_type_p = ''
        let phone_type_text = ''
        let phone_language_p = ''
        let phone_language_text = ''
        let phone_description_p = ''
        let phone_recordid_p = ''
        let main_priority_p = ''
        phone_language_data = JSON.parse($('#phone_language_data').val())
        if(phoneRadioValue == 'new_data' && $('#phone_number_p').val() == ''){
                $('#phone_number_error').show()
            setTimeout(() => {
                $('#phone_number_error').hide()
            }, 5000);
            return false
        }
        if(phoneRadioValue == 'new_data'){
            phone_number_p = $('#phone_number_p').val()
            phone_extension_p = $('#phone_extension_p').val()
            phone_type_p = $('#phone_type_p').val()
            phone_language_p = $('#phone_language_p').val()
            // phone_language_text = $('#phone_language_p').text()
            phone_description_p = $('#phone_description_p').val()
            main_priority_p = $('#main_priority_p').val()
        }else{
            let data = JSON.parse($('#phoneSelectData').val())
            phone_number_p = data.phone_number ? data.phone_number : ''
            phone_extension_p = data.phone_extension ? data.phone_extension : ''
            phone_type_p = data.phone_type ? data.phone_type : ''
            phone_language_p = data.phone_language ? data.phone_language.split(',') : ''
            phone_recordid_p = data.phone_recordid ? data.phone_recordid : ''
            phone_description_p = data.phone_description ? data.phone_description : ''
            main_priority_p = data.main_priority ? '1' : ''

            // contact_service_p = serviceIds ? serviceIds.split(',') : []
            // contact_phone_p = data.phone && data.phone.length > 0 && data.phone[0].phone_number ? data.phone[0].phone_number : ''
        }
        let phone_language_ids = phone_language_p ? phone_language_p : []
        phone_language_ids.forEach(function(v,i){
            if(v in phone_languages){
                if(i == 0){
                    phone_language_text = phone_languages[v]
                }else{
                    phone_language_text = phone_language_text +' ,'+ phone_languages[v]
                }
            }
        })
        phone_type_text = phone_types[phone_type_p] ? phone_types[phone_type_p] : ''
        if(editPhoneData == false){
            phone_language_data[sp] = phone_language_p
            $('#phonesTable').append('<tr id="phoneTr_'+sp+'"><td>'+phone_number_p+'<input type="hidden" name="service_phones[]" value="'+phone_number_p+'" id="phone_number_'+sp+'"></td><td>'+phone_extension_p+'<input type="hidden" name="phone_extension[]" value="'+phone_extension_p+'" id="phone_extension_'+sp+'"></td><td class="text-center">'+phone_type_text+'<input type="hidden" name="phone_type[]" value="'+phone_type_p+'" id="phone_type_'+sp+'"></td><td class="text-center">'+phone_language_text+'<input type="hidden" name="phone_language[]" value="'+phone_language_p+'" id="phone_language_'+sp+'"></td><td class="text-center">'+phone_description_p+'<input type="hidden" name="phone_description[]" value="'+phone_description_p+'" id="phone_description_'+sp+'"></td><td class="text-center"><div class="form-check form-check-inline" style="margin-top: -10px;"><input class="form-check-input " type="radio" name="main_priority[]" id="main_priority'+sp+'" value="'+sp+'" ><label class="form-check-label" for="main_priority'+sp+'"></label></div></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="phoneEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeServicePhoneData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="phoneRadio[]" value="'+phoneRadioValue+'" id="selectedPhoneRadio_'+sp+'"><input type="hidden" name="phone_recordid[]" value="'+phone_recordid_p+'" id="existingPhoneIds_'+sp+'"></td></tr>');
            //
            sp++;
        }else{
            if(selectedPhoneTrId){
                phoneRadioValue = $('#selectedPhoneRadio_'+selectedPhoneTrId).val()
                phone_recordid_p = $('#existingPhoneIds_'+selectedPhoneTrId).val()
                phone_language_data[selectedPhoneTrId] = phone_language_p
                $('#phoneTr_'+selectedPhoneTrId).empty()
                $('#phoneTr_'+selectedPhoneTrId).append('<td>'+phone_number_p+'<input type="hidden" name="service_phones[]" value="'+phone_number_p+'" id="phone_number_'+selectedPhoneTrId+'"></td><td>'+phone_extension_p+'<input type="hidden" name="phone_extension[]" value="'+phone_extension_p+'" id="phone_extension_'+selectedPhoneTrId+'"></td><td class="text-center">'+phone_type_text+'<input type="hidden" name="phone_type[]" value="'+phone_type_p+'" id="phone_type_'+selectedPhoneTrId+'"></td><td class="text-center">'+phone_language_text+'<input type="hidden" name="phone_language[]" value="'+phone_language_p+'" id="phone_language_'+selectedPhoneTrId+'"></td><td class="text-center">'+phone_description_p+'<input type="hidden" name="phone_description[]" value="'+phone_description_p+'" id="phone_description_'+selectedPhoneTrId+'"></td><td class="text-center"><div class="form-check form-check-inline" style="margin-top: -10px;"><input class="form-check-input " type="radio" name="main_priority[]" id="main_priority'+selectedPhoneTrId+'" value="'+selectedPhoneTrId+'" ><label class="form-check-label" for="main_priority'+selectedPhoneTrId+'"></label></div></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="phoneEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeServicePhoneData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="phoneRadio[]" value="'+phoneRadioValue+'" id="selectedPhoneRadio_'+selectedPhoneTrId+'"><input type="hidden" name="phone_recordid[]" value="'+phone_recordid_p+'" id="existingPhoneIds_'+selectedPhoneTrId+'"></td>')
                //
            }
        }


        $('#phoneSelectData').val('')
        $('#phone_number_p').val('')
        $('#phone_extension_p').val('')
        $('#phone_type_p').val('')
        $('#phone_language_p').val('')
        $('#phone_description_p').val('')

        $('#phone_type_p').selectpicker('refresh')
        $('#phone_language_p').selectpicker('refresh')

        $('#phone_language_data').val(JSON.stringify(phone_language_data))
        $('.selectpicker').selectpicker('refresh');

        $('#phonemodal').modal('hide');

        editPhoneData = false
        selectedPhoneTrId = ''
    })
    $(document).on('click', '.phoneModalOpenButton', function(){
        $('#phoneSelectData').val('')
        $('#phone_number_p').val('')
        $('#phone_extension_p').val('')
        $('#phone_type_p').val("<?php echo array_search('Voice', $phone_type->toArray()) ?>")
        $('#phone_language_p').val('')
        $('#phone_description_p').val('')
        $('#phone_type_p').selectpicker('refresh')
        $('#phone_language_p').selectpicker('refresh')
        $('.selectpicker').selectpicker('refresh');
        $("input[name=phoneRadio][value='new_data']").prop("checked",true);
        $("input[name=phoneRadio][value='existing']").prop("disabled",false);
        editPhoneData = false
        phoneRadioValue = 'new_data'
        $('#newPhoneData').show()
        $('#existingPhoneData').hide()
        $('#phonemodal').modal('show');
    });
    $(document).on('click', '.phoneCloseButton', function(){
        editPhoneData = false
        $("input[name=phoneRadio][value='existing']").prop("disabled",false);
        $('#phonemodal').modal('hide');
    });
    $(document).on('click', '.phoneEditButton', function(){
        editPhoneData = true
        var $row = jQuery(this).closest('tr');
        // var $columns = $row.find('td');
        // console.log()
        let phoneTrId = $row.attr('id')
        let id_new = phoneTrId.split('_');
        let id = id_new[1]
        selectedPhoneTrId = id
        $('.selectpicker').selectpicker('refresh');


        // $('.contactRadio').val()
        let radioValue = $("#selectedContactRadio_"+id).val();
        let phone_number_p = $('#phone_number_'+id).val()
        let phone_extension_p = $('#phone_extension_'+id).val()
        let phone_type_p = $('#phone_type_'+id).val()
        let phone_language_p = phone_language_data[id]
        let phone_description_p = $('#phone_description_'+id).val()
        let phone_recordid_p = $('#existingPhoneIds_'+id).val()

        $('.selectpicker').selectpicker('refresh');
        // contactRadioValue = radioValue
        phoneRadioValue = 'new_data'
        // $("input[name=contactRadio][value="+radioValue+"]").prop("checked",true);
        $("input[name=phoneRadio][value='new_data']").prop("checked",true);
        $("input[name=phoneRadio][value='existing']").prop("disabled",true);
        // if(radioValue == 'new_data'){
            $('#phone_number_p').val(phone_number_p)
            $('#phone_extension_p').val(phone_extension_p)
            $('#phone_type_p').val(phone_type_p)
            $('#phone_language_p').val(phone_language_p)
            $('#phone_description_p').val(phone_description_p)
            $('#phoneSelectData').val('')
            $('#phone_type_p').selectpicker('refresh')
            $('#phone_language_p').selectpicker('refresh')
            $('#newPhoneData').show()
            $('#existingPhoneData').hide()

        $('#phonemodal').modal('show');
    });
    $(document).on('click','.removeServicePhoneData',function(){
        var $row = jQuery(this).closest('tr');
        let TrId = $row.attr('id')
        let id_new = TrId.split('_');
        let id = id_new[1]
        let name = id_new[0]
        let deletedId = parseInt(id)
        let phone_language_ids = JSON.parse($('#phone_language_data').val())
        phone_language_ids[deletedId] ? phone_language_ids.splice(deletedId,1) : phone_language_ids
        sp = phone_language_ids.length
        $('#phone_language_data').val(JSON.stringify(phone_language_ids))
        phone_language_data = phone_language_ids
        $(this).closest('tr').remove()
        $('#phonesTable').each(function () {
            var table = $(this);
            table.find('tr').each(function (i) {
                $(this).find('td').each(function(j){
                    if(j == 0){
                        $(this).find('input').attr('id','phone_number_'+i)
                    }else if(j == 1){
                        $(this).find('input').attr('id','phone_extension_'+i)
                    }else if(j == 2){
                        $(this).find('input').attr('id','phone_type_'+i)
                    }else if(j == 4){
                        $(this).find('input').attr('id','phone_description_'+i)
                    }
                })
                $(this).attr("id","phoneTr_"+i)
            });
        });
    })
</script>
