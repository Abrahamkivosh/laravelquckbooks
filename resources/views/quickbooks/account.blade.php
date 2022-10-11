{{--  quickbook display all accounts  --}}
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Quickbooks Accounts</div>
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
                            @foreach($accounts as $account)
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
                                        <a href="{{ route('quickbooks.account.single', $account->Id) }}" class="btn btn-primary">View</a>
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