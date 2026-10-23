<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Every controller must save uploads through App\Services\SafeUpload. This fails if one
 * goes back to naming files from the client or saving/extracting them directly.
 */
class UploadHandlersTest extends TestCase
{
    // Methods that save or extract an upload without SafeUpload's checks.
    private const FORBIDDEN = ['getClientOriginalExtension', 'move', 'store', 'storeAs', 'storePublicly', 'extract', 'extractTo'];

    public function test_controllers_do_not_save_uploads_directly()
    {
        $found = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__ . '/../../app/Http/Controllers'));
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $tokens = token_get_all(file_get_contents($file->getPathname()));
            foreach ($tokens as $i => $token) {
                if (! is_array($token) || $token[0] !== T_STRING || ! in_array($token[1], self::FORBIDDEN, true)) {
                    continue;
                }
                $prev = $this->previousCodeToken($tokens, $i);
                $next = $this->nextCodeToken($tokens, $i);
                if (is_array($prev) && in_array($prev[0], [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR], true) && $next === '(') {
                    $found[] = basename($file->getPathname()) . ':' . $token[2] . ' ->' . $token[1] . '()';
                }
            }
        }

        $this->assertSame([], $found, "Save uploads with App\\Services\\SafeUpload instead:\n" . implode("\n", $found));
    }

    private function previousCodeToken(array $tokens, int $i)
    {
        for ($j = $i - 1; $j >= 0; $j--) {
            if (! is_array($tokens[$j]) || ! in_array($tokens[$j][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                return $tokens[$j];
            }
        }

        return null;
    }

    private function nextCodeToken(array $tokens, int $i)
    {
        for ($j = $i + 1; $j < count($tokens); $j++) {
            if (! is_array($tokens[$j]) || ! in_array($tokens[$j][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                return $tokens[$j];
            }
        }

        return null;
    }
}
