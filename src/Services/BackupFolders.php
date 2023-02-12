<?php

namespace App\Services;

use App\Services\Command\CommandLogger;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BackupFolders
{
    use CommandLogger;

    CONST DEFAULT_DUMP_DIR = 'database-dump/';

    /**
    * @var string $rootDir
    */
    private $rootDir;

    public function __construct(ParameterBagInterface $params)
    {
        $this->rootDir = $params->get('root_dir');
    }

    /**
     * Archives the given folder content to a zip
     * 
     * @var string $directory Directory to archive
     * @var string $archiveName Name of the final archive
     * @var string $outputPath Directory where the archive will be saved
     */
    public function zipArchive(string $directory, string $archiveName, string $outputPath = self::DEFAULT_DUMP_DIR, array $additionalFiles = [])
    {
        $source = $this->rootDir . $directory;
        $fullOutputPath = $this->rootDir . '/' . $outputPath . $archiveName;

        if (extension_loaded('zip') && is_dir($source)) {
            $zip = new \ZipArchive();

            if ($zip->open($fullOutputPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::LEAVES_ONLY);

                $zip->addEmptyDir('documents');

                $filesCount = 0;

                foreach ($files as $file) {
                    // Skip directories (they would be added automatically)
                    if (!$file->isDir()) {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();

                        $explodedPath = explode('documents', $filePath);
                        $relativePath = trim(end($explodedPath), "\/");
                
                        // Add current file to archive
                        $zip->addFile($filePath, 'documents/' . $relativePath);

                        $filesCount++;
                    }
                }

                if ($additionalFiles) {
                    $this->log(sprintf('Add dumpped database to archive'));

                    foreach($additionalFiles as $additionalFile) {
                        $zip->addFile($additionalFile);
                    }
                }
            }

            $this->log(sprintf('%s files archived to "%s"', $filesCount, $outputPath . $archiveName));

            return $zip->close();
        }

        return false;
    }
}
