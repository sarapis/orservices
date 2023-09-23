<div class="organization_services">
    <div class="accordion" id="accordion-condition">
        @php
            $i = 0;
            // $conditionKey = 0;
            // $goalKey = 0;
            // $activitiesKey = 0;
        @endphp
        @foreach ($codes as $condition_key => $condition_values)
                @php
                    $category_data = \App\Model\CodeCategory::whereId($condition_key)->first();
                @endphp
                <div class="card all_form_field">
                    <div class="card-header" id="condition_{{ $i }}">
                        <h4 class="mb-0 card_services_title">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#condition_{{ str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))) }}" aria-expanded="true" aria-controls="condition_{{ str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))) }}"> {{ $category_data->name }} </button>
                        </h4>
                    </div>
                    <div id="condition_{{ str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))) }}" class="collapse hide" aria-labelledby="condition_{{ str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))) }}" data-parent="#accordion-condition">
                        <div class="card-body">
                            @foreach ($condition_values as $data_key => $code_resources)
                                @if ($data_key == 'Condition')
                                <h4 class="mb-0 card_services_title pl-20">
                                    Conditions
                                    <div class="help-tip">
                                        <div>
                                            <p>{{ $help_text->service_conditions ?? '' }}</p>
                                        </div>
                                    </div>
                                </h4>
                                <hr>
                                @foreach ($code_resources as $c_key => $c_datas)
                                    @if ($c_key)
                                    <h5 class="mb-15 card_services_title" style="font-size: 20px;font-weight: 500;text-decoration: underline; padding-right: 25px;">
                                        {{ $c_key }}
                                    </h5>
                                    @endif
                                    @foreach ($c_datas as $code_data)
                                    <div class="row inner-accordion-section pb-15" style="padding-right: 25px;">
                                        <div class="col-md-8" >
                                            <p>
                                                {{ $code_data->description }}
                                            </p>
                                        </div>
                                        <div class="col-md-4" style="padding-right: 25px;">
                                            <select class="form-control selectpicker code_conditions" id="code_conditions" name="code_conditions[]" data-id="{{ $code_data->id }}">
                                                <option value="">Empty</option>
                                                <option value="1_{{ $code_data->id }}" {{ in_array('1_'.$code_data->id,$service_codes) ? 'selected' : '' }}>1</option>
                                                <option value="2_{{ $code_data->id }}" {{ in_array('2_'.$code_data->id,$service_codes) ? 'selected' : '' }}>2</option>
                                                <option value="3_{{ $code_data->id }}" {{ in_array('3_'.$code_data->id,$service_codes) ? 'selected' : '' }}>3</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                                @endif
                            @endforeach
                            @foreach ($condition_values as $data_key => $code_resources)
                                @if ($data_key == 'Goal')
                                <h4 class="mb-0 card_services_title pl-20">
                                    Goals
                                    <div class="help-tip">
                                        <div>
                                            <p>{{ $help_text->service_goals ?? '' }}</p>
                                        </div>
                                    </div>
                                </h4>
                                <hr>
                                @foreach ($code_resources as $g_key => $g_datas)
                                    @if ($g_key)
                                    <h5 class="card_services_title" style="font-size: 20px;font-weight: 500;text-decoration: underline; padding-right: 25px;">
                                        {{ $g_key }}
                                    </h5>
                                    @endif
                                    @foreach ($g_datas as $code_data)
                                    <div class="row inner-accordion-section pb-15" style="padding-right: 25px;">
                                        <div class="col-md-8">
                                            <p>
                                                {{ $code_data->description }}
                                            </p>
                                        </div>
                                        <div class="col-md-4" style="padding-right: 25px;">
                                            <select class="form-control selectpicker goal_conditions" id="goal_conditions" name="goal_conditions[]" data-id="{{ $code_data->id }}">
                                                <option value="">Empty</option>
                                                <option value="1_{{ $code_data->id }}" {{ in_array('1_'.$code_data->id,$service_codes) ? 'selected' : '' }}>1</option>
                                                <option value="2_{{ $code_data->id }}" {{ in_array('2_'.$code_data->id,$service_codes) ? 'selected' : '' }}>2</option>
                                                <option value="3_{{ $code_data->id }}" {{ in_array('3_'.$code_data->id,$service_codes) ? 'selected' : '' }}>3</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                                @endif
                            @endforeach
                            @foreach ($condition_values as $data_key => $code_resources)
                                @if ($data_key == 'Procedure')
                                <div class="accordion" id="accordion-procedure">
                                    <h4 class="mb-0 card_services_title">
                                        Activities
                                        <div class="help-tip">
                                            <div>
                                                <p>{{ $help_text->service_activities ?? '' }}</p>
                                            </div>
                                        </div>
                                    </h4>
                                    <hr>
                                    @foreach ($code_resources as $a_key => $a_datas)

                                    @if ($a_key)
                                        @php
                                            $value = str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))).'|'.str_replace(' ','_',str_replace('/','_',str_replace(',','_',$a_key)));
                                            $data_id = 'procedure_'. str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))) .'|'. str_replace(' ','_',str_replace('/','_',str_replace(',','_',$a_key)));
                                            $checked = in_array(str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))).'|'.str_replace(' ','_',str_replace('/','_',str_replace(',','_',$a_key))),$procedure_grouping) ? 'checked' : '';
                                            $active = in_array(str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))).'|'.str_replace(' ','_',str_replace('/','_',str_replace(',','_',$a_key))),$procedure_grouping) ? 'active' : '';
                                            $display = in_array(str_replace(' ','_',str_replace('/','_',str_replace(',','_',$category_data->name))).'|'.str_replace(' ','_',str_replace('/','_',str_replace(',','_',$a_key))),$procedure_grouping) ? 'show' : 'hide';
                                            $selected = '';
                                            foreach ($a_datas as $key => $tempid) {
                                                if($selected != 'selected')
                                                $selected = in_array('3_'.$tempid->id,$service_codes) ? 'selected' : '';
                                            }

                                            $code_key_data = \App\Model\Code::parent($a_key);
                                        @endphp
                                        <div class="card inner-card  {{ $active }}" style="background-color: {{ $active == 'active' || $selected == 'selected' ? '#f6f6f6' : '#fff' }}">
                                            {{-- <h5 class="mb-15 card_services_title" style="font-size: 20px;font-weight: 500;text-decoration: underline;">{{ $a_key }}</h5> --}}
                                            <div class="inner-card-header checkbox">
                                                <input type="checkbox" name="procedure_grouping[]" id="" class="procedure_grouping" value="{{ $value }}" data-id="{{ $data_id }}" {{ $checked }}>

                                                <button type="button" class="btn btn-link procedure-collapse" data-toggle="collapse" data-target="#procedure_{{ str_replace(' ','_',str_replace('/','_',str_replace(',','_',$a_key))) }}" aria-expanded="true" aria-controls="procedure_{{ str_replace(' ','_',str_replace('/','_',str_replace(',','_',$a_key))) }}" >
                                                    {{ $code_key_data ? $code_key_data->description : '' }}
                                                </button>
                                            </div>
                                            <div class="row inner-accordion-section pb-15 collapse {{ $display  }} {{ $a_key }}" id="procedure_{{ str_replace(' ','_',str_replace('/','_',str_replace(',','_',$a_key))) }}" data-parent="#accordion-procedure">
                                                @foreach ($a_datas as $code_data)
                                                    <div class="col-md-8 " style="padding: 6px 0px 0px 7; !important;">
                                                        <p>
                                                            {{ $code_data->description }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4" style="padding: 6px 0px 0px 7; !important;">
                                                        <input type="checkbox" name="activities_conditions[]" class="activities_conditions" id="activities_{{$i}}_{{$code_data->id}}" class="" {{ in_array('3_'.$code_data->id,$service_codes) ? 'checked' : '' }} value="{{ $code_data->id }}" data-id="{{ $code_data->id }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @php
                $i++;
                // $conditionKey = 0;
                // $goalKey = 0;
                // $activitiesKey = 0;
            @endphp
        @endforeach
    </div>
</div>
<script>
    $(document).ready(() => {
        $('#accordion-condition').on('shown.bs.collapse', function () {
            $('#accordion-condition .collapse.show').closest('.card').addClass('active');

        })
        $('#accordion-condition').on('hide.bs.collapse', function () {
            $('#accordion-condition .collapse.show').closest('.card').removeClass('active');
        })

        $('#accordion-activities').on('shown.bs.collapse', function () {
            $('#accordion-activities .collapse.show').closest('.card').addClass('active');

        })
        $('#accordion-activities').on('hide.bs.collapse', function () {
            $('#accordion-activities .collapse.show').closest('.card').removeClass('active');
        })

        $('#accordion-goals').on('shown.bs.collapse', function () {
            $('#accordion-goals .collapse.show').closest('.card').addClass('active');

        })
        $('#accordion-goals').on('hide.bs.collapse', function () {
            $('#accordion-goals .collapse.show').closest('.card').removeClass('active');
        })
        $('#accordion-procedure').on('shown.bs.collapse', function () {
            $('#accordion-procedure .collapse.show').closest('.inner-card').addClass('active');

        })
        $('#accordion-procedure').on('hide.bs.collapse', function () {
            $('#accordion-procedure .collapse.show').closest('.inner-card').removeClass('active');
        })
    })
    // $(document).on('change','.procedure_grouping',function(){
    //     if ($(this).prop('checked') == false){
    //         let id = $(this).data('id');
    //         $('#'+id).each(function(){
    //             $(this).find('div').find('input').prop('checked',false)
    //             // $(this).find('input').prop('checked',false);
    //         })
    //     }
    // })

</script>
