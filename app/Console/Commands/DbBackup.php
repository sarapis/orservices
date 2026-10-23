<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Process\ExecutableFinder;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--keep=14 : How many dumps to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump the database to storage/app/backup (gzipped) and keep the newest N dumps';

    /**
     * Execute the console command.
     *
     * The password goes to the dump tool through MYSQL_PWD, never on its command line, and the
     * connection comes from config() so this keeps working if the config is ever cached.
     */
    public function handle(): int
    {
        $db = config('database.connections.' . config('database.default'));
        $dir = storage_path('app/backup');
        File::ensureDirectoryExists($dir);

        $file = $dir . '/backup-' . now()->format('Y-m-d') . '.sql.gz';
        $tmp = $file . '.tmp';
        $gz = gzopen($tmp, 'wb6');

        $binary = (new ExecutableFinder)->find('mariadb-dump') ?? 'mysqldump';
        $result = Process::env(['MYSQL_PWD' => (string) ($db['password'] ?? '')])
            ->timeout(600)
            ->run([
                $binary, '--single-transaction', '--routines', '--triggers',
                '--host=' . $db['host'], '--port=' . $db['port'], '--user=' . $db['username'],
                $db['database'],
            ], function (string $type, string $output) use ($gz) {
                if ($type === 'out') {
                    gzwrite($gz, $output);
                }
            });
        gzclose($gz);

        if ($result->failed()) {
            File::delete($tmp);
            $this->error('Database backup failed: ' . trim($result->errorOutput()));

            return self::FAILURE;
        }

        File::move($tmp, $file);
        $removed = self::prune($dir, max(1, (int) $this->option('keep')));
        $this->info('Saved ' . basename($file) . ($removed ? '; removed ' . count($removed) . ' old dump(s)' : ''));

        return self::SUCCESS;
    }

    /**
     * Delete all but the newest $keep backup-* files in $dir and return the deleted names.
     * Names sort by date, so the newest are last.
     */
    public static function prune(string $dir, int $keep): array
    {
        $files = glob($dir . '/backup-*.sql*') ?: [];
        $files = array_values(array_filter($files, fn ($f) => ! str_ends_with($f, '.tmp')));
        sort($files);
        $old = array_slice($files, 0, max(0, count($files) - $keep));
        File::delete($old);

        return array_map('basename', $old);
    }
}
