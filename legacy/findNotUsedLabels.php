<?php
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
require_once 'include/entryPoint.php';

$label_dirs = ['../legacy/modules', '../legacy/include/language'];
$label_file_name_prefix = 'en_us';
$directories_used_labels = ['../api', '../vue/src', '../legacy'];
$directories_used_labels_exclude_dir = 'language';
$labels_prefixes = ['LBL_', 'ERR_', 'LNK_'];
$not_used_labels = [];
$used_labels_by_application = [];

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

$getUsedLabels = function () use (&$directories_used_labels, &$directories_used_labels_exclude_dir, &$labels_prefixes, &$used_labels_by_application)
{
    //read all files from $directories_used_labels and find text in the files by reqex, then add to $all_labels
    foreach ($directories_used_labels as $directories_used_label) {

        $files = array_filter(getFilesFromDirectory($directories_used_label),
            function ($file) use ($directories_used_labels_exclude_dir) {
                return strpos($file, $directories_used_labels_exclude_dir) === false;
            });

        foreach ($files as $file) {

            $content = file_get_contents($file);
            foreach ($labels_prefixes as $labels_prefix) {
                
                $pattern = '/\b' . $labels_prefix . '.*?\b/i';
                preg_match_all($pattern, $content, $matches);

                if (is_array($matches[0]) && !empty($matches[0])) {
                    foreach (array_unique($matches[0]) as $match) {
                        $used_labels_by_application[$match] = $match;
                    }
                }
            }
        }
    }
};

$findNotUsedLabels = function () use (&$label_dirs, &$label_file_name_prefix, &$used_labels_by_application, &$labels_prefixes, &$not_used_labels)
{
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
        $used_labels_by_application = array_map('strtoupper', $used_labels_by_application);

        foreach ($labels as $label_key => $label_value) {
            foreach ($labels_prefixes as $labels_prefix) {
                $pattern = '/\b' . $labels_prefix . '.*?\b/i';

                if (preg_match($pattern, $label_key)) {
                    if (in_array(strtoupper($label_key), $used_labels_by_application)) {
                        $used_labels[$label_file][] = $label_value;
                    } else {
                        $not_used_labels[$label_file][] = $label_key;
                    }
                }
            }
        }
    }
};

$createOutput = function () use (&$not_used_labels)
{
    if(!empty($not_used_labels))
    {
        foreach($not_used_labels as $key => $labels)
        {
            $output_string = $key . " => [";
            foreach($labels as $label) 
            {
                $output_string .= $label . ", ";
            }
            $output_string = rtrim($output_string, ', ');
            $output_string .= "]\n";
            echo $output_string;
        }
        
    }
};

$deleteNotUsedLabels = function () use (&$not_used_labels)
{
    foreach($not_used_labels as $key => $labels)
    {
        foreach($labels as $label)
        {
            $content = file_get_contents($key);
            $lines = explode("\n", $content);
            $exclude = [];
            
            foreach($lines as $line) 
            {
                if (strpos($line, $label) !== FALSE) {
                    if(strpos($line, '=> \'' . $label) == FALSE) {
                        continue;
                    }
                }
                $exclude[] = $line;
            }
            file_put_contents($key, implode("\n", $exclude));
        }
        
    }
};

function runScript($getUsedLabels, $findNotUsedLabels, $createOutput, $deleteNotUsedLabels, $delete_not_used_labels = false)
{
    if($delete_not_used_labels === true)
    {
        $getUsedLabels();
        $findNotUsedLabels();
        $createOutput();
        $deleteNotUsedLabels();
    } else {
        $getUsedLabels();
        $findNotUsedLabels();
        $createOutput();
    }
}

runScript($getUsedLabels, $findNotUsedLabels, $createOutput, $deleteNotUsedLabels);

