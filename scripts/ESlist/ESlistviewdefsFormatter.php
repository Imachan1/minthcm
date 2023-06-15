<?php

function findFilesInDirectory($directory, $fileName)
{
    $files = [];

    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ("." != $file && ".." != $file) {
                if ($file == $fileName) {
                    $files[] = $directory . "/" . $file;
                }
                if (is_dir($directory . "/" . $file)) {
                    $files = array_merge(
                        $files,
                        findFilesInDirectory($directory . "/" . $file, $fileName)
                    );
                }
            }
        }
        closedir($handle);
    }

    return $files;
}

$directory = "/var/www/minthcm_repo/MintHCM";
$fileName = "eslistviewdefs.php";

$files = findFilesInDirectory($directory, $fileName);

foreach ($files as $file) {
    shell_exec("php-cs-fixer fix {$file} --rules=@PhpCsFixer");
}
