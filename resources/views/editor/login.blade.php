@extends('layouts.app')

@section('title', 'Editor Login')

@section('content')
<article style="max-width:420px;margin:auto">
    <header>
        <h4>Editor Access</h4>
        <p>Masukkan password editor untuk mengelola kegiatan.</p>
    </header>

    @if(session('error'))
        <mark>{{ session('error') }}</mark>
    @endif

    <form method="POST">
        @csrf

        <input
            type="password"
            name="password"
            placeholder="Editor password"
            required
        >

        <button type="submit" class="contrast">
            Masuk Editor
        </button>
    </form>
</article>
@endsection
