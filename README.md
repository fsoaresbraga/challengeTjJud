# Cadastro de Livros — Teste Técnico TJ JUD

CRUD de **Livros**, **Autores** e **Assuntos** com relatório por autor, API REST interna e frontend Blade + Alpine.js.

| Item | Stack |
|------|--------|
| Backend | Laravel 12 / PHP 8.2+ |
| Banco | MySQL 8 (schema em português) |
| Frontend | Blade + Bootstrap 5 + Alpine.js + SweetAlert2 + IMask |
| Relatório | PDF via barryvdh/laravel-dompdf (download assíncrono) |
| API Docs | OpenAPI 3 em `documentation/` + Swagger UI |
| Testes | PHPUnit (Feature + Unit) |
| Infra | Docker Compose (app, nginx, MySQL) |

---

## Como subir com Docker

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

Aplicação: [http://localhost:8080](http://localhost:8080)

### Comandos úteis

```bash
# logs
docker compose logs -f app

# testes dentro do container
docker compose exec app php artisan test

# recriar banco com seeds
docker compose exec app php artisan migrate:fresh --seed
```

---

## Como rodar os testes (local)

Requisitos: PHP 8.2+, Composer, extensão `pdo_sqlite`.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan test
```

Os testes usam **SQLite em memória** (`phpunit.xml`), incluindo a VIEW do relatório (sintaxe adaptada por driver).

---

## Rotas principais

### Web (Blade)
| Rota | Descrição |
|------|-----------|
| `/` | Menu inicial |
| `/books` | CRUD de livros |
| `/authors` | CRUD de autores |
| `/subjects` | CRUD de assuntos |
| `/reports/books-by-author` | Exportação do relatório em PDF |
| `/documentation` | Swagger UI (OpenAPI desacoplada) |

### API (camelCase em inglês)
| Método | Rota |
|--------|------|
| CRUD | `/api/books`, `/api/authors`, `/api/subjects` |
| GET | `/api/reports/books-by-author/pdf` (PDF) |

Listagens aceitam `?page=` e `?perPage=` (máx. 100). Não há endpoint “all” ilimitado.

### OpenAPI

Spec desacoplada dos controllers em [`documentation/`](documentation/) (`openapi.yaml`, `paths/`, `components/`).  
UI: [http://localhost:8080/documentation](http://localhost:8080/documentation).

---

