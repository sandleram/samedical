<?php

namespace Tests\Unit\Application\Blob;

use App\Application\Blob\DownloadBlob;
use App\Domain\Blob\BlobFile;
use App\Domain\Blob\BlobRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DownloadBlobTest extends TestCase
{
    public function test_rejects_invalid_md5(): void
    {
        $repo = new class implements BlobRepositoryInterface
        {
            public function findActiveByIdMd5(string $idMd5): ?BlobFile
            {
                throw new \RuntimeException('should not be called');
            }
        };

        $uc = new DownloadBlob($repo);
        $this->assertNull($uc->execute('short'));
        $this->assertNull($uc->execute(str_repeat('g', 32)));
    }

    public function test_delegates_valid_md5(): void
    {
        $file = new BlobFile(1, 'a.pdf', 'application/pdf', 3, 'abc', 1);
        $repo = new class($file) implements BlobRepositoryInterface
        {
            public function __construct(private BlobFile $file) {}

            public function findActiveByIdMd5(string $idMd5): ?BlobFile
            {
                return $this->file;
            }
        };

        $uc = new DownloadBlob($repo);
        $this->assertSame($file, $uc->execute(str_repeat('a', 32)));
    }
}
