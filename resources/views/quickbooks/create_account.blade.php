{{--  create_account blade  --}}
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            {{--  breadcrumbs  --}}
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li class=" breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class=" breadcrumb-item"><a href="{{ route('quickbooks.account') }}">Quickbooks Accounts</a></li>
                    <li class=" breadcrumb-item active">Create Account</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div> Create Account </div>
                    </div>
                    <div class="panel-body">
                        {{--  form class  --}}

                        <form class=" row "  action="{{ route('quickbooks.account.store') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="name">Account Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Account Name">
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="account_type">Account Type</label>
                                <select name="account_type" class="form-control" id="account_type">
                                    <option value="Bank">Bank</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Other Current Asset">Other Current Asset</option>
                                    <option value="Fixed Asset">Fixed Asset</option>
                                    <option value="Other Asset">Other Asset</option>
                                    <option value="Accounts Payable">Accounts Payable</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Other Current Liability">Other Current Liability</option>
                                    <option value="Long Term Liability">Long Term Liability</option>
                                    <option value="Equity">Equity</option>
                                    <option value="Income">Income</option>
                                    <option value="Cost of Goods Sold">Cost of Goods Sold</option>
                                    <option value="Expense">Expense</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="account_sub_type">Account Sub Type</label>
                                <select name="account_sub_type" class="form-control" id="account_sub_type">
                                    <option value="Checking">Checking</option>
                                    <option value="Savings">Savings</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Other Current Asset">Other Current Asset</option>
                                    <option value="Fixed Asset">Fixed Asset</option>
                                    <option value="Other Asset">Other Asset</option>
                                    <option value="Accounts Payable">Accounts Payable</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Other Current Liability">Other Current Liability</option>
                                    <option value="Long Term Liability">Long Term Liability</option>
                                    <option value="Equity">Equity</option>
                                    <option value="Income">Income</option>
                                    <option value="Cost of Goods Sold">Cost of Goods Sold</option>
                                    <option value="Expense">Expense</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="account_number">Account Number</label>
                                <input type="text" name="account_number" class="form-control" id="account_number"
                                    placeholder="Account Number">
                            </div>
                            {{--  <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="currency">Currency</label>
                                <select name="currency" class="form-control" id="currency">
                                    <option value="USD">USD</option>
                                    <option value="CAD">CAD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="AUD">AUD</option>
                                    <option value="NZD">NZD</option>
                                    <option value="INR">INR</option>
                                    <option value="SGD">SGD</option>
                                    <option value="CHF">CHF</option>
                                    <option value="MYR">MYR</option>
                                    <option value="JPY">JPY</option>
                                    <option value="BRL">BRL</option>
                                    <option value="ZAR">ZAR</option>
                                    <option value="HKD">HKD</option>
                                    <option value="MXN">MXN</option>
                                    <option value="TWD">TWD</option>
                                    <option value="THB">THB</option>
                                    <option value="TRY">TRY</option>
                                    <option value="NOK">NOK</option>
                                    <option value="DKK">DKK</option>
                                    <option value="SEK">SEK</option>
                                    <option value="PLN">PLN</option>
                                    <option value="RUB">RUB</option>
                                    <option value="HUF">HUF</option>
                                    <option value="CZK">CZK</option>
                                    <option value="ILS">ILS</option>
                                    <option value="PHP">PHP</option>
                                    <option value="AED">AED</option>
                                    <option value="SAR">SAR</option>
                                    <option value="KRW">KRW</option>
                                    <option value="TWD">TWD</option>
                                    <option value="TWD">TWD</option>
                                </select>
                            </div>  --}}
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="active">Active</label>
                                <select name="active" class="form-control" id="active">
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="classification">Classification</label>
                                <select name="classification" class="form-control" id="classification">
                                    <option value="Asset">Asset</option>
                                    <option value="Liability">Liability</option>
                                    <option value="Equity">Equity</option>
                                    <option value="Income">Income</option>
                                    <option value="Expense">Expense</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="current_balance">Current Balance</label>
                                <input type="text" name="current_balance" class="form-control" id="current_balance"
                                    placeholder="Current Balance">
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="current_balance_with_sub_accounts">Current Balance With Sub Accounts</label>
                                <input type="text" name="current_balance_with_sub_accounts" class="form-control"
                                    id="current_balance_with_sub_accounts" placeholder="Current Balance With Sub Accounts">
                            </div>
                            {{--  domain  --}}
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="domain">Domain</label>
                                <select name="domain" class="form-control" id="domain">
                                    <option value="QBO">QBO</option>
                                    <option value="QBD">QBD</option>
                                    <option value="QBOA">QBOA</option>
                                </select>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <button type="submit" class="btn btn-primary">Submit</button>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
