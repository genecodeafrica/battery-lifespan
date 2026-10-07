@extends('layouts.app')

@section('title', 'Import Oxford Dataset')

@section('content')

<div class="container-fluid">

    <div class="page-header mb-4">

        <div>
            <h1 class="page-title">
                <i class="bi bi-database-add"></i>
                Import Battery Dataset
            </h1>

            <p class="text-muted">
                Import Oxford Battery Degradation Dataset 1
                into BatteryLife AI.
            </p>
        </div>

        <div>
            <a
                href="{{ route('batteries.index') }}"
                class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i>
                Back to Batteries
            </a>
        </div>

    </div>


    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>

            {{ session('success') }}
        </div>

    @endif


    {{-- Error Message --}}

    @if(session('error'))

        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>

            {{ session('error') }}
        </div>

    @endif


    {{-- Validation Errors --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            <i class="bi bi-filetype-csv"></i>
                            CSV Dataset Import
                        </h5>

                        <p class="text-muted mb-0">
                            Upload the Oxford battery summary CSV.
                        </p>

                    </div>

                </div>


                <div class="card-body-custom">

                    <form
                        action="{{ route('batteries.import.store') }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf


                        <div class="upload-area mb-4">

                            <i class="bi bi-cloud-arrow-up"></i>

                            <h5>
                                Select CSV Dataset
                            </h5>

                            <p class="text-muted">
                                Supported format: CSV
                            </p>

                            <input
                                type="file"
                                name="csv_file"
                                class="form-control"
                                accept=".csv,.txt"
                                required>

                        </div>


                        <div class="dataset-info mb-4">

                            <h6>
                                <i class="bi bi-info-circle"></i>
                                Import Information
                            </h6>

                            <ul>

                                <li>
                                    Only <strong>C1dc</strong>
                                    discharge measurements will be imported.
                                </li>

                                <li>
                                    The dataset contains
                                    <strong>8 battery cells</strong>.
                                </li>

                                <li>
                                    Capacity retention will be calculated automatically.
                                </li>

                                <li>
                                    Capacity loss will be calculated automatically.
                                </li>

                                <li>
                                    Degradation rate will be calculated automatically.
                                </li>

                                <li>
                                    Existing measurements will be updated rather
                                    than duplicated.
                                </li>

                            </ul>

                        </div>


                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="bi bi-upload"></i>
                                Import Dataset

                            </button>


                            <a
                                href="{{ route('batteries.index') }}"
                                class="btn btn-outline-light">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection