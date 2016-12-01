@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Accounts<a href="{{ route('accounts.create') }}" class="btn btn-primary btn-xs pull-right">Create</a></div>
                <div class="panel-body">
@if ($accounts->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
    @foreach ($accounts as $account)
                            <tr>
                                <th>{{ $account->id }}</th>
                                <td>{{ $account->name }}</td>
                                <td>
                                    <form method="POST" action="{{ route('accounts.destroy', $account->id) }}">
                                        <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-sm btn-default">Edit</a>
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
    @endforeach
                        </tbody>
                    </table>
@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
