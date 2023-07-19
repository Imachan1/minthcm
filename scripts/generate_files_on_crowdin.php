<?php

$path = "../MintHCM/";
$directores_to_scan = ['include', 'install', 'modules'];
function scan($source_path, $subdir, $output_dir_name)
{
    $path_wih_source = $source_path . $subdir;
    $result = [];
    if ($handle = opendir($path_wih_source)) {

        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $file_path = $path_wih_source . "/" . $entry;
                $relative_path = $subdir . "/" . $entry;
                if (!is_dir($file_path) && substr($entry, 0, strlen('en_us')) == 'en_us' && !is_excluded_folder($relative_path)) {
                    // echo $path_wih_source . "/" . $entry."\n";
                    if (!is_dir($output_dir_name / $subdir)) {
                        if (!mkdir("$output_dir_name/$subdir", 0777, true)) {
                            // echo "Failed to create folder $output_dir_name/$subdir\n";
                        }
                    }
                    $new_file_name = "$output_dir_name/$relative_path";
                    if (!copy("$file_path", "$new_file_name")) {
                        echo "failed to copy $file_path into $new_file_name...\n";
                    }
                } else if (is_dir($file_path)) {
                    scan($source_path, $relative_path, 'output');
                }
            }
        }

        closedir($handle);
    }
    return $result;
}
mkdir('output', 0777, true);
foreach ($directores_to_scan as $subdir) {
    $files = scan($path, $subdir, 'output');
}


function is_excluded_folder($path)
{
    $excluded_modules = [
        'Accounts',
        'Bugs',
        'Cases',
        'Contacts',
        'Leads',
        'Opportunities',
        'Prospects',
    ];
    foreach ($excluded_modules as $module) {
        if (strpos($path, 'modules/' . $module) === 0) {
            return true;
        }
    }
    return false;
}
