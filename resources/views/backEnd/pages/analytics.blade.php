@extends('backLayout.app')
@section('title')
    Analytics
@stop

@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Analytics</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">


                    <div class="table table-responsive">
                        <h2>Search Analytics</h2>
                        <table class="table table-striped jambo_table bulk_action" id="tblroles">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Last Time Searched</th>
                                    <th>Search Term</th>
                                    <th>Amount of results of the last search</th>
                                    <th>Times Searched</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($analytics as $analytic)
                                    <tr>
                                        <td>{{ $analytic->id }}</td>
                                        <td>{{ $analytic->created_at }}</td>
                                        <td>{{ $analytic->search_term }}</td>
                                        <td>{{ $analytic->search_results }}</td>
                                        <td>{{ $analytic->times_searched }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- {!! Form::model($page, [
    'url' => ['analytics', 4],
    'class' => 'form-horizontal',
    'method' => 'put',
]) !!}

                    <div class="form-group {{ $errors->has('body') ? 'has-error' : '' }}">
                        {!! Form::label('body', 'Google Analytics: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('body', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('body', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                    {!! Form::close() !!} --}}
                </div>
                <div class="table table-responsive">
                    {{-- <h2>Additional Analytics</h2> --}}
                    <div class="col-md-10" style="padding: 0px; margin-bottom:10px;">
                        <form action="/analytics" method="get">
                            <div class="col-md-4">
                                <select name="search_year" id="" class="form-control">
                                    <option value="{{ date('Y', strtotime('-3 year')) }}"
                                        {{ $search_year == date('Y', strtotime('-3 year')) ? 'selected' : '' }}>
                                        {{ date('Y', strtotime('-3 year')) }}</option>
                                    <option value="{{ date('Y', strtotime('-2 year')) }}"
                                        {{ $search_year == date('Y', strtotime('-2 year')) ? 'selected' : '' }}>
                                        {{ date('Y', strtotime('-2 year')) }}</option>
                                    <option value="{{ date('Y', strtotime('-1 year')) }}"
                                        {{ $search_year == date('Y', strtotime('-1 year')) ? 'selected' : '' }}>
                                        {{ date('Y', strtotime('-1 year')) }}</option>
                                    <option value="{{ date('Y') }}" {{ $search_year == date('Y') ? 'selected' : '' }}>
                                        {{ date('Y') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-info" value="Go">
                            </div>

                    </div>
                    <div class="col-md-2 text-right" style="padding: 0px">
                        <a href="/analytics/download_search_analytic_csv/{{ $search_year }}" class="btn btn-info">Download CSV</a>
                    </div>
                    <table class="table table-striped jambo_table bulk_action" id="tblroles">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                                <th>8</th>
                                <th>9</th>
                                <th>10</th>
                                <th>11</th>
                                <th>12</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($searches_analytics as $value)
                                <tr>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['Jan'] }}</td>
                                    <td>{{ $value['Feb'] }}</td>
                                    <td>{{ $value['Mar'] }}</td>
                                    <td>{{ $value['Apr'] }}</td>
                                    <td>{{ $value['May'] }}</td>
                                    <td>{{ $value['Jun'] }}</td>
                                    <td>{{ $value['Jul'] }}</td>
                                    <td>{{ $value['Aug'] }}</td>
                                    <td>{{ $value['Sep'] }}</td>
                                    <td>{{ $value['Oct'] }}</td>
                                    <td>{{ $value['Nov'] }}</td>
                                    <td>{{ $value['Dec'] }}</td>
                                    <td> {{ intval($value['Jan']) + intval($value['Feb']) + intval($value['Mar']) + intval($value['Apr']) + intval($value['May']) + intval($value['Jun']) + intval($value['Jul']) + intval($value['Aug']) + intval($value['Sep']) + intval($value['Oct']) + intval($value['Nov']) + intval($value['Dec'])  }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table table-responsive">
                    <h2>User Activity</h2>
                    <div class="col-md-10" style="padding: 0px; margin-bottom:10px;">
                        {{-- <form action="/analytics" method="get"> --}}

                            <div class="col-md-4">
                                <select name="year" id="" class="form-control">
                                    <option value="{{ date('Y', strtotime('-3 year')) }}"
                                        {{ $year == date('Y', strtotime('-3 year')) ? 'selected' : '' }}>
                                        {{ date('Y', strtotime('-3 year')) }}</option>
                                    <option value="{{ date('Y', strtotime('-2 year')) }}"
                                        {{ $year == date('Y', strtotime('-2 year')) ? 'selected' : '' }}>
                                        {{ date('Y', strtotime('-2 year')) }}</option>
                                    <option value="{{ date('Y', strtotime('-1 year')) }}"
                                        {{ $year == date('Y', strtotime('-1 year')) ? 'selected' : '' }}>
                                        {{ date('Y', strtotime('-1 year')) }}</option>
                                    <option value="{{ date('Y') }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                        {{ date('Y') }}</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <select name="user_id" id="" class="form-control">
                                    <option value="">All</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ isset($user_id) && $user->id == $user_id ? 'selected' : '' }}>{{ $user->email  }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <input type="submit" class="btn btn-info" value="Go">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-right" style="padding: 0px">
                        <a href="/analytics/download_analytic_csv/{{ $year }}/{{ isset($user_id) ? $user_id : 'all' }}" class="btn btn-info">Download CSV</a>
                    </div>
                    <table class="table table-striped jambo_table bulk_action" id="tblroles">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                                <th>8</th>
                                <th>9</th>
                                <th>10</th>
                                <th>11</th>
                                <th>12</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($additional_analytics as $value)
                                <tr>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['Jan'] }}</td>
                                    <td>{{ $value['Feb'] }}</td>
                                    <td>{{ $value['Mar'] }}</td>
                                    <td>{{ $value['Apr'] }}</td>
                                    <td>{{ $value['May'] }}</td>
                                    <td>{{ $value['Jun'] }}</td>
                                    <td>{{ $value['Jul'] }}</td>
                                    <td>{{ $value['Aug'] }}</td>
                                    <td>{{ $value['Sep'] }}</td>
                                    <td>{{ $value['Oct'] }}</td>
                                    <td>{{ $value['Nov'] }}</td>
                                    <td>{{ $value['Dec'] }}</td>
                                    <td> {{ intval($value['Jan']) + intval($value['Feb']) + intval($value['Mar']) + intval($value['Apr']) + intval($value['May']) + intval($value['Jun']) + intval($value['Jul']) + intval($value['Aug']) + intval($value['Sep']) + intval($value['Oct']) + intval($value['Nov']) + intval($value['Dec'])  }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300
            });
            $('#tblroles').DataTable({
                columnDefs: [{
                    targets: [0],
                    visible: false,
                    searchable: false
                }, ],
                order: [
                    [0, "asc"]
                ],
            });
        });
    </script>
@endsection
