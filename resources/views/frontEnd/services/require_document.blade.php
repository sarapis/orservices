


<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="table-responsive">
                <table class="display dataTable table-striped jambo_table table-bordered table-responsive"
                    cellspacing="0" width="100%" style="display: table;">
                    <thead>
                        <th>Type</th>
                        <th>URL</th>
                        <th>Title (Optional)</th>
                        {{-- <th>Relation</th> --}}
                        <th style="width:100px">&nbsp;</th>
                    </thead>
                    <tbody id="requiredDocumentTable">
                        @isset($service)
                        @foreach ($service->require_documents as $key => $require_document)
                            <tr id="requiredDocumentTr_{{ $key }}">
                                <td>{{ $require_document->document_type }}
                                    <input type="hidden" name="document_type[]" value="{{ $require_document->detail_id }}" id="document_type_{{ $key }}">
                                </td>

                                <td>{{ $require_document->document_link }}
                                    <input type="hidden" name="document_url[]" value="{{ $require_document->document_link }}" id="document_url_{{ $key }}">
                                </td>

                                <td>{{ $require_document->document_title }}
                                    <input type="hidden" name="document_title[]" value="{{ $require_document->document_title }}" id="document_title_{{ $key }}">
                                </td>
                                <td style="vertical-align:middle;">
                                    <a href="javascript:void(0)" class="requireDocumentEditButton plus_delteicon bg-primary-color">
                                        <img src="/frontend/assets/images/edit_pencil.png" alt="" title="">
                                    </a>
                                    <a href="javascript:void(0)" class="removeRequireDocumentData plus_delteicon btn-button">
                                        <img src="/frontend/assets/images/delete.png" alt="" title="">
                                    </a>
                                    <input type="hidden" name="requireDocumentId[]" value="{{ $require_document->id }}" id="existingRequireDocumentIds_{{ $key }}">
                                </td>
                            </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="deletedRequireDocument" id="deletedRequireDocument">
{{-- program modal --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="requiredocumentmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close requiredocumentCloseButton"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Required Document</h4>
            </div>
            <div class="modal-body all_form_field">
                <div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="document_type_p" id="document_type_p" class="form-control">
                                    <option value="">Select</option>
                                    @foreach ($requiredDocumentTypes as $key => $value )
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <span id="document_type_error" style="display: none;color:red">Type is required!</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>URL</label>
                                <input type="text" class="form-control" placeholder="URL" id="document_url_p">
                                <span id="document_url_error" style="display: none;color:red">URL is required!</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Title (Optional)</label>
                                <input type="text" class="form-control" placeholder="Document Title" id="document_title_p">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-lg btn_delete red_btn requiredocumentCloseButton">Close</button>
                <button type="button" id="requireDocumentSubmit" class="btn btn-primary btn-lg btn_padding green_btn">Add</button>
            </div>
        </div>
    </div>
</div>
{{-- End here --}}

<script>
    // let programRadioValue = 'existing'
    // $('.programRadio').on('change', function() {
    //     let value = $(this).val()
    //     programRadioValue = value
    //     if (value == 'new_data') {
    //         $('#newProgramData').show()
    //         $('#existingProgramData').hide()
    //     } else {
    //         $('#newProgramData').hide()
    //         $('#existingProgramData').show()
    //     }
    // })
    let rd = "{{ isset($service) ? count($service->require_documents) : '0' }}";
    // let document_types = JSON.parse($('#document_types').val())
    // let document_titles = JSON.parse($('#document_titles').val())
    let documentTypes = '<?php echo json_encode($requiredDocumentTypes); ?>'
        documentTypes = JSON.parse(documentTypes)
    // let program_service_relationships = JSON.parse($('#program_service_relationships').val())

    $('#requireDocumentSubmit').click(function() {
        let document_type_p = ''
        let document_title_p = ''
        let document_url_p = ''
        let requireDocumentId_p = ''

        document_type_p = $('#document_type_p').val()
        document_id_p = $('#document_type_p').val()
        document_title_p = $('#document_title_p').val()
        document_url_p = $('#document_url_p').val()

        if ($('#document_type_p').val() == '') {
            $('#document_type_error').show()
            setTimeout(() => {
                $('#document_type_error').hide()
            }, 5000);
            return false
        }
        if (document_url_p) {
            var res = document_url_p.match(/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/g);
            if(res == null){
                $('#document_url_error').text('Please format this as a valid url, which usually starts with https:// or http://')
                $('#document_url_error').show()
                setTimeout(() => {
                    $('#document_url_error').hide()
                }, 5000);
                return false;
            }
        }
        // else if(document_url_p == ''){
        //     $('#document_url_error').text('URL is required.')
        //     $('#document_url_error').show()
        //     setTimeout(() => {
        //         $('#document_url_error').hide()
        //     }, 5000);
        //     return false;
        // }

        // if (programRadioValue == 'new_data') {

            // program_service_relationship_p = $('#program_service_relationship_p').val()
            // contactsTable
        // } else {
        //     let data = JSON.parse($('#programSelectData').val())
        //     document_type_p = data.name ? data.name : ''
        //     document_title_p = data.description ? data.description : ''
        //     document_url_p = data.alternate_name ? data.alternate_name : ''
        //     // program_service_relationship_p = data.program_service_relationship ? data.program_service_relationship : ''
        //     requireDocumentId_p = data.program_recordid ? data.program_recordid : ''
        // }
        if (editRequireDocumentData == false) {
            $('#requiredDocumentTable').append('<tr id="requiredDocumentTr_' + rd + '"><td>' + documentTypes[document_type_p] + '<input type="hidden" name="document_type[]" value="' + document_id_p + '" id="document_type_' + rd + '"></td><td>' + document_url_p + '<input type="hidden" name="document_url[]" value="' + document_url_p + '" id="document_url_' + rd + '"></td><td>' + document_title_p + '<input type="hidden" name="document_title[]" value="' + document_title_p + '" id="document_title_' + rd + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="requireDocumentEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeRequireDocumentData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="requireDocumentId[]" value="' + requireDocumentId_p + '" id="existingRequireDocumentIds_' + rd + '"></td></tr>');
            rd++;
        } else {
            if (selectedRequireDocumentTrId) {
                // programRadioValue = $('#selectedProgramRadio_' + selectedRequireDocumentTrId).val()
                requireDocumentId_p = $('#existingRequireDocumentIds_' + selectedRequireDocumentTrId).val()
                // document_types[selectedRequireDocumentTrId] = document_type_p
                // document_titles[selectedRequireDocumentTrId] = document_title_p

                // program_service_relationships[selectedRequireDocumentTrId] = program_service_relationship_p

                // </td><td class="text-center">' +
                // program_service_relationship_p +
                // '<input type="hidden" name="program_service_relationship[]" value="' +
                // program_service_relationship_p + '" id="program_service_relationship_' +
                // selectedRequireDocumentTrId +
                // '"></td>
                $('#requiredDocumentTr_' + selectedRequireDocumentTrId).empty()
                $('#requiredDocumentTr_' + selectedRequireDocumentTrId).append('<td>' + documentTypes[document_type_p] + '<input type="hidden" name="document_type[]" value="' + document_id_p + '" id="document_type_' + selectedRequireDocumentTrId + '"></td><td>' + document_url_p + '<input type="hidden" name="document_url[]" value="' + document_url_p + '" id="document_url_' + selectedRequireDocumentTrId + '"></td><td>' + document_title_p + '<input type="hidden" name="document_title[]" value="' + document_title_p + '" id="document_title_' + selectedRequireDocumentTrId + '"><td style="vertical-align:middle;"><a href="javascript:void(0)" class="requireDocumentEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="requireDocumentId[]" value="' + requireDocumentId_p + '" id="existingRequireDocumentIds_' + selectedRequireDocumentTrId + '"></td>')
                // <input type="hidden" name="programRadio[]" value="' + programRadioValue + '" id="selectedProgramRadio_' + selectedRequireDocumentTrId + '">
            }
        }
        // $('#document_types').val(JSON.stringify(document_types))
        // $('#document_titles').val(JSON.stringify(document_titles))

        // $('#program_service_relationships').val(JSON.stringify(program_service_relationships))

        // $('#programSelectData').val('')
        $('#document_type_p').val('')
        $('#document_title_p').val('')
        $('#document_url_p').val('')

        $('#requiredocumentmodal').modal('hide');

        // pr = JSON.parse($('#document_types').val()).length;
        editRequireDocumentData = false
        selectedRequireDocumentTrId = ''
    })
    $(document).on('click', '.removeRequireDocumentData', function() {
        var $row = jQuery(this).closest('tr');
        if (confirm("Are you sure want to remove this program?")) {
            let requireDocumentTrId = $row.attr('id')
            let id_new = requireDocumentTrId.split('_');
            let id = id_new[1]
            let requireDocumentId = $('#existingRequireDocumentIds_'+id).val();
            let deletedRequireDocument = $('#deletedRequireDocument').val();
            deletedRequireDocument = deletedRequireDocument ? JSON.parse(deletedRequireDocument) : [];
            if(requireDocumentId && !deletedRequireDocument.includes(requireDocumentId)){
                deletedRequireDocument.push(requireDocumentId)
            }
            $('#deletedRequireDocument').val(JSON.stringify(deletedRequireDocument))

            $(this).closest('tr').remove()
        }
        return false
    });
    $(document).on('click', '.requiredocumentCloseButton', function() {
        editRequireDocumentData = false
        // $('#programSelectData').val('')
        $('#document_type_p').val('')
        $('#document_url_p').val('')
        $('#document_title_p').val('')
        $('#requiredocumentmodal').modal('hide');
    });
    $(document).on('click', '.requiredocumentmodalOpenButton', function() {
        // $('#programSelectData').val('')
        $('#document_type_p').val('')
        $('#document_url_p').val('')
        $('#document_title_p').val('')

        $('#document_type_p').selectpicker('refresh');
        editRequireDocumentData = false
        // programRadioValue = 'existing'
        // $("input[name=programRadio][value='new_data']").prop("checked", true);
        // $("input[name=programRadioP][value='existing']").prop("checked", true);
        // $("input[name=programRadioP][value='existing']").prop("disabled", false);
        // $('#newProgramData').hide()
        // $('#existingProgramData').show()
        $('#requiredocumentmodal').modal('show');
    });
    $(document).on('click', '.requireDocumentEditButton', function() {
        editRequireDocumentData = true
        var $row = jQuery(this).closest('tr');
        // var $columns = $row.find('td');
        // console.log()
        let requireDocumentTrId = $row.attr('id')
        let id_new = requireDocumentTrId.split('_');
        let id = id_new[1]
        selectedRequireDocumentTrId = id

        // let radioValue = $("#selectedProgramRadio_" + id).val();
        let document_type_p = $('#document_type_' + id).val()
        let document_title_p = $('#document_title_' + id).val()
        let document_url_p = $('#document_url_' + id).val()

        // let program_service_relationship_p = $('#program_service_relationship_' + id).val()

        // programRadioValue = 'new_data'
        // $("input[name=programRadioP][value='new_data']").prop("checked", true);
        // $("input[name=programRadioP][value='existing']").prop("checked", false);
        // $("input[name=programRadioP][value='existing']").prop("disabled", true);
        // if(radioValue == 'new_data'){
            $('#document_title_p').val(document_title_p)
            $('#document_url_p').val(document_url_p)
            // $('#program_service_relationship_p').val(program_service_relationship_p)

            // $('#programSelectData').val('')
            $('#document_type_p').val(document_type_p)
        $('#document_type_p').selectpicker('refresh')
        // $('#newProgramData').show()
        // $('#existingProgramData').hide()
        $('#requiredocumentmodal').modal('show');
    });
    // end program section
</script>
