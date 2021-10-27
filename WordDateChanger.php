<?php

require "vendor/autoload.php";

try {

    $files = [];
    if ($handle = opendir('.')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && endsWith($entry, ".docx")) {
                $files[] = $entry;
            }
        }

        closedir($handle);
    }

    foreach ($files as $filename) {
        $reader = \PhpOffice\PhpWord\IOFactory::createReader("Word2007");

        $phpWord = $reader->load($filename);

        $properties = $phpWord->getDocInfo();

        $day = (int) date('d', $properties->getModified());
        $month = (int) date('m', $properties->getModified());
        $year = (int) date('Y', $properties->getModified());

        $hour = (int) date('h', $properties->getModified());
        $minute = (int) date('i', $properties->getModified());

        $properties->setCreated(mktime($hour, $minute-30, 0, $month, 14, $year));

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save("Уст.конф. ".$filename);
    }

} catch (Exception $e) {
    var_dump($e);
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if(!$length) {
        return true;
    }
    return substr($haystack, -$length) === $needle;
}