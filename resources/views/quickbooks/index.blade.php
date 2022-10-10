{{--  quickbooks index  --}}
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Quickbooks</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{--  initiate connection link  --}}
                                <a href="{{ route('quickbooks.initiate') }}" class="btn btn-primary">Initiate Connection</a>
                                <a href="{{ route('quickbooks.account') }}" class="btn btn-primary">Accounts</a>
                                {{--  <a href="{{ route('quickbooks.customer') }}" class="btn btn-primary">Customers</a>
                                <a href="{{ route('quickbooks.invoice') }}" class="btn btn-primary">Invoices</a>
                                <a href="{{ route('quickbooks.payment') }}" class="btn btn-primary">Payments</a>
                                <a href="{{ route('quickbooks.product') }}" class="btn btn-primary">Products</a>
                                <a href="{{ route('quickbooks.salesreceipt') }}" class="btn btn-primary">Sales Receipts</a>
                                <a href="{{ route('quickbooks.vendor') }}" class="btn btn-primary">Vendors</a>  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection