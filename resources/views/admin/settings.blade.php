@extends('admin.layouts.admin')

@section('title', trans('shopeasyreg::admin.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('shopeasyreg.admin.save') }}">
                @csrf
                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="cartAuth" name="cart_auth" @checked(setting('shop.cart_auth'))>
                    <label class="form-check-label" for="cartAuth">{{ trans('shopeasyreg::admin.cart_auth') }}</label>
                </div>
                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="emailVerification" name="email_verification" @checked(setting('shop.email_verification', true))>
                    <label class="form-check-label" for="emailVerification">{{ trans('shopeasyreg::admin.email_verification') }}</label>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
