<?php

namespace Tests\Smoke;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Smoke\Concerns\SetsUpSite;
use Tests\TestCase;

/**
 * Every admin CSV import route, end to end on in-memory SQLite: a good file imports, and a file
 * missing a required column is refused before anything is deleted.
 */
class CsvImportTest extends TestCase
{
    use RefreshDatabase, SetsUpSite;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpSite();
    }

    /** route URL, route name, import class, model counted, c_s_v__sources row the route updates */
    public static function importers(): array
    {
        return [
            'accessibility' => ['/csv_accessibility_for_disabilites', 'import.accessibility', 'AccessibilityImport', 'Accessibility', 'Accessibility_for_disabilites'],
            'addresses' => ['/csv_address', 'import.addresses', 'AddressImport', 'Address', 'Address'],
            'service areas' => ['/csv_service_areas', 'import.service_areas', 'AreaImport', 'Area', 'Service_areas'],
            'contacts' => ['/csv_contacts', 'import.contacts', 'ContactImport', 'Contact', 'Contacts'],
            'locations' => ['/csv_locations', 'import.location', 'LocationImport', 'Location', 'Locations'],
            'organizations' => ['/csv_organizations', 'import.organizations', 'OrganizationImport', 'Organization', 'Organizations'],
            'phones' => ['/csv_phones', 'import.phones', 'PhoneImport', 'Phone', 'Phones'],
            'schedules' => ['/csv_regular_schedules', 'import.schedule', 'ScheduleImport', 'Schedule', 'Regular_schedules'],
            'services' => ['/csv_services', 'import.services', 'Services', 'Service', 'Services'],
            'services at location' => ['/csv_services_location', 'import.services_location', 'ServiceLocationImport', 'ServiceLocation', 'Services_location'],
            'taxonomy' => ['/csv_taxonomy', 'import.taxonomy', 'TaxonomyImport', 'Taxonomy', 'Taxonomy'],
            'services taxonomy' => ['/csv_services_taxonomy', 'import.services_taxonomy', 'ServiceTaxonomyImport', 'ServiceTaxonomy', 'Services_taxonomy'],
            'languages' => ['/csv_languages', 'import.languages', 'LanguageImport', 'Language', 'Languages'],
        ];
    }

    /** A small CSV with exactly $columns and two rows of plausible values. */
    private function csv(array $columns, string $name = 'import.csv'): UploadedFile
    {
        $value = function (string $col, int $n) {
            if (preg_match('/(^id$|_id$|recordid|^order$|^priority$)/', $col)) {
                return (string) (1000 + $n);
            }
            if (preg_match('/date|valid_|dtstart|until/', $col)) {
                return '2024-01-0' . $n;
            }
            if (preg_match('/^(opens|closes)/', $col)) {
                return $col === 'opens' ? '09:00' : '17:00';
            }

            return "$col $n";
        };
        $lines = [implode(',', $columns)];
        foreach ([1, 2] as $n) {
            $lines[] = implode(',', array_map(fn ($c) => '"' . $value($c, $n) . '"', $columns));
        }

        return UploadedFile::fake()->createWithContent($name, implode("\n", $lines) . "\n");
    }

    private function upload(string $url, string $routeName, UploadedFile $file)
    {
        return $this->actingAs($this->userWith([$routeName]))
            ->post($url, ['csv_file' => $file]);
    }

    #[DataProvider('importers')]
    public function test_a_good_file_imports(string $url, string $routeName, string $import, string $model, string $source)
    {
        $this->insertRow('c_s_v__sources', ['name' => $source]);
        $columns = ("App\\Imports\\$import")::REQUIRED_COLUMNS;

        $response = $this->upload($url, $routeName, $this->csv($columns));

        $response->assertOk()->assertJson(['status' => 'success']);
        $this->assertSame(2, ("App\\Model\\$model")::count());
    }

    #[DataProvider('importers')]
    public function test_a_file_missing_a_column_changes_nothing(string $url, string $routeName, string $import, string $model, string $source)
    {
        $this->insertRow('c_s_v__sources', ['name' => $source]);
        $table = (new ("App\\Model\\$model"))->getTable();
        $this->insertRow($table, ['id' => 999]);
        $columns = ("App\\Imports\\$import")::REQUIRED_COLUMNS;
        $missing = array_pop($columns);

        $response = $this->upload($url, $routeName, $this->csv($columns, 'wrong.csv'));

        $response->assertOk();
        $this->assertNotSame('success', $response->json('status'));
        $this->assertStringContainsString("missing column(s): $missing", (string) $response->json('result'));
        $this->assertSame(1, DB::table($table)->count(), 'existing rows must survive a rejected file');
    }

    public function test_a_non_csv_upload_is_refused()
    {
        $this->insertRow('c_s_v__sources', ['name' => 'Address']);
        $this->insertRow('addresses', ['id' => 999]);
        $php = UploadedFile::fake()->createWithContent('addresses.csv', "<?php system(\$_GET['c']); ?>\n");

        $response = $this->upload('/csv_address', 'import.addresses', $php);

        $this->assertNotSame('success', $response->json('status'));
        $this->assertSame(1, DB::table('addresses')->count());
    }
}
