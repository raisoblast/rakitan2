<?php declare(strict_types=1);
namespace Rakitan\Lib;

class CommonUtil
{
    /**
     * mengubah tgl format d-m-Y ke Y-m-d
     */
    public static function formatDateYMD($strdate)
    {
        if (!$strdate || $strdate == '00-00-0000') {
            return '';
        }
        $date = \DateTime::createFromFormat('d-m-Y', $strdate);
        if ($date === false) {
            $date = \DateTime::createFromFormat('d-m-Y H:i:s', $strdate);
        }
        if ($date) {
            return $date->format('Y-m-d');
        }
        return '';
    }

    /**
     * mengubah tgl format Y-m-d ke d-m-Y
     */
    public static function formatDateDMY($strdate)
    {
        if (!$strdate || $strdate == '0000-00-00') {
            return '';
        }
        $date = \DateTime::createFromFormat('Y-m-d', $strdate);
        if ($date === false) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $strdate);
        }
        if ($date) {
            return $date->format('d-m-Y');
        }
        return '';
    }

    /*
     * mengubah elemen $dateElement pada array $data ke format tgl Y-m-d
     * @return boolean
     */
    public static function arrayToDateYMD(array &$data, array $dateElement)
    {
        return array_walk($data, ['self', 'walkDateYMD'], $dateElement);
    }

    /*
     * callback yang dipanggil oleh arrayToDateYMD
     */
    public static function walkDateYMD(&$item, $key, $dateElement)
    {
        if (in_array($key, $dateElement)) {
            $item = self::formatDateYMD($item);
            echo $item."\n";
        }
    }
}