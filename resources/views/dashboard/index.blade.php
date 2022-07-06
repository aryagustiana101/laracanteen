@extends('layouts.app')

@section('content')

<div class="row g-5 g-xl-8 mb-10">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row g-5 g-xl-8 mb-10">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Data Keseluruhan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center bg-light-dark rounded p-5 mb-7">
                            <span class="svg-icon svg-icon-dark me-5">
                                <span class="svg-icon svg-icon-1">
                                    <i class="fa-solid fa-address-card text-dark fs-5x"></i>
                                </span>
                            </span>
                            <div class="flex-grow-1 me-2">
                                <p href="/antrian" class="fw-bolder text-gray-800 text-hover-primary fs-6">
                                    Waktu Saat Ini</p>
                                <span class="fw-bold fs-3 d-block" id="clock"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

@endsection