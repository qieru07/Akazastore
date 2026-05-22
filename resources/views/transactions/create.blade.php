@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-5">
    Tambah Transaksi
</h1>

<form action="/transactions"
      method="POST"
      class="bg-white p-5 rounded-xl shadow">

    @csrf

    <select name="product_id"
            class="border w-full p-3 rounded-lg mb-5">

        @foreach($products as $product)

            <option value="{{ $product->id }}">

                {{ $product->name }}
                - Rp {{ number_format($product->price) }}

            </option>

        @endforeach

    </select>

    <input type="text"
           name="nickname"
           placeholder="Nickname"
           class="border w-full p-3 rounded-lg mb-5">

    <input type="text"
           name="user_id_game"
           placeholder="User ID Game"
           class="border w-full p-3 rounded-lg mb-5">

    <button class="bg-black text-white px-5 py-2 rounded-lg">

        Simpan

    </button>

</form>

@endsection