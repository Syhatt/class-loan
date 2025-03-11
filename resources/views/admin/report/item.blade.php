@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <form action="{{ route('report.item.generate') }}" method="GET">
        <label for="month">Pilih Bulan:</label>
        <select name="month" id="month" class="form-control">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary mt-2">Generate Laporan</button>
    </form>
    
@endsection
