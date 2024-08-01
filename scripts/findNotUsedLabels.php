<?php
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
require_once 'include/entryPoint.php';
$label_dirs = ['../legacy/modules', '../legacy/include/language'];
$label_file_name_prefix = 'en_us';
$directores_used_labels = ['../api', '../vue/src', '../legacy/custom', '../legacy/include', '../legacy/modules', '../legacy/themes'];
$directores_used_labels_exclude_dir = 'language';
$labels_prefixes = ['LBL_', 'ERR_', 'LNK_'];

function getFilesFromDirectory($directory)
{
    if (!is_dir($directory)) {
        return [];
    }
    $items = scandir($directory);
    $files = [];
    // Iteracja przez każdy element w katalogu
    foreach ($items as $item) {
        // Pomijanie "." i ".."
        if ('.' === $item || '..' === $item) {
            continue;
        }

        $path = $directory . DIRECTORY_SEPARATOR . $item;

        // Sprawdzenie, czy ścieżka jest katalogiem
        if (is_dir($path)) {
            // Rekursywne wywołanie funkcji dla podkatalogów
            $files = array_merge($files, getFilesFromDirectory($path));
        } else {
            // Dodanie pliku do listy
            $files[] = $path;
        }
    }

    return $files;
}

$used_labels_by_application = [];
//read all files from $directores_used_labels and find text in the files by reqex, then add to $all_labels
foreach ($directores_used_labels as $directores_used_label) {
    $files = array_filter(getFilesFromDirectory($directores_used_label),
        function ($file) use ($directores_used_labels_exclude_dir) {
            return strpos($file, $directores_used_labels_exclude_dir) === false;
        });
    foreach ($files as $file) {
        $content = file_get_contents($file);
        foreach ($labels_prefixes as $labels_prefix) {
            $pattern = '/\b' . $labels_prefix . '.*?\b/';
            preg_match_all($pattern, $content, $matches);
            if (is_array($matches[0]) && !empty($matches[0])) {
                $x = 12;
                foreach (array_unique($matches[0]) as $match) {
                    $used_labels_by_application[$match] = $match;
                }
            }
        }
    }
}

$label_files = [];
foreach ($label_dirs as $label_dir) {
    $label_files = array_merge($label_files, array_filter(getFilesFromDirectory($label_dir), function ($file) use ($label_file_name_prefix) {
        return strpos($file, $label_file_name_prefix) !== false;
    }));
}

foreach ($label_files as $label_file) {
    $mod_strings = [];
    $app_strings = [];
    include $label_file;
    $labels = array_merge([], $mod_strings, $app_strings);
    // $used_labels = [];
    $not_used_labels = [];
    foreach ($labels as $label_key => $label_value) {
        if (in_array($label_key, $used_labels_by_application)) {
            $used_labels[$label_key] = $label_value;
        } else {
            $not_used_labels[$label_key] = $label_key;
        }
    }
    if (!empty($not_used_labels)) {
        echo '<br><br>Not used labels in ' . $label_file . '<br>';
        print_r(array_keys($not_used_labels));
        echo '<hr>';
    }
}

//get all files from dir
// $files = array_filter(glob('../legacy/modules/*.*'), 'is_file');
