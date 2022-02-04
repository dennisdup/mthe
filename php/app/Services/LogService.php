<?php

namespace App\Services;
/**
 * Class LogService
 *
 * @package App\Services
 */
class LogService
{
    /**
     * @param $filename
     * @param $exception
     */
    public static function saveToLog($filename, $exception, $anotherInfo = null)
    {
        $message = "\n" . $exception->getMessage() . " - " . $anotherInfo . "\n";
        file_put_contents($filename, $message, FILE_APPEND);
    }
}
