<?php
/**
 *
 * Common helper
 *
 * @Litkovskiy 2010
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Common
{
    public static function getDateTime($format = null, $time = 'now')
    {
        $dateTime = new DateTime($time);

        if ($format) {
            return $dateTime->format($format);
        }

        return $dateTime;
    }

    /**
     * Call parseDate() and return array date in format (date2.date1.date0)
     *
     * @param string $strDateTime
     *
     * @return array
     */
    public static function parseDateTimeAndGetDate($strDateTime)
    {
        $date = $time = null;
        list($date, $time) = explode(' ', $strDateTime);
        return self::getDate($date);
//        return self::getCutDate($date);
    }

	/**
     * Call parseDate() and return array date in format (date2.date1.date0)
     *
     * @param string $strDateTime
     *
     * @return array
     */
    public static function getDate($strDateTime)
    {
        $date = self::parseDate($strDateTime);
        return $date[2] . '.' . $date[1] . '.' . $date[0];
    }

    /**
     * Call parseDate() and return array date in format (date2.date1.date0)
     *
     * @param string $strDateTime
     *
     * @return array
     */
    public static function getCutDate($strDateTime)
    {
        $date = self::getDate($strDateTime);
        return substr($date, 0,-2);
    }

    /**
     * Parse Date string
     *
     * @param string $strDateTime
     *
     * @return array with date items or with 0
     */
    public static function parseDate($strDateTime)
    {
        $matches = null;
        if (preg_match('!(\d{4})-(\d{2})-(\d{2})!', $strDateTime, $matches)) {
            return array($matches[1], $matches[2], $matches[3]);
        } else {
            return array(0, 0, 0);
        }
    }



    /**
     * Parse Time string
     *
     * @param string $strDateTime
     *
     * @return array  with time items or with 0
     */
    public static function parseTime($strDateTime)
    {
        $matches = null;
        if (preg_match('!(\d{2})\:(\d{2})\:(\d{2})!', $strDateTime, $matches)) {
            return array($matches[1], $matches[2], $matches[3]);
        } else {
            return array(0, 0, 0);
        }
    }

    /**
     * Assert True
     *
     * @param string $val
     * @param string $message
     * @return bool|string
     * @throws Exception
     */
    public static function assertTrue($val, $message)
    {
        if (!$val) {
            throw new Exception($message);
        }
    }

    /**
     * Assert False
     *
     * @param string $val
     * @param string $message
     * @return bool|string
     * @throws Exception
     */
    public static function assertFalse($val, $message)
    {
        if ($val) {
            throw new Exception($message);
        }
    }
    
    
    public static function cutString ($text, $length)
    {
        $sep = " ";
        $words = explode($sep, $text);
        if ( count($words) > $length ){
            $text = strip_tags(implode($sep, array_slice($words, 0, $length))."...");
        }
        
        return $text;
    }
    
    
    /**
     * Put log message to file
     *
     * @param string $msg
     */
    public static function debugLog($msg)
    {
        if ( !(is_int($msg) || is_string($msg) || is_float($msg)) ) {
            $msg = var_export($msg, true);
        }

        $date = new DateTime();
        $dateString = $date->format('Y-m-d H:i:s');
        $debugLogFilename = 'debug_log_'.$date->format('Y-m-d').'.txt';
        error_log("$dateString\t$msg\n", 3, __DIR__ . '/../logs/'.$debugLogFilename);
    }
    
    
    public static function debugLogProd($msg)
    {
        if ( !(is_int($msg) || is_string($msg) || is_float($msg)) ) {
            $msg = var_export($msg, true);
        }

        $date = new DateTime();
        $dateString = $date->format('Y-m-d H:i:s');

        $debugLogFilename = 'debug_log_'.$date->format('Y-m-d').'.txt';

        error_log("$dateString\t$msg\n", 3, $_SERVER["DOCUMENT_ROOT"].'/application/logs/'.$debugLogFilename);
    }
}