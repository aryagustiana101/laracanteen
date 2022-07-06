@extends('layouts.auth')

@section('content')
@if (session()->has('error'))
<div class="alert alert-danger alert-dismissible d-flex flex-column flex-sm-row p-5 mb-10">
    <span class="me-5 mb-5 mb-sm-0 mt-3">
        <i class="fa-solid fa-circle-exclamation text-danger fs-2hx"></i>
    </span>
    <div class="d-flex flex-column pe-0 pe-sm-10">
        <h4 class="mb-2 text-danger">Login Gagal!</h4>
        <span>{{ session('error') }}</span>
    </div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
        data-bs-dismiss="alert">
        <i class="fa-solid fa-xmark text-danger fs-1"></i>
    </button>
</div>
@endif

<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
    <form class="form w-100" action="/login" method="post">
        @csrf
        <div class="text-center mb-10">
            <h1 class="text-dark mb-3">Login Laracanteen</h1>
        </div>
        <div class="fv-row mb-10">
            <label class="form-label fs-6 fw-bolder text-dark">Emai atau No. Telepon</label>
            <input
                class="form-control form-control-lg form-control-solid @error('user_credential') is-invalid @enderror"
                type="text" name="user_credential" autocomplete="off" value="{{ old('user_credential') }}" />
            @error('user_credential')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="fv-row mb-10">
            <div class="d-flex flex-stack mb-2">
                <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
            </div>
            <input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                type="password" name="password" value="{{ old('password') }}" />
            @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">Login</button>
        </div>
    </form>
</div>
@endsection