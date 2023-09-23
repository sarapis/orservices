<h4 class="title_edit text-left mb-25 mt-10">
    Contacts <a class="contactModalOpenButton float-right plus_delteicon bg-primary-color"><img
            src="/frontend/assets/images/plus.png" alt="" title=""></a>
</h4>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="table-responsive">
                <table class="display dataTable table-striped jambo_table table-bordered table-responsive"
                    cellspacing="0" width="100%" style="display: table;">
                    <thead>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Email</th>
                        <th>Visibility</th>
                        <th>Phone</th>
                        <th style="width:100px">&nbsp;</th>
                    </thead>
                    <tbody id="contactsTable">
                        @foreach ($service->contact as $key => $contact)
                            <tr id="contactTr_{{ $key }}">
                                <td>{{ $contact->contact_name }}
                                    <input type="hidden" name="contact_name[]" value="{{ $contact->contact_name }}"
                                        id="contact_name_{{ $key }}">
                                </td>

                                <td>{{ $contact->contact_title }}
                                    <input type="hidden" name="contact_title[]" value="{{ $contact->contact_title }}"
                                        id="contact_title_{{ $key }}">
                                </td>

                                <td class="text-center">{{ $contact->contact_email }}
                                    <input type="hidden" name="contact_email[]" value="{{ $contact->contact_email }}"
                                        id="contact_email_{{ $key }}">
                                </td>
                                <td class="text-center">{{ $contact->visibility }}
                                    <input type="hidden" name="contact_visibility[]"
                                        value="{{ $contact->visibility }}"
                                        id="contact_visibility_{{ $key }}">
                                </td>

                                <td class="text-center">
                                    {{ $contact->phone && count($contact->phone) > 0 ? $contact->phone[0]->phone_number : '' }}
                                    <input type="hidden" name="contact_phone[]"
                                        value="{{ $contact->phone && count($contact->phone) > 0 ? $contact->phone[0]->phone_number : '' }}"
                                        id="contact_phone_{{ $key }}">
                                </td>
                                <td style="vertical-align:middle;">
                                    <a href="javascript:void(0)"
                                        class="contactEditButton plus_delteicon bg-primary-color">
                                        <img src="/frontend/assets/images/edit_pencil.png" alt=""
                                            title="">
                                    </a>
                                    <a href="javascript:void(0)" class="removeContactData plus_delteicon btn-button">
                                        <img src="/frontend/assets/images/delete.png" alt="" title="">
                                    </a>
                                    <input type="hidden" name="contactRadio[]" value="existing"
                                        id="selectedContactRadio_{{ $key }}"><input type="hidden"
                                        name="contact_recordid[]" value="{{ $contact->contact_recordid }}"
                                        id="existingContactIds_{{ $key }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
