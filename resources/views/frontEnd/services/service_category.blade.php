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
                                @if (count($service_category_term_data) > 0 && count($service_category_term_data) == count($service_category_type_data))
                                    @foreach ($service_category_type_data as $key => $value)
                                        <tr>
                                            <td>
                                                {!! Form::select('service_category_type[]', $service_category_types, $value->taxonomy_recordid, ['class' => 'form-control selectpicker service_category_type', 'placeholder' => 'Select Type', 'id' => 'service_category_type_' . $key]) !!}

                                            </td>
                                            <td class="create_btn">
                                                @php
                                                    $taxonomy_parent_name = \App\Model\Taxonomy::where('taxonomy_recordid', $value->taxonomy_recordid)->first();
                                                    $taxonomy_info_list = \App\Model\Taxonomy::where('taxonomy_parent_name', $value->taxonomy_recordid)->get();
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
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $taxonomy_array['create_new'] = '+ Create New';
                                                @endphp
                                                {!! Form::select('service_category_term[]', $taxonomy_array, $value->selectedTermId, ['class' => 'form-control selectpicker service_category_term', 'placeholder' => 'Select Term', 'id' => 'service_category_term_' . $key]) !!}
                                                <input type="hidden" name="service_category_term_type[]"
                                                    id="service_category_term_type_{{ $key }}" value="old">
                                            </td>
                                            {{-- <td class="text-center">
                                        <a href="javascript:void(0)" class="removePhoneData" style="color:red;"> <i class="fa fa-minus-circle" aria-hidden="true"></i> </a>
                                    </td> --}}
                                            <td style="vertical-align: middle">
                                                <a href="#" class="plus_delteicon btn-button removePhoneData">
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
                                            {!! Form::select('service_category_term[]', [], null, ['class' => 'form-control selectpicker service_category_term', 'placeholder' => 'Select Term', 'id' => 'service_category_term_0']) !!}
                                            <input type="hidden" name="service_category_term_type[]"
                                                id="service_category_term_type_0" value="old">
                                        </td>
                                        <td style="vertical-align: middle">
                                            <a href="#" class="plus_delteicon btn-button">
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
