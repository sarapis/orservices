<?php

namespace Tests\Unit;

use App\Console\Commands\DbBackup;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class DbBackupTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir() . '/dbbackup_' . bin2hex(random_bytes(4));
        mkdir($this->dir . '/app/backup', 0755, true);
        $this->app->useStoragePath($this->dir);

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => 'orservices-db',
            'database.connections.mysql.port' => '3306',
            'database.connections.mysql.database' => 'orservice_v2',
            'database.connections.mysql.username' => 'orservices',
            'database.connections.mysql.password' => 's3cret-pw',
        ]);
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->dir));
        parent::tearDown();
    }

    public function test_password_goes_through_the_environment_not_the_command_line()
    {
        Process::fake();

        $this->artisan('db:backup')->assertSuccessful();

        Process::assertRan(function (PendingProcess $process) {
            $argv = implode(' ', (array) $process->command);

            return ! str_contains($argv, 's3cret-pw')
                && str_contains($argv, '--host=orservices-db')
                && str_ends_with($argv, 'orservice_v2')
                && ($process->environment['MYSQL_PWD'] ?? null) === 's3cret-pw';
        });
        $this->assertFileExists($this->dir . '/app/backup/backup-' . now()->format('Y-m-d') . '.sql.gz');
    }

    public function test_a_failed_dump_leaves_no_file()
    {
        Process::fake(['*' => Process::result(errorOutput: 'Access denied', exitCode: 2)]);

        $this->artisan('db:backup')->assertFailed();

        $this->assertSame([], glob($this->dir . '/app/backup/*'));
    }

    public function test_prune_keeps_the_newest_dumps()
    {
        $backup = $this->dir . '/app/backup';
        foreach (['2026-10-01.sql', '2026-10-02.sql', '2026-10-03.sql.gz', '2026-10-04.sql.gz'] as $d) {
            touch("$backup/backup-$d");
        }
        touch("$backup/backup-2026-10-05.sql.gz.tmp");
        touch("$backup/notes.txt");

        $removed = DbBackup::prune($backup, 2);

        $this->assertSame(['backup-2026-10-01.sql', 'backup-2026-10-02.sql'], $removed);
        $this->assertFileExists("$backup/backup-2026-10-03.sql.gz");
        $this->assertFileExists("$backup/backup-2026-10-04.sql.gz");
        $this->assertFileExists("$backup/backup-2026-10-05.sql.gz.tmp");
        $this->assertFileExists("$backup/notes.txt");
    }
}
