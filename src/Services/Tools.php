<?php

namespace App\Services;


trait Tools
{
    public function deleteDirectory(string $dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dir . '/' . $object) == 'dir') {
                        self::deleteDirectory($dir . '/' . $object);
                    }
                    else {
                        unlink($dir . '/' . $object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    public function createDirIfNotExists(string $folderPath): ?string
    {
        if(!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        return $folderPath;
    }

    public function getFolderFiles(string $path, array $fileExtensions): array
    {
        $files = [];
        $folder = opendir($path);
        if (!$folder) {
            return $files;
        }

        while(false !== ($file = readdir($folder))) {
            if ($file != '.' && $file != '..' && !is_dir($file)) {
                $pathFile  = $path . $file;
                $extension = pathinfo($pathFile, PATHINFO_EXTENSION);
                
                // To sort files by creation date
                
                if (in_array(strtolower($extension), $fileExtensions)) {
                   

                    $fileSub = explode("." , $file) ; 
                    $createdTime = strtotime($fileSub[0]);
                    $files[$createdTime] = $file;
                }
            }
        }

        krsort($files);
        return $files;
    }
}
