{{--  get single account and its relationships  --}}
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            {{--  breadcrumbs  --}}
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('quickbooks.account') }}">Quickbooks Accounts</a></li>
                    <li class="active">{{ $account->Name }}</li>
                </ol>
        </div>
        {{--  account details  --}}
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading d-flex justify-content-between ">
                        <div>
                            Quickbooks Account Details
                        </div>
                        {{--  return back  --}}
                        <div>
                            <a href="{{ route('quickbooks.account') }}" class="btn btn-primary pull-right">Back</a>
                        </div>
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
                                </tr>
                            </thead>
                            <tbody>
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
                                </tr>
                            </tbody>
                        </table>
                        {{--  <table class="table table-bordered">
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($account->AccountSubType as $account)
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
                                @endforeach
                                </tr>
                            </tbody>
                        </table>  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
