@extends('backLayout.app')
@section('title')
Page
@stop

@section('content')

    <h3>Page</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Name</th><th>Title</th><th>Body</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $page->id }}</td> <td> {{ $page->name }} </td><td> {{ $page->title }} </td><td> {{ $page->body }} </td>
                </tr>
            </tbody>    
        </table>
    </div>

@endsection