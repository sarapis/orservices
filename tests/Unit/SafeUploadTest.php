<?php

namespace Tests\Unit;

use App\Services\SafeUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use ZipArchive;

class SafeUploadTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir() . '/safeupload_' . bin2hex(random_bytes(4));
        mkdir($this->dir . '/out', 0755, true);
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->dir));
        parent::tearDown();
    }

    /** An upload whose client name is $name and whose bytes are $bytes. */
    private function upload(string $name, string $bytes): UploadedFile
    {
        $path = $this->dir . '/' . bin2hex(random_bytes(4));
        file_put_contents($path, $bytes);

        return new UploadedFile($path, $name, null, null, true);
    }

    private function fixture(string $name): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/uploads/' . $name);
    }

    private function polyglot(): string
    {
        return $this->fixture('tiny.jpg') . "<?=/****/@null; eval(base64_decode('ZWNobyAxOw=='));";
    }

    public function test_real_png_is_saved_as_png()
    {
        $name = SafeUpload::save($this->upload('logo.png', $this->fixture('tiny.png')), $this->dir . '/out', '123category_logo_1', SafeUpload::IMAGE);

        $this->assertSame('123category_logo_1.png', $name);
        $this->assertFileExists($this->dir . '/out/' . $name);
    }

    public function test_real_jpeg_is_saved_as_jpg()
    {
        $name = SafeUpload::save($this->upload('photo.jpeg', $this->fixture('tiny.jpg')), $this->dir . '/out', 'photo', SafeUpload::IMAGE);

        $this->assertSame('photo.jpg', $name);
    }

    public function test_php_file_is_rejected()
    {
        $this->expectException(ValidationException::class);
        SafeUpload::save($this->upload('shell.php', "<?php system(\$_GET['c']);"), $this->dir . '/out', 'x', SafeUpload::IMAGE);
    }

    public function test_jpeg_php_polyglot_named_php_is_rejected()
    {
        // The shape of the shell found on the server: a real JPEG with PHP appended, named .php.
        $this->expectException(ValidationException::class);
        SafeUpload::save($this->upload('category_logo.php', $this->polyglot()), $this->dir . '/out', 'logo', SafeUpload::IMAGE);
    }

    public function test_jpeg_php_polyglot_with_image_name_is_saved_as_jpg()
    {
        // It passes as an image, but it can only ever be served as .jpg, never run.
        $name = SafeUpload::save($this->upload('category_logo.jpg', $this->polyglot()), $this->dir . '/out', 'logo', SafeUpload::IMAGE);

        $this->assertSame('logo.jpg', $name);
        $this->assertSame([], glob($this->dir . '/out/*.php'));
    }

    public function test_double_extension_png_is_rejected()
    {
        $this->expectException(ValidationException::class);
        SafeUpload::save($this->upload('logo.png.php', $this->fixture('tiny.png')), $this->dir . '/out', 'logo', SafeUpload::IMAGE);
    }

    public function test_double_extension_with_php_content_is_rejected()
    {
        $this->expectException(ValidationException::class);
        SafeUpload::save($this->upload('logo.png.php', "<?php echo 'pwned';"), $this->dir . '/out', 'logo', SafeUpload::IMAGE);
    }

    public function test_svg_is_rejected()
    {
        $this->expectException(ValidationException::class);
        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>';
        SafeUpload::save($this->upload('logo.svg', $svg), $this->dir . '/out', 'logo', SafeUpload::IMAGE);
    }

    public function test_csv_is_saved_as_csv_whatever_its_client_name()
    {
        $name = SafeUpload::save($this->upload('data.php', "name,phone\nA,1\n"), $this->dir . '/out', 'metafilter_1', SafeUpload::CSV);

        $this->assertSame('metafilter_1.csv', $name);
    }

    public function test_php_named_csv_is_rejected()
    {
        $this->expectException(ValidationException::class);
        SafeUpload::save($this->upload('services.csv', "<?php system(\$_GET['c']); ?>\n"), $this->dir . '/out', 'x', SafeUpload::CSV);
    }

    public function test_zip_must_really_be_a_zip()
    {
        $this->expectException(ValidationException::class);
        SafeUpload::extension($this->upload('hsds.zip', "<?php echo 1;"), SafeUpload::ZIP);
    }

    public function test_name_strips_path_and_dots_from_the_base()
    {
        $this->assertSame('etcpasswd.zip', SafeUpload::name('../../etc/passwd', 'zip'));
        $this->assertSame('shellphp.csv', SafeUpload::name('shell.php', 'csv'));
        $this->assertMatchesRegularExpression('/^\d+\.zip$/', SafeUpload::name('../', 'zip'));
    }

    public function test_zip_entry_paths()
    {
        $this->assertSame('data/services.csv', SafeUpload::safeEntryPath('data/services.csv'));
        $this->assertSame('datapackage.json', SafeUpload::safeEntryPath('datapackage.json'));

        foreach ([
            '../escape.csv', 'data/../../escape.csv', '/etc/cron.d/x.csv', 'C:\\x\\..\\y.csv',
            'shell.php', 'data/shell.phtml', 'a.php.csv', 'data/x.phar',
            '.hidden.csv', '__MACOSX/._services.csv', 'data/', 'image.png',
        ] as $entry) {
            $this->assertNull(SafeUpload::safeEntryPath($entry), $entry);
        }
    }

    public function test_extract_zip_writes_only_data_files_inside_dest()
    {
        $zipPath = $this->dir . '/hsds.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);
        $zip->addFromString('data/services.csv', "id,name\n1,A\n");
        $zip->addFromString('data/shell.php', '<?php echo 1;');
        $zip->addFromString('data/a.php.csv', '<?php echo 1;');
        $zip->addFromString('../escape.csv', "x\n");
        $zip->close();

        $written = SafeUpload::extractZip($zipPath, $this->dir . '/out');

        $this->assertSame(['data/services.csv'], $written);
        $this->assertFileExists($this->dir . '/out/data/services.csv');
        $this->assertFileDoesNotExist($this->dir . '/out/data/shell.php');
        $this->assertFileDoesNotExist($this->dir . '/out/data/a.php.csv');
        $this->assertFileDoesNotExist($this->dir . '/escape.csv');
    }
}
