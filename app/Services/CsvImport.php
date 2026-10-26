<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use RuntimeException;

/**
 * Checks shared by every CSV importer, run before an import touches the database.
 *
 * Several importers truncate their tables and then import; a file missing a column used to
 * fail on the first row with the tables already empty. assertColumns() rejects such a file
 * up front, using each import class's REQUIRED_COLUMNS (the columns its model() reads
 * without a fallback).
 */
class CsvImport
{
    /**
     * The uploaded file in $field, checked against SafeUpload's CSV allowlist.
     *
     * @throws ValidationException
     */
    public static function upload(Request $request, string $field = 'csv_file'): UploadedFile
    {
        $file = $request->file($field);
        if (! $file instanceof UploadedFile) {
            throw ValidationException::withMessages([$field => 'Choose a CSV file to import.']);
        }
        SafeUpload::extension($file, SafeUpload::CSV, $field);

        return $file;
    }

    /**
     * Throw unless the heading row of $file has every column $import requires. Headings are
     * slugged exactly as WithHeadingRow slugs them ("Location ID" -> location_id).
     *
     * @throws RuntimeException
     */
    public static function assertColumns(object $import, UploadedFile|string $file): void
    {
        $required = defined($import::class . '::REQUIRED_COLUMNS') ? $import::REQUIRED_COLUMNS : [];
        $headings = Excel::toArray(new HeadingRowImport, $file)[0][0] ?? [];
        $missing = array_values(array_diff($required, $headings));
        if ($missing) {
            $name = $file instanceof UploadedFile ? $file->getClientOriginalName() : basename($file);
            throw new RuntimeException("$name is missing column(s): " . implode(', ', $missing) . '. Nothing was imported or deleted.');
        }
    }
}
