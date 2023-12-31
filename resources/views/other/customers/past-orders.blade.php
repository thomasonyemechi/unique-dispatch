@extends('layouts.other')

@section('page_title')
    Past Orders
@endsection
@section('page_content')

    <div class="home-navbar bg-primary d-flex align-items-center gap-3 mb-auto p-3 osahan-header">
        <div>
            <h5 class="fw-bold text-white mb-0"> Past Orders </h5>
        </div>

        <div class="d-flex align-items-center gap-3 ms-auto">
            <a href="notification.html" class="bg-white shadow rounded-pill notification-icon position-relative">
                <i class="bx bxs-bell h5 m-0 text-primary"></i>
                <span
                    class="position-absolute top-0 ms-5 mt-1 translate-middle badge rounded-circle bg-danger py-1 fw-normal">
                    3
                    <span class="visually-hidden">unread messages</span>
                </span>
            </a>
            <a class="toggle notification-icon d-flex align-items-center bg-white rounded-pill" href="#"><i
                    class="bx bx-menu bx-sm text-primary"></i></a>
        </div>
    </div>








    <div class="vh-100 my-auto overflow-auto p-3">



        @if($past_orders->count() > 0)
            <div class="mb-5">
                <h5 class="mb-3">Current</h5>
                <div>
                    @foreach ($past_orders as $order)
                        <a href="/staff/customer/{{ $order->id }}" class="link-dark">
                            <div
                                class="bg-white rounded-3 shadow d-flex align-items-center justify-content-between p-3 border border mb-2">
                                <div>
                                    <h6 class="mb-1"> {{ $order->service_name }} </h6>
                                    <p class="mb-1 text-muted small">
                                        Added {{ date('j M, Y H:i a', strtotime($order->created_at)) }}
                                    </p>
                                    @php
                                        $statusInt = $order->status;
                                        $enumStatus = \App\Enums\OrderStatus::fromInt($statusInt);
                                    @endphp
                                    <p class="{!! $enumStatus->statusClass() !!} mb-0">{{$enumStatus}}<span
                                            class="fw-normal text-muted ms-1 small">{{$order->time_left}} left</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="pt-3 d-flex justify-content-end ">
                {{ $customers->links('pagination::bootstrap-4') }}
            </div>
        @else
            {{--img at the center of page for empty orders small size--}}
            <div class="mt-5 d-flex justify-content-center align-items-center">
                <div class="text-center" style="height: 16rem; width: 17rem;">
                    <img src="{{asset('assets/img/errors/empty_cart.svg')}}"
                         alt="empty"
                         class="img-fluid">
                    <p class="text-center text-muted mt-3 font-900">Oops..., No past orders available</p>
                    <a href="{{route('customer.view-orders')}}" class="bg-primary btn btn-block mb-3 mx-auto">Go
                        to
                        Orders</a>
                </div>
            </div>
    @endif
@endsection


@push('scripts')
@endpush
