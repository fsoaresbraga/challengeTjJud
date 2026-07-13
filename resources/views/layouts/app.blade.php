<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cadastro de Livros') — TJ JUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --tj-navy: #0b3d5c;
            --tj-navy-dark: #072a40;
            --tj-accent: #c9a227;
        }
        body {
            background: linear-gradient(180deg, #f3f6f9 0%, #e8eef3 100%);
            min-height: 100vh;
        }
        .navbar-tj {
            background: linear-gradient(90deg, var(--tj-navy) 0%, var(--tj-navy-dark) 100%);
        }
        .navbar-tj .nav-link.active,
        .navbar-tj .navbar-brand {
            color: #fff !important;
        }
        .navbar-tj .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        .navbar-tj .nav-link:hover {
            color: var(--tj-accent);
        }
        .page-card {
            border: 0;
            box-shadow: 0 8px 24px rgba(11, 61, 92, 0.08);
            border-radius: 0.75rem;
        }
        .hero-home {
            background:
                radial-gradient(circle at top right, rgba(201, 162, 39, 0.25), transparent 40%),
                linear-gradient(135deg, var(--tj-navy) 0%, #145a82 100%);
            color: #fff;
            border-radius: 1rem;
            padding: 2.5rem;
        }
        .menu-tile {
            display: block;
            text-decoration: none;
            color: inherit;
            background: #fff;
            border-radius: 0.75rem;
            padding: 1.25rem;
            box-shadow: 0 8px 24px rgba(11, 61, 92, 0.08);
            transition: transform .15s ease, box-shadow .15s ease;
            height: 100%;
        }
        .menu-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(11, 61, 92, 0.14);
            color: inherit;
        }
        .alert-fixed {
            position: sticky;
            top: 1rem;
            z-index: 1020;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-tj mb-4">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('home') }}">TJ JUD · Livros</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Livros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('authors.*') ? 'active' : '' }}" href="{{ route('authors.index') }}">Autores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}">Assuntos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.books-by-author') }}">Relatório</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('documentation.*') ? 'active' : '' }}" href="{{ route('documentation.index') }}">API Docs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/imask@7.6.1/dist/imask.min.js"></script>
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/ui.js') }}"></script>
    @stack('scripts')
</body>
</html>
