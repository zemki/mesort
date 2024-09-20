<?php

// Code within app\Helpers\Helper.php

namespace App\Helpers;

use Exception;
use File;

class Helper
{
    /**
     * Generate random string with a given set of chars.
     *
     * @param  string  $keyspace
     * @return string
     *
     * @throws Exception
     */
    public static function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$()=-*.,')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; $i++) {
            $pieces[] = $keyspace[mt_rand(0, $max)];
        }

        return implode('', $pieces);
    }

    /**
     * return extension of base64 image.
     *
     * @param  string  $uri  base64 image uri
     * @return string image extension
     */
    public static function extension($uri)
    {
        $img = explode(',', $uri);
        $ini = substr($img[0], 11);
        $type = explode(';', $ini);

        return $type[0];
    }

    /**
     * get the preset images for the tokens.
     */
    public static function getPresetImages(): array
    {
        $filesInFolder = File::files(storage_path('app/presets_tokens'));
        $arrayOfFiles = [];
        $i = 0;
        foreach ($filesInFolder as $path) {
            $arrayOfFiles[$i]['basename'] = pathinfo($path)['basename'];
            $arrayOfFiles[$i]['dirname'] = url('/images/presets_tokens') . '/' . pathinfo($path)['basename'];
            $i++;
        }

        return $arrayOfFiles;
    }

    /**
     * get the images for the classifiers.
     */
    public static function getClassifiers(): array
    {
        $dir = storage_path('app/classifiers');
        $classifiers = array_diff(scandir($dir), ['..', '.']);

        $arrayOfFiles = $arrayOfClassifiersName = [];
        $classifierIndex = 1;
        $arrayOfClassifiersName[0]['name'] = 'none';
        $i = 0;
        foreach ($classifiers as $classifier) {
            $filesInFolder = File::files($dir . '/' . $classifier);

            foreach ($filesInFolder as $path) {
                $arrayOfFiles[$i]['name'] = $classifier;
                $arrayOfFiles[$i]['basename'] = pathinfo($path)['basename'];
                $arrayOfFiles[$i]['dirname'] = url('/images/classifiers') . '/' . $classifier . '/' . pathinfo($path)['basename'];
                $i++;
            }
            $arrayOfClassifiersName[$classifierIndex]['name'] = $classifier;
            $classifierIndex++;
        }

        return [$arrayOfFiles, $arrayOfClassifiersName];
    }

    /**
     * @return array
     */
    public static function super_unique($array, $key)
    {
        $temp_array = [];
        foreach ($array as &$value) {
            if (! isset($temp_array[$value[$key]])) {
                $temp_array[$value[$key]] = &$value;
            }
        }
        $array = array_values($temp_array);

        return $array;
    }

    /**
     * @return bool|string
     */
    public static function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini === 0 || $ini === false) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        if (strpos($string, $end, $ini) === false) {
            $len = strlen($string) - 1;
        }

        return substr($string, $ini, $len);
    }
}
