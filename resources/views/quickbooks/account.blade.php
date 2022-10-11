{{--  quickbook display all accounts  --}}
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            {{--  breadcrumbs  --}}
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li class=" breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class=" breadcrumb-item active">Quickbooks Accounts</li>
                </ol>
            </div>
        </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div> Quickbooks Accounts </div>
                <a href="{{ route('quickbooks.account') }}" class="btn btn-primary pull-right">Refresh</a>
                {{--  link to create account  --}}
                <a href="{{ route('quickbooks.account.create') }}" class="btn btn-primary pull-right">Create Account</a>
                
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Account Type</th>
                            <th>Account Sub Type</th>
                            <th>Account Classification</th>
                            <th>Account Description</th>
                            <th>Account Active</th>
                            <th>Account Balance</th>
                            <th>Account Currency</th>
                            <th>Account Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr>
                                <td>{{ $account->Name }}</td>
                                <td>{{ $account->AccountType }}</td>
                                <td>{{ $account->AccountSubType }}</td>
                                <td>{{ $account->Classification }}</td>
                                <td>{{ $account->Description }}</td>
                                <td>{{ $account->Active }}</td>
                                <td>{{ $account->CurrentBalance }}</td>
                                <td>{{ $account->CurrencyRef }}</td>
                                <td>{{ $account->MetaData->LastUpdatedTime }}</td>
                                <td>
                                    <a href="{{ route('quickbooks.account.single', $account->Id) }}"
                                        class="btn btn-primary">View</a>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
