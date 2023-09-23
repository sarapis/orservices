
<ul class="nav nav-tabs tabpanel_above">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#programs-tab">
            <h4 class="card_services_title">Programs
            </h4>
        </a>
    </li>
    @if ($layout->show_classification == 'yes')
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#require-document-tab">
                <h4 class="card_services_title">Required Documents
                </h4>
            </a>
        </li>
        {{-- <li class="nav-item">
    <a class="nav-link " data-toggle="tab" href="#sdoh-codes-tab">
        <h4 class="card_services_title">SDOH Codes
        </h4>
    </a>
</li> --}}
    @endif
</ul>
<div class="card all_form_field">
    <div class="card-block" style="border-radius: 0 0 12px 12px">
        <div class="tab-content">
            <div class="tab-pane active" id="programs-tab">
                <h4 class="title_edit text-left mb-25 mt-10"> Programs <a class="programModalOpenButton float-right plus_delteicon bg-primary-color"><img src="/frontend/assets/images/plus.png" alt="" title=""></a>
                </h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="table-responsive">
                                <table class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                    cellspacing="0" width="100%" style="display: table;">
                                    <thead>
                                        <th>Name</th>
                                        <th>Alternative Name</th>
                                        <th>Description</th>
                                        {{-- <th>Relation</th> --}}
                                        <th style="width:100px">&nbsp;</th>
                                    </thead>
                                    <tbody id="programsTable">
                                        @isset($service)

                                        @foreach ($service->program as $key => $program)
                                            <tr id="programTr_{{ $key }}">
                                                <td>{{ $program->name }}
                                                    <input type="hidden" name="program_name[]" value="{{ $program->name }}" id="program_name_{{ $key }}">
                                                </td>

                                                <td>{{ $program->alternate_name }}
                                                    <input type="hidden" name="program_alternate_name[]" value="{{ $program->alternate_name }}" id="program_alternate_name_{{ $key }}">
                                                </td>

                                                <td>{{ $program->description }}
                                                    <input type="hidden" name="program_description[]" value="{{ $program->description }}" id="program_description_{{ $key }}">
                                                </td>

                                                {{-- <td class="text-center">{{ $program->program_service_relationship }}
                                                    <input type="hidden" name="program_service_relationship[]"
                                                        value="{{ $program->program_service_relationship }}"
                                                        id="program_service_relationship_{{ $key }}">
                                                </td> --}}
                                                <td style="vertical-align:middle;">
                                                    <a href="javascript:void(0)"
                                                        class="programEditButton plus_delteicon bg-primary-color">
                                                        <img src="/frontend/assets/images/edit_pencil.png" alt=""
                                                            title="">
                                                    </a>
                                                    <a href="javascript:void(0)"
                                                        class="removeProgramData plus_delteicon btn-button">
                                                        <img src="/frontend/assets/images/delete.png" alt=""
                                                            title="">
                                                    </a>
                                                    <input type="hidden" name="programRadio[]" value="existing"
                                                        id="selectedProgramRadio_{{ $key }}"><input type="hidden"
                                                        name="program_recordid[]" value="{{ $program->program_recordid }}"
                                                        id="existingProgramIds_{{ $key }}">
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
            </div>
            <div class="tab-pane" id="require-document-tab">
                <h4 class="title_edit text-left mb-25 mt-10">
                    Required Documents
                    <a class="requiredocumentmodalOpenButton float-right plus_delteicon bg-primary-color"><img src="/frontend/assets/images/plus.png" alt="" title=""></a>
                </h4>
                @include('frontEnd.services.require_document')
            </div>
        </div>
    </div>
