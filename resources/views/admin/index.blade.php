@extends('layouts.app')

@section('content')
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card text-white bg-primary">
                <div class="card-header h4 text-center">Total Rooms</div>
                <div class="card-body py-5">
                    <h5 class="text-center">{{ $totalRooms }}</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-success">
                <div class="card-header h4 text-center">Total Reservations</div>
                <div class="card-body py-5">
                    <h5 class="text-center">{{ $reservedRoom }}</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
