@extends('layouts.app')

@section('title','Daftar Kegiatan')

@section('content')
<article>
    <header>
        <nav>
            <ul>
                <li><h1>Daftar Kegiatan</h1></li>
            </ul>

            <ul>
                <li>
                    <a href="{{ route('kegiatan.create') }}" class="contrast">
                        + Tambah Kegiatan
                    </a>
                </li>
                <li>
                    <a href="{{ route('kegiatan.index') }}"
                    role="button"
                    class="{{ $view === 'card' ? 'contrast' : 'secondary' }}">
                        Card
                    </a>
                </li>
                <li>
                    <a href="{{ route('kegiatan.index', ['view'=>'table']) }}"
                    role="button"
                    class="{{ $view === 'table' ? 'contrast' : 'secondary' }}">
                        Table
                    </a>
                </li>
            </ul>
        </nav>
    </header>


    {{-- VIEW --}}
    @if($view === 'card')
    @include('kegiatan._card', ['data' => $data])
    @else
        @include('kegiatan._table', ['data' => $data])
    @endif

    {{-- MODAL GLOBAL (SEKALI SAJA) --}}
    @include('kegiatan._modal')

</article>
@endsection