{{-- schedule section --}}
<div class="col-md-12">
    <div class="form-group">
        <h4 class="title_edit text-left mb-25 mt-10">
            Regular Schedule
        </h4>
        <div class="table-responsive">
            <table class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
                <thead>
                    <th>Weekday</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Closed All Day</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Monday
                            <input type="hidden" name="byday" value="monday" >
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id' => 'opens_at_location_monday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_monday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_monday" value="1" id="schedule_closed_location_monday" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tuesday
                            <input type="hidden" name="byday" value="tuesday" >
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker' ,'id' => 'opens_at_location_tuesday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_tuesday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_tuesday" value="2" id="schedule_closed_location_tuesday">
                        </td>
                    </tr>
                    <tr>
                        <td>Wednesday
                            <input type="hidden" name="byday" value="wednesday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id' => 'opens_at_location_wednesday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_wednesday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_wednesday" value="3" id="schedule_closed_location_wednesday" >
                        </td>
                    </tr>
                    <tr>
                        <td>Thursday
                            <input type="hidden" name="byday" value="thursday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id' => 'opens_at_location_thursday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at',null, ['class' => 'form-control timePicker','id' => 'closes_at_location_thursday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_thursday" value="4" id="schedule_closed_location_thursday">
                        </td>
                    </tr>
                    <tr>
                        <td>Friday
                            <input type="hidden" name="byday" value="friday">
                        </td>
                        <td>
                            {!! Form::text('opens_at',null, ['class' => 'form-control timePicker','id' => 'opens_at_location_friday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_friday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_friday" id="schedule_closed_location_friday" value="5" >
                        </td>
                    </tr>
                    <tr>
                        <td>Saturday
                            <input type="hidden" name="byday" value="saturday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id' => 'opens_at_location_saturday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_saturday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_saturday" id="schedule_closed_location_saturday" value="6" >
                        </td>
                    </tr>
                    <tr>
                        <td>Sunday
                            <input type="hidden" name="byday" value="sunday">
                        </td>
                        <td>
                            {!! Form::text('opens_at',  null, ['class' => 'form-control timePicker','id' => 'opens_at_location_sunday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_sunday']) !!}
                        </td>

                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_sunday" id="schedule_closed_location_sunday" value="7" >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <h4 class="title_edit text-left mb-25 mt-10">
            Holiday Schedule
        </h4>
        {{-- <label>Holiday Schedule: <a id="addScheduleHolidayLocation"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label> --}}
        <div class="table-responsive">
            <table class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%" id="">
                <thead>
                    <th>Start</th>
                    <th>End</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Closed All Day</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody id="scheduleHolidayLocation">
                    <tr>
                        <td>
                            <input type="date" name="holiday_start_date" id="holiday_start_date_location_0" class="form-control">
                        </td>
                        <td>
                            <input type="date" name="holiday_end_date" id="holiday_end_date_location_0" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="holiday_open_at" id="holiday_open_at_location_0" class="form-control timePicker">
                        </td>
                        <td>
                            <input type="text" name="holiday_close_at" id="holiday_close_at_location_0" class="form-control timePicker">
                        </td>
                        <td>
                            <input type="checkbox" name="holiday_closed" id="holiday_closed_location_0" value="1">
                        </td>
                        <td>
                            <a href="javascript:void(0)" id="addScheduleHolidayLocation" class="plus_delteicon bg-primary-color">
                                <img src="/frontend/assets/images/plus.png" alt="" title="">
                            </a>
                        </td>
                    </tr>
                    {{-- <tr id="addTr">
                        <td colspan="6" class="text-center">
                            <a href="javascript:void(0)" id="addData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- end here --}}
{{-- edit --}}

{{-- schedule section --}}
<div class="col-md-12">
    <div class="form-group">
        {{-- <label class="p-0">Regular Schedule: </label> --}}
        <h4 class="title_edit text-left mb-25 mt-10">
            Regular Schedule
        </h4>
        <div class="table-responsive">
            <table class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
                {{-- <thead>
                            <th colspan="4" class="text-center">Regular Schedule</th>
                        </thead> --}}
                <thead>
                    <th>Weekday</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Closed All Day</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Monday
                            <input type="hidden" name="byday" value="monday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id'
                            => 'opens_at_location_monday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' =>
                            'form-control timePicker','id' => 'closes_at_location_monday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_monday"
                                value="1" id="schedule_closed_location_monday">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tuesday
                            <input type="hidden" name="byday" value="tuesday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker'
                            ,'id' => 'opens_at_location_tuesday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' =>
                            'form-control timePicker','id' => 'closes_at_location_tuesday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_tuesday"
                                value="2" id="schedule_closed_location_tuesday">
                        </td>
                    </tr>
                    <tr>
                        <td>Wednesday
                            <input type="hidden" name="byday" value="wednesday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id'
                            => 'opens_at_location_wednesday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' =>
                            'form-control timePicker','id' => 'closes_at_location_wednesday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_wednesday"
                                value="3" id="schedule_closed_location_wednesday">
                        </td>
                    </tr>
                    <tr>
                        <td>Thursday
                            <input type="hidden" name="byday" value="thursday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id'
                            => 'opens_at_location_thursday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at',null, ['class' => 'form-control timePicker','id'
                            => 'closes_at_location_thursday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_thursday"
                                value="4" id="schedule_closed_location_thursday">
                        </td>
                    </tr>
                    <tr>
                        <td>Friday
                            <input type="hidden" name="byday" value="friday">
                        </td>
                        <td>
                            {!! Form::text('opens_at',null, ['class' => 'form-control timePicker','id'
                            => 'opens_at_location_friday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' =>
                            'form-control timePicker','id' => 'closes_at_location_friday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_friday"
                                id="schedule_closed_location_friday" value="5">
                        </td>
                    </tr>
                    <tr>
                        <td>Saturday
                            <input type="hidden" name="byday" value="saturday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id'
                            => 'opens_at_location_saturday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' =>
                            'form-control timePicker','id' => 'closes_at_location_saturday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_saturday"
                                id="schedule_closed_location_saturday" value="6">
                        </td>
                    </tr>
                    <tr>
                        <td>Sunday
                            <input type="hidden" name="byday" value="sunday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id'
                            => 'opens_at_location_sunday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' =>
                            'form-control timePicker','id' => 'closes_at_location_sunday']) !!}
                        </td>

                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_sunday"
                                id="schedule_closed_location_sunday" value="7">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        {{-- <label class="p-0">Holiday Schedule: </label> --}}
        <h4 class="title_edit text-left mb-25 mt-10">
            Holiday Schedule
        </h4>
        <div class="table-responsive">
            <table class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%" id="">
                <thead>
                    <th>Start</th>
                    <th>End</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Closed All Day</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody id="scheduleHolidayLocation">
                    <tr>
                        <td>
                            <input type="date" name="holiday_start_date"
                                id="holiday_start_date_location_0" class="form-control">
                        </td>
                        <td>
                            <input type="date" name="holiday_end_date"
                                id="holiday_end_date_location_0" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="holiday_open_at"
                                id="holiday_open_at_location_0" class="form-control timePicker">
                        </td>
                        <td>
                            <input type="text" name="holiday_close_at"
                                id="holiday_close_at_location_0" class="form-control timePicker">
                        </td>
                        <td>
                            <input type="checkbox" name="holiday_closed"
                                id="holiday_closed_location_0" value="1">
                        </td>
                        <td>
                            <a href="javascript:void(0)" id="addScheduleHolidayLocation"
                                class="plus_delteicon bg-primary-color">
                                <img src="/frontend/assets/images/plus.png" alt="" title="">
                            </a>
                        </td>
                    </tr>
                    {{-- <tr id="addTr">
                                <td colspan="6" class="text-center">
                                    <a href="javascript:void(0)" id="addData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
                                </td>
                            </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- end here --}}
<div class="tab-pane" id="additional-info-tab">
    <div class="organization_services">
        <div class="card all_form_field">
            <div class="card-block">
                <h4 class="title_edit text-left mb-25 mt-10">
                    Details
                    <div class="d-inline float-right" id="addDetailTr">
                        <a href="javascript:void(0)" id="addDetailData" class="plus_delteicon bg-primary-color">
                            <img src="/frontend/assets/images/plus.png" alt="" title="">
                        </a>
                    </div>
                </h4>
                <div class="row">
                    {{-- service detail section --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="">
                                <table class="table table_border_none" id="DetailTable">
                                    <thead>
                                        <th>Detail Type</th>
                                        <th>Detail Term</th>
                                        <th style="width:60px">&nbsp;</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {!! Form::select('detail_type[]',$detail_types,null,['class' =>
                                                'form-control
                                                selectpicker detail_type','placeholder' => 'Select Detail Type','id' =>
                                                'detail_type_0']) !!}

                                            </td>
                                            <td class="create_btn">
                                                {!! Form::select('detail_term[]',[],null,['class' => 'form-control
                                                selectpicker
                                                detail_term','placeholder' => 'Select Detail Term','id' =>
                                                'detail_term_0']) !!}
                                                <input type="hidden" name="term_type[]" id="term_type_0" value="old">
                                            </td>
                                            <td style="vertical-align: middle">
                                                <a href="#" class="plus_delteicon btn-button">
                                                    <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- end here --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- service_create --}}
{{-- schedule section --}}
<div class="col-md-12">
    <div class="form-group">
        <h4 class="title_edit text-left mb-25 mt-10">
            Regular Schedule
        </h4>
        <div class="table-responsive">
            <table class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
                {{-- <thead>
                    <th colspan="4" class="text-center">Regular Schedule</th>
                </thead> --}}
                <thead>
                    <th>Weekday</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Closed All Day</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Monday
                            <input type="hidden" name="byday" value="monday" >
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id' => 'opens_at_location_monday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_monday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_monday" value="1" id="schedule_closed_location_monday" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tuesday
                            <input type="hidden" name="byday" value="tuesday" >
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker' ,'id' => 'opens_at_location_tuesday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_tuesday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_tuesday" value="2" id="schedule_closed_location_tuesday">
                        </td>
                    </tr>
                    <tr>
                        <td>Wednesday
                            <input type="hidden" name="byday" value="wednesday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id' => 'opens_at_location_wednesday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_wednesday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_wednesday" value="3" id="schedule_closed_location_wednesday" >
                        </td>
                    </tr>
                    <tr>
                        <td>Thursday
                            <input type="hidden" name="byday" value="thursday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id' => 'opens_at_location_thursday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at',null, ['class' => 'form-control timePicker','id' => 'closes_at_location_thursday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_thursday" value="4" id="schedule_closed_location_thursday">
                        </td>
                    </tr>
                    <tr>
                        <td>Friday
                            <input type="hidden" name="byday" value="friday">
                        </td>
                        <td>
                            {!! Form::text('opens_at',null, ['class' => 'form-control timePicker','id' => 'opens_at_location_friday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_friday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_friday" id="schedule_closed_location_friday" value="5" >
                        </td>
                    </tr>
                    <tr>
                        <td>Saturday
                            <input type="hidden" name="byday" value="saturday">
                        </td>
                        <td>
                            {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id' => 'opens_at_location_saturday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_saturday']) !!}
                        </td>
                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_saturday" id="schedule_closed_location_saturday" value="6" >
                        </td>
                    </tr>
                    <tr>
                        <td>Sunday
                            <input type="hidden" name="byday" value="sunday">
                        </td>
                        <td>
                            {!! Form::text('opens_at',  null, ['class' => 'form-control timePicker','id' => 'opens_at_location_sunday']) !!}
                        </td>
                        <td>
                            {!! Form::text('closes_at', null, ['class' => 'form-control timePicker','id' => 'closes_at_location_sunday']) !!}
                        </td>

                        <td style="vertical-align: middle">
                            <input type="checkbox" name="schedule_closed_location_sunday" id="schedule_closed_location_sunday" value="7" >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <h4 class="title_edit text-left mb-25 mt-10">
            Holiday Schedule
        </h4>
        {{-- <label>Holiday Schedule: <a id="addScheduleHolidayLocation"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label> --}}
        <div class="table-responsive">
            <table class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%" id="">
                <thead>
                    <th>Start</th>
                    <th>End</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Closed All Day</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody id="scheduleHolidayLocation">
                    <tr>
                        <td>
                            <input type="date" name="holiday_start_date" id="holiday_start_date_location_0" class="form-control">
                        </td>
                        <td>
                            <input type="date" name="holiday_end_date" id="holiday_end_date_location_0" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="holiday_open_at" id="holiday_open_at_location_0" class="form-control timePicker">
                        </td>
                        <td>
                            <input type="text" name="holiday_close_at" id="holiday_close_at_location_0" class="form-control timePicker">
                        </td>
                        <td>
                            <input type="checkbox" name="holiday_closed" id="holiday_closed_location_0" value="1">
                        </td>
                        <td>
                            <a href="javascript:void(0)" id="addScheduleHolidayLocation" class="plus_delteicon bg-primary-color">
                                <img src="/frontend/assets/images/plus.png" alt="" title="">
                            </a>
                        </td>
                    </tr>
                    {{-- <tr id="addTr">
                        <td colspan="6" class="text-center">
                            <a href="javascript:void(0)" id="addData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- end here --}}
