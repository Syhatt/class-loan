@extends('layouts.master')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
    </div>

    <div class="row">
        @foreach ($item as $item)
            <div class="col">
                <div class="card" style="width: 18rem;">
                    {{-- <img src="..." class="card-img-top" alt="..."> --}}
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ $item->desc }}</p>
                        <p class="card-text">Stock : {{ $item->stock }}</p>
                        <a href="{{ route('bookingitem.tambah', $item->id) }}" class="btn btn-info btn-sm"><i class="fas fa-bookmark"></i>
                            Booking</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
