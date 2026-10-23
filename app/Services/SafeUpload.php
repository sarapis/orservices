<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;
use RuntimeException;
use ZipArchive;

/**
 * The one place that decides what an uploaded file may be saved as under public/.
 *
 * Saved names never come from the client: the extension is guessed from the file's
 * content and must be in the allowlist for that kind of upload, and the base name is
 * built by the caller. SVG is not accepted for images: it can carry script that runs
 * on this origin, and Intervention Image can't resize it anyway.
 */
class SafeUpload
{
    // Guessed extension => extension the file is saved under.
    public const IMAGE = ['png' => 'png', 'jpg' => 'jpg', 'jpeg' => 'jpg', 'gif' => 'gif', 'webp' => 'webp'];
    public const CSV = ['csv' => 'csv', 'txt' => 'csv'];
    public const ZIP = ['zip' => 'zip'];

    public const IMAGE_RULES = 'image|mimes:png,jpg,jpeg,gif,webp|max:5120';
    public const FILE_RULES = 'file|max:51200';

    // Files taken out of an uploaded HSDS zip; everything else in the archive is skipped.
    public const ZIP_ENTRY_EXTENSIONS = ['csv', 'json', 'txt'];

    /**
     * Validate $file and return the extension to save it under.
     *
     * @throws ValidationException
     */
    public static function extension(UploadedFile $file, array $allowed, string $field = 'file'): string
    {
        $rules = $allowed === self::IMAGE ? self::IMAGE_RULES : self::FILE_RULES;
        Validator::make([$field => $file], [$field => 'required|' . $rules])->validate();

        $guessed = strtolower((string) $file->guessExtension());
        if (! isset($allowed[$guessed])) {
            throw ValidationException::withMessages([$field => 'This file type is not allowed.']);
        }

        return $allowed[$guessed];
    }

    /**
     * Validate $file, move it into $dir as "<basename>.<safe extension>" and return that name.
     *
     * @throws ValidationException
     */
    public static function save(UploadedFile $file, string $dir, string $basename, array $allowed, string $field = 'file'): string
    {
        $name = self::name($basename, self::extension($file, $allowed, $field));
        $file->move($dir, $name);

        return $name;
    }

    /**
     * Download an image (e.g. an Airtable attachment URL), re-encode it and save it into $dir.
     * The saved name keeps the remote file's base name, cleaned, with an allowlisted image
     * extension (png when the remote one isn't). Returns the saved name.
     */
    public static function saveRemoteImage(string $url, string $dir, string $filename): string
    {
        $extension = self::IMAGE[strtolower(pathinfo($filename, PATHINFO_EXTENSION))] ?? 'png';
        $name = self::name(pathinfo($filename, PATHINFO_FILENAME), $extension);
        $bytes = Http::timeout(30)->get($url)->throw()->body();
        ImageManager::gd()->read($bytes)->save(rtrim($dir, '/') . '/' . $name);

        return $name;
    }

    public static function name(string $basename, string $extension): string
    {
        $base = preg_replace('/[^A-Za-z0-9_-]/', '', $basename);

        return ($base === '' ? (string) time() : $base) . '.' . $extension;
    }

    /**
     * Extract only the data files (ZIP_ENTRY_EXTENSIONS) from $zipPath into $dest, keeping
     * their relative paths. Entries that would land outside $dest, hidden files and anything
     * with a script extension are skipped. Returns the relative paths written.
     */
    public static function extractZip(string $zipPath, string $dest): array
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException('Could not open the zip file.');
        }

        $written = [];
        try {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                $relative = self::safeEntryPath($entry);
                if ($relative === null) {
                    continue;
                }

                $target = rtrim($dest, '/') . '/' . $relative;
                if (! is_dir(dirname($target))) {
                    mkdir(dirname($target), 0755, true);
                }
                $in = $zip->getStream($entry);
                $out = fopen($target, 'wb');
                stream_copy_to_stream($in, $out);
                fclose($in);
                fclose($out);
                $written[] = $relative;
            }
        } finally {
            $zip->close();
        }

        return $written;
    }

    /**
     * The relative path to extract a zip entry to, or null if it must be skipped.
     */
    public static function safeEntryPath(string $entry): ?string
    {
        $entry = str_replace('\\', '/', $entry);
        if ($entry === '' || str_starts_with($entry, '/') || str_ends_with($entry, '/')) {
            return null;
        }
        foreach (explode('/', $entry) as $part) {
            if (! preg_match('/^[A-Za-z0-9_-][A-Za-z0-9 ._-]*$/', $part)) {
                return null; // empty, "..", hidden, or odd characters
            }
        }
        if (preg_match('/\.(php\d?|phtml|phar|pht|phps|inc)(\.|$)/i', $entry)) {
            return null;
        }
        if (! in_array(strtolower(pathinfo($entry, PATHINFO_EXTENSION)), self::ZIP_ENTRY_EXTENSIONS, true)) {
            return null;
        }

        return $entry;
    }
}
