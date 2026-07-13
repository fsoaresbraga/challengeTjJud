<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatório de livros por autor</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }
        h1 {
            font-size: 18px;
            margin: 0 0 4px;
            color: #0b3d5c;
        }
        .meta {
            margin-bottom: 18px;
            color: #555;
            font-size: 10px;
        }
        h2 {
            font-size: 13px;
            margin: 16px 0 6px;
            padding-bottom: 3px;
            border-bottom: 1px solid #0b3d5c;
            color: #0b3d5c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #e8eef3;
            font-weight: bold;
        }
        .empty {
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>Relatório de livros por autor</h1>
    <div class="meta">
        Gerado em {{ $generatedAt->format('d/m/Y H:i') }}
    </div>

    @forelse ($grouped as $authorName => $books)
        <h2>{{ $authorName }}</h2>
        <table>
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
    @empty
        <p class="empty">Nenhum dado encontrado no relatório.</p>
    @endforelse
</body>
</html>
