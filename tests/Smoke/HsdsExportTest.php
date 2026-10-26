<?php

namespace Tests\Smoke;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Smoke\Concerns\SetsUpSite;
use Tests\TestCase;
use ZipArchive;

/**
 * The HSDS zip export used to write its CSVs into the current directory and then rename them
 * from public/, so it only worked when PHP's cwd was public/ and public/ was writable.
 */
class HsdsExportTest extends TestCase
{
    use RefreshDatabase, SetsUpSite;

    private string $public;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpSite();
        // Keep the export away from the tracked files in public/csv_export.
        $this->public = realpath(sys_get_temp_dir()) . '/hsds_export_' . bin2hex(random_bytes(4));
        mkdir($this->public . '/csv_export/datapackage', 0755, true);
        $this->app->usePublicPath($this->public);
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->public));
        parent::tearDown();
    }

    public function test_the_hsds_zip_downloads_and_leaves_no_files_behind()
    {
        $this->insertRow('services', ['id' => 1, 'service_recordid' => 101, 'service_name' => 'Food pantry', 'service_organization' => 201]);
        $cwdBefore = glob(getcwd() . '/*.csv');

        $response = $this->actingAs($this->userWith(['dataSync.export_hsds_zip_file']))
            ->post('/export_hsds_zip_file', ['organization_tags' => ['download_all']]);

        $response->assertOk();
        $zipPath = $response->baseResponse->getFile()->getPathname();
        try {
            $zip = new ZipArchive();
            $this->assertTrue($zip->open($zipPath) === true, 'response is not a zip');
            $this->assertNotFalse($zip->locateName('services.csv'));
            $this->assertStringContainsString('Food pantry', $zip->getFromName('services.csv'));
            $zip->close();

            $this->assertStringStartsWith(storage_path('app/exports/'), $zipPath);
            $this->assertSame([], glob(storage_path('app/exports/*'), GLOB_ONLYDIR), 'work folder left behind');
        } finally {
            @unlink($zipPath); // deleteFileAfterSend only fires when a real response is sent
        }
        $this->assertSame($cwdBefore, glob(getcwd() . '/*.csv'), 'CSV files written to the current directory');
        $this->assertFileExists($this->public . '/csv_export/datapackage/services.csv');
    }
}
