<?php

namespace Tests\Smoke;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Smoke\Concerns\SetsUpSite;
use Tests\TestCase;

/**
 * Every import class, including those only the HSDS zip importers use: a row holding just its
 * REQUIRED_COLUMNS must build a model that saves. Catches columns that are missing from
 * REQUIRED_COLUMNS and NOT NULL columns the import would fill with NULL.
 */
class ImportClassesTest extends TestCase
{
    use RefreshDatabase, SetsUpSite;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpSite();
    }

    public static function importClasses(): array
    {
        $classes = [];
        foreach (glob(__DIR__ . '/../../app/Imports/*.php') as $file) {
            $name = basename($file, '.php');
            $classes[$name] = ["App\\Imports\\$name"];
        }

        return $classes;
    }

    #[DataProvider('importClasses')]
    public function test_a_row_with_only_the_required_columns_imports(string $class)
    {
        $columns = defined("$class::REQUIRED_COLUMNS") ? $class::REQUIRED_COLUMNS : [];
        $row = [];
        foreach ($columns as $i => $col) {
            $row[$col] = preg_match('/(^id$|_id$|recordid|^order$|^priority$)/', $col) ? (string) (500 + $i) : "$col value";
        }

        $model = (new $class)->model($row);
        if ($model !== null) {
            $model->save();
            $this->assertTrue($model->exists, "$class saved nothing");
        } else {
            $this->addToAssertionCount(1); // null = handled in place (e.g. an update)
        }
    }
}
