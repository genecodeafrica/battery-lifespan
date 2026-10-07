@extends('layouts.app')

@section('title', 'Add Battery')

@section('content')

<div class="page-header">

    <h1 class="page-title">
        Register Battery
    </h1>

    <p class="page-description">
        Add a new lithium-ion battery to the monitoring system.
    </p>

</div>


<div class="row justify-content-center">

    <div class="col-12 col-xl-9">

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <div>

                    <h2 class="dashboard-card-title">
                        Battery Information
                    </h2>

                    <div class="dashboard-card-subtitle">
                        Enter the basic battery information
                    </div>

                </div>

            </div>


            <div class="dashboard-card-body">

                <form
                    method="POST"
                    action="{{ route('batteries.store') }}">

                    @csrf


                    <div class="row g-3">

                        {{-- BATTERY CODE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Battery Code
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="battery_code"
                                value="{{ old('battery_code') }}"
                                class="form-control"
                                placeholder="e.g. BAT-001"
                                required>

                            @error('battery_code')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- CELL NAME --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Cell Name
                            </label>

                            <input
                                type="text"
                                name="cell_name"
                                value="{{ old('cell_name') }}"
                                class="form-control"
                                placeholder="e.g. Cell1">

                        </div>


                        {{-- INITIAL CAPACITY --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Initial Capacity (mAh)
                            </label>

                            <input
                                type="number"
                                step="0.0001"
                                name="initial_capacity_mAh"
                                value="{{ old('initial_capacity_mAh') }}"
                                class="form-control"
                                placeholder="e.g. 739.1109">

                        </div>


                        {{-- MANUFACTURER --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Manufacturer
                            </label>

                            <input
                                type="text"
                                name="manufacturer"
                                value="{{ old('manufacturer') }}"
                                class="form-control"
                                placeholder="Manufacturer name">

                        </div>


                        {{-- INSTALLATION DATE --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Installation Date
                            </label>

                            <input
                                type="date"
                                name="installation_date"
                                value="{{ old('installation_date') }}"
                                class="form-control">

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="4"
                                class="form-control"
                                placeholder="Optional battery description...">{{ old('description') }}</textarea>

                        </div>

                    </div>


                    <div class="d-flex gap-2 mt-4">

                        <a
                            href="{{ route('batteries.index') }}"
                            class="btn btn-outline-light">

                            Cancel

                        </a>


                        <button
                            type="submit"
                            class="btn btn-success">

                            <i class="bi bi-check-lg me-1"></i>

                            Register Battery

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection