@extends('layouts.app')

@section('title', 'Início')

@section('content')
    <div class="hero-home mb-4">
        <h1 class="display-6 fw-semibold mb-2">Cadastro de Livros</h1>
        <p class="mb-0 opacity-75">CRUD de livros, autores e assuntos com relatório por autor — teste técnico TJ JUD.</p>
    </div>

    <div class="row g-3">
        <div class="col-md-6 col-lg-3">
            <a class="menu-tile" href="{{ route('books.index') }}">
                <h2 class="h5">Livros</h2>
                <p class="mb-0 text-secondary small">Cadastrar, editar e associar autores/assuntos.</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a class="menu-tile" href="{{ route('authors.index') }}">
                <h2 class="h5">Autores</h2>
                <p class="mb-0 text-secondary small">Manutenção do cadastro de autores.</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a class="menu-tile" href="{{ route('subjects.index') }}">
                <h2 class="h5">Assuntos</h2>
                <p class="mb-0 text-secondary small">Manutenção do cadastro de assuntos.</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a class="menu-tile" href="{{ route('reports.books-by-author') }}">
                <h2 class="h5">Relatório</h2>
                <p class="mb-0 text-secondary small">Livros agrupados por autor (VIEW).</p>
            </a>
        </div>
    </div>
@endsection
