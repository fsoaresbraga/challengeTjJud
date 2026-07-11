<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\ReportService;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ReportServiceTest extends TestCase
{
    private ReportRepositoryInterface&MockInterface $reportRepository;

    private ReportService $reportService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reportRepository = Mockery::mock(ReportRepositoryInterface::class);
        $this->reportService = new ReportService($this->reportRepository);
    }

    public function test_books_by_author_delegates_to_repository(): void
    {
        $rows = collect([
            (object) ['authorName' => 'A', 'title' => 'Book 1'],
        ]);

        $this->reportRepository
            ->shouldReceive('booksByAuthor')
            ->once()
            ->andReturn($rows);

        $this->assertSame($rows, $this->reportService->booksByAuthor());
    }

    public function test_books_grouped_by_author_groups_collection(): void
    {
        $rows = collect([
            (object) ['authorName' => 'Machado', 'title' => 'Dom Casmurro'],
            (object) ['authorName' => 'Machado', 'title' => 'Memórias'],
            (object) ['authorName' => 'Clarice', 'title' => 'A Hora da Estrela'],
        ]);

        $this->reportRepository
            ->shouldReceive('booksByAuthor')
            ->once()
            ->andReturn($rows);

        $grouped = $this->reportService->booksGroupedByAuthor();

        $this->assertInstanceOf(Collection::class, $grouped);
        $this->assertCount(2, $grouped);
        $this->assertCount(2, $grouped->get('Machado'));
        $this->assertCount(1, $grouped->get('Clarice'));
        $this->assertSame('Dom Casmurro', $grouped->get('Machado')->first()->title);
    }
}
