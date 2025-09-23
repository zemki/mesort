<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Format bytes into human readable format
     *
     * @param int $size Size in bytes
     * @param int $precision Number of decimal places
     * @return string Formatted size with unit
     */
    public static function formatBytes(int $size, int $precision = 2): string
    {
        if ($size === 0) {
            return '0 B';
        }

        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        $power = floor($base);
        $formattedSize = round(pow(1024, $base - $power), $precision);

        return $formattedSize . ' ' . $suffixes[$power];
    }
}