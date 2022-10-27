@extends('layouts.app')
@section('title')
USers
@stop

<style>
    tr.modified {
        background-color: red !important;
    }

    tr.modified>td {
        background-color: red !important;
        color: white;
    }

</style>
@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-12">
                <h4 class="card-title title_edit mb-30">
                    Users
                </h4>
                <div class="card all_form_field">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-3 control-label">Organization</label>
                                <div class="col-sm-7" style="margin-bottom: 20px">
                                    {!! Form::select('organization', $organizations, null, ['class' => 'form-control selectpicker', 'id' => 'organization', 'multiple' => true, 'data-live-search' => 'true', 'data-size' => 5]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <a href="{{ route('users_lists.create') }}" class="btn btn-success" >Create User</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="text-center">First name</th>
                                        <th class="text-center">Last name</th>
                                        <th class="text-center">E-mail</th>
                                        <th class="text-center">Cell Phone</th>
                                        <th class="text-center">User Organizations</th>
                                        <th class="text-center">User Role</th>
                                        <th class="text-center">Edits </th>
                                        <th class="text-center">Notes </th>
                                        <th class="text-center">Status </th>
                                        <th class="text-center">Last Logged In </th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td class="text-center">{{ $user->first_name }}</td>
                                            <td class="text-center">{{ $user->last_name }}</td>
                                            <td class="text-center">{{ $user->email }}</td>
                                            <td class="text-center">{{ $user->phone_number }}</td>
                                            @if ($user->organizations)
                                                <td class="text-center">
                                                    @foreach ($user->organizations as $value)
                                                        <a href="/organizations/{{ $value->organization_recordid }}" class="panel-link" style="color: blue;">{{ $value->organization_name }} </a><br>
                                                    @endforeach
                                                </td>
                                            @else
                                                <td class="text-center"></td>
                                            @endif
                                            <td class="text-center">{{ empty($user->roles) ? ' ' : $user->roles->name }}</td>
                                            <td>{{ count($user->edits) }}</td>
                                            <td class="text-center">{{ $user->interations ? count($user->interations) : 0 }}</td>
                                            <td class="text-center">{!! $user->status == '1' ? '<span class="badge badge-success"> Active </span>' : '<span class="badge badge-danger"> Deactivate</span>' !!}</td>
                                            <td class="text-center">{{ $user->last_login }}</td>
                                            <td class="text-center">{{ $user->created_at }}</td>
                                            <td class="text-center"><a href="{{ route('users_lists.edit', $user->id) }}"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('customScript')
<script>
    $(document).ready(function() {
        table = $('#table').DataTable();
        $("#organization").selectpicker();
        $('#organization').on( 'change', function () {
            table.search( this.value ).draw();
        });
    });
</script>
@endsection
