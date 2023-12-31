@extends('layouts.other')

@section('page_title')
    Dashboard
@endsection
@section('page_content')
    @include('other.designers.top')

    <div class="vh-100 my-auto overflow-auto">
        <div>
            @foreach ($orders as $created_by => $order_list)
                @php
                    $marketer = user($created_by);
                @endphp
                <div class="order-group m-3 mb-4 p-3">

                    <div class="border-bottom bg-white mb-3 p-3 rounded d-flex align-items-center ">
                        <img src="{{ asset($marketer->photo) }}" alt="" class="img-fluid me-2 rounded-lg profile">
                        <div>
                            <h6 class="mb-1"> {{ $marketer->name }} </h6>
                            <a href="/designer/m/{{ $created_by }}" class="text-warning m-0 small">Start Design
                            </a>
                        </div>
                    </div>



                    @foreach ($order_list as $order)
                        <div
                            class="bg-white rounded-3 shadow d-flex align-items-center justify-content-between p-3 border border mb-2">
                            <div>
                                <h6 class="small">{{ $order->service_name }}</h6>
                                <div class="bg-light p-1 rounded">
                                    <p class="mb-0 text-muted small">
                                        <span class="fw-bold">{{ date('j M, Y', strtotime($order->created_at)) }}</span>
                                    </p>
                                </div>

                                @if (!$order->designer)
                                    <p class="text-danger mt-0 mb-0 small">No designer has started work on this order
                                    </p>
                                @endif


                                <div class="d-flex justify-content-start ">
                                    @if ($order->designer)
                                        <div class="badge bg-warning  ">Pending</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>


    </div>
@endsection


@push('scripts')
@endpush
