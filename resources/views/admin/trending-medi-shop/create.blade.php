@extends('admin.layout.app')

@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Add New Trending Medical Shop</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Trending Medical Shop</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('trending-shop.store') }}" method="POST">
            @csrf
            <label for="doctor">Select Medical Shop:</label>
            <select name="name" id="doctor">
                <option value="">-- Select a Medical Shop --</option>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->name }}" data-hospital-id="{{ $shop->id }}">
                        {{ $shop->name }}
                    </option>
                @endforeach
            </select>

            <!-- Hidden input for clinic_id -->
            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shops->first()->id ?? '' }}">

            <button type="submit">Save</button>
        </form>
    </main>

@endsection
