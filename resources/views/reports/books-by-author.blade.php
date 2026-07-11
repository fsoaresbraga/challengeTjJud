@extends('layouts.app')

@section('title', 'Relatório')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Relatório de livros por autor</h1>
            <p class="text-secondary mb-0">
                Dados da VIEW <code>vw_relatorio_livros_por_autor</code>, agrupados por autor no Blade.
            </p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Voltar</a>
    </div>

    @forelse ($grouped as $authorName => $books)
        <div class="card page-card mb-3">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ $authorName }}</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Editora</th>
                                <th>Edição</th>
                                <th>Ano</th>
                                <th>Valor</th>
                                <th>Assuntos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($books as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->publisher }}</td>
                                    <td>{{ $book->edition }}</td>
                                    <td>{{ $book->publicationYear }}</td>
                                    <td>R$ {{ number_format((float) $book->price, 2, ',', '.') }}</td>
                                    <td>{{ $book->subjects ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info mb-0">Nenhum dado encontrado no relatório.</div>
    @endforelse
@endsection
