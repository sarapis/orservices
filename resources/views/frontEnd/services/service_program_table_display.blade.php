@if ($service->program && count($service->program) > 0)
    <div class="tagp_class">
        <span class="pl-0 category_badge subtitle"><b>Related Programs:</b></span>
        <table class="display dataTable table-striped jambo_table table-bordered table-responsive no-footer" id="PhoneTable">
            <thead>
                <th>Name</th>
                <th>Alternative Name</th>
                <th>Description</th>
            </thead>
            <tbody>
            @foreach ($service->program as $key => $program)
                <tr>
                    <td>
                        {{ $program->name }}
                    </td>
                    <td>
                        {{ $program->alternate_name }}
                    </td>
                    <td>
                        {{ $program->description }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