</div>
{{-- program modal --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="programmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close programCloseButton"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Program</h4>
            </div>
            <div class="modal-body all_form_field">
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input programRadio" type="radio" name="programRadioP"
                            id="programRadio2" value="new_data">
                        <label class="form-check-label" for="programRadio2"><b style="color: #000">Add New Program</b></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input programRadio" type="radio" name="programRadioP" id="programRadio1" value="existing" checked>
                        <label class="form-check-label" for="programRadio1"><b style="color: #000">Select Existing Program</b></label>
                    </div>

                </div>
                <div class="" id="existingProgramData">
                    <select name="programs" id="programSelectData" class="form-control selectpicker"
                        data-live-search="true" data-size="5">
                        <option value="">Select Programs</option>
                        @foreach ($all_programs as $program)
                            <option value="{{ $program }}" data-id="{{ $program->program_recordid }}">
                                {{ $program->name . ' - ' . ($program->organization && count($program->organization) > 0 ? $program->organization[0]->organization_name : '') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="newProgramData" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Name" id="program_name_p">
                                <span id="program_name_error" style="display: none;color:red">Program Name is required!</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alternative Name</label>
                                <input type="text" class="form-control" placeholder="Alternative Name" id="program_alternate_name_p">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" placeholder="Program description" id="program_description_p">
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Relation: </label>
                                {!! Form::select('program_service_relationship_p', ['required' => 'Required', 'prefered' => 'Prefered', 'not_required' => 'Not Required'], null, ['class' => 'form-control selectpicker', 'data-size' => 5, ' data-live-search' => 'true', 'id' => 'program_service_relationship_p']) !!}
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-danger btn-lg btn_delete red_btn programCloseButton">Close</button>
                <button type="button" id="programSubmit"
                    class="btn btn-primary btn-lg btn_padding green_btn">Add</button>
            </div>
        </div>
    </div>
</div>
{{-- End here --}}

<script>
    let programRadioValue = 'existing'
    $('.programRadio').on('change', function() {
        let value = $(this).val()
        programRadioValue = value
        if (value == 'new_data') {
            $('#newProgramData').show()
            $('#existingProgramData').hide()
        } else {
            $('#newProgramData').hide()
            $('#existingProgramData').show()
        }
    })
    let pr = "{{ isset($service) ? count($service->program) : '0' }}";
    // let program_names = JSON.parse($('#program_names').val())
    // let program_descriptions = JSON.parse($('#program_descriptions').val())

    // let program_service_relationships = JSON.parse($('#program_service_relationships').val())

    $('#programSubmit').click(function() {
        let program_name_p = ''
        let program_description_p = ''
        let program_alternate_name_p = ''
        let program_service_relationship_p = ''
        let program_recordid_p = ''
        if (programRadioValue == 'new_data' && $('#program_name_p').val() == '') {
            $('#program_name_error').show()
            setTimeout(() => {
                $('#program_name_error').hide()
            }, 5000);
            return false
        }

        if (programRadioValue == 'new_data') {
            program_name_p = $('#program_name_p').val()
            program_description_p = $('#program_description_p').val()
            program_alternate_name_p = $('#program_alternate_name_p').val()
            // program_service_relationship_p = $('#program_service_relationship_p').val()
            // contactsTable
        } else {
            let data = JSON.parse($('#programSelectData').val())
            program_name_p = data.name ? data.name : ''
            program_description_p = data.description ? data.description : ''
            program_alternate_name_p = data.alternate_name ? data.alternate_name : ''
            // program_service_relationship_p = data.program_service_relationship ? data.program_service_relationship : ''
            program_recordid_p = data.program_recordid ? data.program_recordid : ''
        }
        if (editProgramData == false) {
            // <td class="text-center">' +
            //     program_service_relationship_p +
            //     '<input type="hidden" name="program_service_relationship[]" value="' +
            //     program_service_relationship_p +
            //     '" id="program_service_relationship_' + pr +
            //     '">
            $('#programsTable').append('<tr id="programTr_' + pr + '"><td>' + program_name_p + '<input type="hidden" name="program_name[]" value="' + program_name_p + '" id="program_name_' + pr + '"></td><td>' + program_alternate_name_p + '<input type="hidden" name="program_alternate_name[]" value="' + program_alternate_name_p + '" id="program_alternate_name_' + pr + '"></td><td>' + program_description_p + '<input type="hidden" name="program_description[]" value="' + program_description_p + '" id="program_description_' + pr + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="programEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeProgramData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="programRadio[]" value="' + programRadioValue + '" id="selectedProgramRadio_' + pr + '"><input type="hidden" name="program_recordid[]" value="' + program_recordid_p + '" id="existingProgramIds_' + pr + '"></td></tr>');
            pr++;
        } else {
            if (selectedProgramTrId) {
                programRadioValue = $('#selectedProgramRadio_' + selectedProgramTrId).val()
                program_recordid_p = $('#existingProgramIds_' + selectedProgramTrId).val()
                // program_names[selectedProgramTrId] = program_name_p
                // program_descriptions[selectedProgramTrId] = program_description_p

                // program_service_relationships[selectedProgramTrId] = program_service_relationship_p

                // </td><td class="text-center">' +
                // program_service_relationship_p +
                // '<input type="hidden" name="program_service_relationship[]" value="' +
                // program_service_relationship_p + '" id="program_service_relationship_' +
                // selectedProgramTrId +
                // '"></td>
                $('#programTr_' + selectedProgramTrId).empty()
                $('#programTr_' + selectedProgramTrId).append('<td>' + program_name_p + '<input type="hidden" name="program_name[]" value="' + program_name_p + '" id="program_name_' + selectedProgramTrId + '"></td><td>' + program_alternate_name_p + '<input type="hidden" name="program_alternate_name[]" value="' + program_alternate_name_p + '" id="program_alternate_name_' + selectedProgramTrId + '"></td><td>' + program_description_p + '<input type="hidden" name="program_description[]" value="' + program_description_p + '" id="program_description_' + selectedProgramTrId + '"><td style="vertical-align:middle;"><a href="javascript:void(0)" class="programEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="programRadio[]" value="' + programRadioValue + '" id="selectedProgramRadio_' + selectedProgramTrId + '"><input type="hidden" name="program_recordid[]" value="' + program_recordid_p + '" id="existingProgramIds_' + selectedProgramTrId + '"></td>')
            }
        }
        // $('#program_names').val(JSON.stringify(program_names))
        // $('#program_descriptions').val(JSON.stringify(program_descriptions))

        // $('#program_service_relationships').val(JSON.stringify(program_service_relationships))

        $('#programSelectData').val('')
        $('#program_name_p').val('')
        $('#program_description_p').val('')
        $('#program_alternate_name_p').val('')
        $('#program_service_relationship_p').val('')

        $('#program_service_relationship_p').selectpicker('refresh')

        $('#programmodal').modal('hide');

        // pr = JSON.parse($('#program_names').val()).length;
        editProgramData = false
        selectedProgramTrId = ''
    })
    $(document).on('click', '.removeProgramData', function() {
        var $row = jQuery(this).closest('tr');
        if (confirm("Are you sure want to remove this program?")) {
            let programTrId = $row.attr('id')
            let id_new = programTrId.split('_');
            let id = id_new[1]
            let deletedId = id

            // let program_name_val = JSON.parse($('#program_names').val())
            // let program_description_val = JSON.parse($('#program_descriptions').val())
            // let program_service_relationship_val = JSON.parse($('#program_service_relationships').val())

            // program_name_val.splice(deletedId, 1)
            // program_description_val.splice(deletedId, 1)
            // program_service_relationship_val.splice(deletedId, 1)

            // $('#program_names').val(JSON.stringify(program_name_val))
            // $('#program_descriptions').val(JSON.stringify(program_description_val))
            // $('#program_service_relationships').val(JSON.stringify(program_service_relationship_val))
            $(this).closest('tr').remove()

            // $('#programsTable').each(function() {
            //     var table = $(this);
            //     table.find('tr').each(function(i) {
            //         $(this).attr("id", "programTr_" + i)
            //     });
            //     //Code here
            // });
            // // s = program_name_val.length
            // pr = 1
        }
        return false
    });
    $(document).on('click', '.programCloseButton', function() {
        editProgramData = false
        $('#programSelectData').val('')
        $('#program_name_p').val('')
        $('#program_alternate_name_p').val('')
        $('#program_description_p').val('')
        $('#program_service_relationship_p').val('')

        $('#program_service_relationship_p').selectpicker('refresh')
        $('#programmodal').modal('hide');
    });
    $(document).on('click', '.programModalOpenButton', function() {
        $('#programSelectData').val('')
        $('#program_name_p').val('')
        $('#program_alternate_name_p').val('')
        $('#program_description_p').val('')
        $('#program_service_relationship_p').val('')

        $('.selectpicker').selectpicker('refresh');
        editProgramData = false
        programRadioValue = 'existing'
        // $("input[name=programRadio][value='new_data']").prop("checked", true);
        $("input[name=programRadioP][value='existing']").prop("checked", true);
        $("input[name=programRadioP][value='existing']").prop("disabled", false);
        $('#program_service_relationship_p').selectpicker('refresh')
        $('#newProgramData').hide()
        $('#existingProgramData').show()
        $('#programmodal').modal('show');
    });
    $(document).on('click', '.programEditButton', function() {
        editProgramData = true
        var $row = jQuery(this).closest('tr');
        // var $columns = $row.find('td');
        // console.log()
        let programTrId = $row.attr('id')
        let id_new = programTrId.split('_');
        let id = id_new[1]
        selectedProgramTrId = id

        let radioValue = $("#selectedProgramRadio_" + id).val();
        let program_name_p = $('#program_name_' + id).val()
        let program_description_p = $('#program_description_' + id).val()
        let program_alternate_name_p = $('#program_alternate_name_' + id).val()
        // let program_service_relationship_p = $('#program_service_relationship_' + id).val()

        programRadioValue = 'new_data'
        $("input[name=programRadioP][value='new_data']").prop("checked", true);
        $("input[name=programRadioP][value='existing']").prop("checked", false);
        $("input[name=programRadioP][value='existing']").prop("disabled", true);
        // if(radioValue == 'new_data'){
        $('#program_name_p').val(program_name_p)
        $('#program_description_p').val(program_description_p)
        $('#program_alternate_name_p').val(program_alternate_name_p)
        // $('#program_service_relationship_p').val(program_service_relationship_p)

        $('#programSelectData').val('')
        // $('#program_service_relationship_p').selectpicker('refresh')
        $('#newProgramData').show()
        $('#existingProgramData').hide()
        $('#programmodal').modal('show');
    });
    // end program section
</script>
