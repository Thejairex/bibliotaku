<?php
// app/Console/Commands/TestJikan.php
namespace App\Console\Commands;

use App\Enums\MediaType;
use App\Services\JikanService;
use Illuminate\Console\Command;

class TestJikan extends Command
{
    protected $signature = 'jikan:test
                            {query : Título a buscar}
                            {--type=anime : Tipo (anime, manga, manhwa, manhua, novel)}
                            {--limit=5 : Cantidad de resultados}
                            {--id= : Buscar por MAL ID directo}';

    protected $description = 'Prueba la integración con Jikan API';

    public function handle(JikanService $jikan): int
    {
        $type = $this->option('type');
        $malId = $this->option('id');

        // Búsqueda por ID directo
        if ($malId) {
            $this->info("Buscando MAL ID: {$malId} ({$type})...");
            $result = $type === 'anime'
                ? $jikan->getAnime((int) $malId)
                : $jikan->getManga((int) $malId);

            if (!$result) {
                $this->error('No se encontró resultado.');
                return self::FAILURE;
            }

            $this->renderSingle($result);
            return self::SUCCESS;
        }

        // Búsqueda por texto
        $query = $this->argument('query');
        $limit = (int) $this->option('limit');

        $this->info("Buscando \"{$query}\" en [{$type}] (limit: {$limit})...\n");

        $results = $jikan->searchByType($type, $query, $limit);

        if (empty($results)) {
            $this->warn('Sin resultados.');
            return self::SUCCESS;
        }

        $this->renderTable($results);

        // Mostrar detalle del primer resultado
        if ($this->confirm("\n¿Ver detalle del primer resultado?", true)) {
            $this->renderSingle($results[0]);
        }

        return self::SUCCESS;
    }

    private function renderTable(array $results): void
    {
        $rows = collect($results)->map(fn($r) => [
            $r['mal_id'],
            str($r['title'])->limit(40),
            $r['type'] ?? '-',
            $r['total_episodes'] ?? $r['total_chapters'] ?? '?',
            $r['score'] ?? '-',
            $r['status'] ?? '-',
        ]);

        $this->table(
            ['MAL ID', 'Título', 'Tipo', 'Eps/Caps', 'Score', 'Estado'],
            $rows
        );
    }

    private function renderSingle(array $r): void
    {
        $this->newLine();
        $this->line("<fg=magenta>════════════════════════════════════════</>");
        $this->line("<fg=white;options=bold> {$r['title']}</>");
        $this->line("<fg=magenta>════════════════════════════════════════</>");
        $this->line(" <fg=gray>MAL ID:</>       {$r['mal_id']}");
        $this->line(" <fg=gray>Tipo:</>         " . ($r['type'] ?? '-'));
        $this->line(" <fg=gray>Estado:</>       " . ($r['status'] ?? '-'));
        $this->line(" <fg=gray>Score MAL:</>    " . ($r['score'] ?? '-'));
        $this->line(" <fg=gray>Episodios:</>    " . ($r['total_episodes'] ?? '-'));
        $this->line(" <fg=gray>Capítulos:</>    " . ($r['total_chapters'] ?? '-'));
        $this->line(" <fg=gray>Volúmenes:</>    " . ($r['total_volumes'] ?? '-'));
        $this->line(" <fg=gray>URL MAL:</>      " . ($r['mal_url'] ?? '-'));
        $this->line(" <fg=gray>Cover:</>        " . ($r['cover_url'] ?? '-'));

        if ($r['synopsis']) {
            $this->newLine();
            $this->line(" <fg=gray>Sinopsis:</>");
            $this->line(' ' . str($r['synopsis'])->limit(200));
        }

        $this->newLine();
    }
}