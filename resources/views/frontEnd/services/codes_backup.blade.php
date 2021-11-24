<div class="tab-pane" id="conditions-tab">
    <div class="organization_services">
        <div style="top:8px;" >
            <div>
                <p class="service_help_text">
                    {{ $help_text->service_conditions ?? '' }}
                </p>
            </div>
        </div>
        <div class="accordion" id="accordion-condition">
            @php
                $i = 0;
            @endphp
            @foreach ($conditions as $condition_key => $condition_values)
                    <div class="card all_form_field">
                        <div class="card-header" id="condition_{{ $i }}">
                            <h4 class="mb-0 card_services_title">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#condition_{{ str_replace(' ','_',$condition_key) }}" aria-expanded="true" aria-controls="condition_{{ str_replace(' ','_',$condition_key) }}">
                                    {{ $condition_key }}
                                </button>
                            </h4>
                        </div>
                        <div id="condition_{{ str_replace(' ','_',$condition_key) }}" class="collapse hide" aria-labelledby="condition_{{ str_replace(' ','_',$condition_key) }}" data-parent="#accordion-condition">
                            <div class="card-body">
                                @foreach ($condition_values as $data_key => $code_data)
                                    <div class="row inner-accordion-section pb-15">
                                        <div class="col-md-6">
                                            <p>
                                                {{ $code_data->description }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control selectpicker service_category_type" id="code_conditions" name="code_conditions[]">
                                                <option value="">Empty</option>
                                                <option value="1_{{ $code_data->id }}" {{ in_array('1_'.$code_data->id,$service_codes) ? 'selected' : '' }}>1</option>
                                                <option value="2_{{ $code_data->id }}" {{ in_array('2_'.$code_data->id,$service_codes) ? 'selected' : '' }}>2</option>
                                                <option value="3_{{ $code_data->id }}" {{ in_array('3_'.$code_data->id,$service_codes) ? 'selected' : '' }}>3</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
    </div>
</div>
<div class="tab-pane" id="goals-tab">
    <div class="organization_services">
        <div style="top:8px;" >
            <div>
                <p class="service_help_text">
                    {{ $help_text->service_goals ?? '' }}
                </p>
            </div>
        </div>
        <div class="accordion" id="accordion-goals">
            @php
                $i = 0;
            @endphp
            @foreach ($goals as $goal_key => $goal_values)
                    <div class="card all_form_field">
                        <div class="card-header" id="goal_{{ $i }}">
                            <h4 class="mb-0 card_services_title">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#goal_{{ str_replace(' ','_',$goal_key) }}" aria-expanded="true" aria-controls="goal_{{ str_replace(' ','_',$goal_key) }}">
                                    {{ $goal_key }}
                                </button>
                            </h4>
                        </div>
                        <div id="goal_{{ str_replace(' ','_',$goal_key) }}" class="collapse hide" aria-labelledby="goal_{{ str_replace(' ','_',$goal_key) }}" data-parent="#accordion-goals">
                            <div class="card-body">
                                @foreach ($goal_values as $data_key => $code_data)
                                    <div class="row inner-accordion-section pb-15">
                                        <div class="col-md-6">
                                            <p>
                                                {{ $code_data->description }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control selectpicker service_category_type" id="goal_conditions" name="goal_conditions[]">
                                                <option value="">Empty</option>
                                                <option value="1_{{ $code_data->id }}" {{ in_array('1_'.$code_data->id,$service_codes) ? 'selected' : '' }}>1</option>
                                                <option value="2_{{ $code_data->id }}" {{ in_array('2_'.$code_data->id,$service_codes) ? 'selected' : '' }}>2</option>
                                                <option value="3_{{ $code_data->id }}" {{ in_array('3_'.$code_data->id,$service_codes) ? 'selected' : '' }}>3</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
    </div>
</div>
<div class="tab-pane" id="activities-tab">
    <div class="organization_services">
        <div style="top:8px;" >
            <div>
                <p class="service_help_text">
                    {{ $help_text->service_activities ?? '' }}
                </p>
            </div>
        </div>
        <div class="accordion" id="accordion-activities">
            @php
                $i = 0;
            @endphp
            @foreach ($activities as $activitie_key => $activities_values)
                    <div class="card">
                        <div class="card-header" id="activities_{{ $i }}">
                            <h4 class="mb-0 card_services_title">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#activities_{{ str_replace(' ','_',$activitie_key) }}" aria-expanded="true" aria-controls="activities_{{ str_replace(' ','_',$activitie_key) }}">
                                    {{ $activitie_key }}
                                </button>
                            </h4>
                        </div>
                        <div id="activities_{{ str_replace(' ','_',$activitie_key) }}" class="collapse hide" aria-labelledby="activities_{{ str_replace(' ','_',$activitie_key) }}" data-parent="#accordion-activities">
                            <div class="card-body">
                                @foreach ($activities_values as $data_key => $code_data)
                                    <div class="row inner-accordion-section pb-15">
                                        <div class="col-md-6">
                                            <p>
                                                {{ $code_data->description }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="checkbox" name="activities_conditions[]" id="activities_{{$i}}_{{$data_key}}" class="" {{ in_array('3_'.$code_data->id,$service_codes) ? 'checked' : '' }} value="{{ $code_data->id }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
    </div>
</div>

<div class="tab-pane" id="conditions-tab">
    <div class="organization_services">
        <div  style="top:8px;" >
            <div>
                <p class="service_help_text">
                    {{ $help_text->service_conditions ?? '' }}
                </p>
            </div>
        </div>
        <div class="accordion" id="accordion-condition">
            @php
                $i = 0;
            @endphp
            @foreach ($conditions as $condition_key => $condition_values)
                    <div class="card all_form_field">
                        <div class="card-header" id="condition_{{ $i }}">
                            <h4 class="mb-0 card_services_title">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#condition_{{ str_replace(' ','_',$condition_key) }}" aria-expanded="true" aria-controls="condition_{{ str_replace(' ','_',$condition_key) }}">
                                    {{ $condition_key }}
                                </button>
                            </h4>
                        </div>
                        <div id="condition_{{ str_replace(' ','_',$condition_key) }}" class="collapse hide" aria-labelledby="condition_{{ str_replace(' ','_',$condition_key) }}" data-parent="#accordion-condition">
                            <div class="card-body">
                                {{-- <select class="form-control selectpicker" data-live-search="true"id="code_conditions" name="code_conditions[]" data-size="5">
                                    <option value="">Select</option>
                                    @foreach ($condition_values as $data_key => $code_data)
                                    <option value="{{$code_data->id}}">{{$code_data->description}}</option>
                                    @endforeach
                                </select> --}}
                                @foreach ($condition_values as $data_key => $code_data)
                                    <div class="row inner-accordion-section pb-15">
                                        <div class="col-md-6">
                                            <p>
                                                {{ $code_data->description }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control selectpicker service_category_type" id="code_conditions" name="code_conditions[]">
                                                <option value="">Empty</option>
                                                <option value="1_{{ $code_data->id }}">1</option>
                                                <option value="2_{{ $code_data->id }}">2</option>
                                                <option value="3_{{ $code_data->id }}">3</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
    </div>
</div>
<div class="tab-pane" id="goals-tab">
    <div class="organization_services">
        <div style="top:8px;" >
            <div>
                <p class="service_help_text">
                    {{ $help_text->service_goals ?? '' }}
                </p>
            </div>
        </div>
        <div class="accordion" id="accordion-goals">
            @php
                $i = 0;
            @endphp
            @foreach ($goals as $goal_key => $goal_values)
                    <div class="card all_form_field">
                        <div class="card-header" id="goal_{{ $i }}">
                            <h4 class="mb-0 card_services_title">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#goal_{{ str_replace(' ','_',$goal_key) }}" aria-expanded="true" aria-controls="goal_{{ str_replace(' ','_',$goal_key) }}">
                                    {{ $goal_key }}
                                </button>
                            </h4>
                        </div>
                        <div id="goal_{{ str_replace(' ','_',$goal_key) }}" class="collapse hide" aria-labelledby="goal_{{ str_replace(' ','_',$goal_key) }}" data-parent="#accordion-goals">
                            <div class="card-body">
                                {{-- <select class="form-control selectpicker" data-live-search="true"id="goal_conditions" name="goal_conditions[]" data-size="5">
                                    <option value="">Select</option>
                                    @foreach ($goal_values as $data_key => $code_data)
                                    <option value="{{$code_data->id}}">{{$code_data->description}}</option>
                                    @endforeach
                                </select> --}}
                                @foreach ($goal_values as $data_key => $code_data)
                                    <div class="row inner-accordion-section pb-15">
                                        <div class="col-md-6">
                                            <p>
                                                {{ $code_data->description }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control selectpicker service_category_type" id="goal_conditions" name="goal_conditions[]">
                                                <option value="">Empty</option>
                                                <option value="1_{{ $code_data->id }}">1</option>
                                                <option value="2_{{ $code_data->id }}">2</option>
                                                <option value="3_{{ $code_data->id }}">3</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
    </div>
</div>
<div class="tab-pane" id="activities-tab">
    <div class="organization_services">
        <div style="top:8px;" >
            <div>
                <p class="service_help_text">
                    {{ $help_text->service_activities ?? '' }}
                </p>
            </div>
        </div>
        <div class="accordion" id="accordion-activities">
            @php
                $i = 0;
            @endphp
            @foreach ($activities as $activitie_key => $activities_values)
                    <div class="card">
                        <div class="card-header" id="activities_{{ $i }}">
                            <h4 class="mb-0 card_services_title">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#activities_{{ str_replace(' ','_',$activitie_key) }}" aria-expanded="true" aria-controls="activities_{{ str_replace(' ','_',$activitie_key) }}">
                                    {{ $activitie_key }}
                                </button>
                            </h4>
                        </div>
                        <div id="activities_{{ str_replace(' ','_',$activitie_key) }}" class="collapse hide" aria-labelledby="activities_{{ str_replace(' ','_',$activitie_key) }}" data-parent="#accordion-activities">
                            <div class="card-body">
                                @foreach ($activities_values as $data_key => $code_data)
                                    <div class="row inner-accordion-section pb-15">
                                        <div class="col-md-6">
                                            <p>
                                                {{ $code_data->description }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="checkbox" name="activities_conditions[]" id="activities_{{$i}}_{{$data_key}}" value="{{ $code_data->id }}"  class="filled-in chk-col-blue">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
    </div>
</div>
