<?php declare(strict_types=1);
namespace Rakitan\Lib\Template;

use League\Plates\Extension\ExtensionInterface;
use League\Plates\Engine;

/*
 * plates extension
 *
 * @author arifk
 */
class MyPlatesExtension implements ExtensionInterface
{
    /**
     *
     * @var string
     */
    protected $baseDir;

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('asset', [$this, 'asset']);
        $engine->registerFunction('url', [$this, 'url']);
        $engine->registerFunction('formatDate', [$this, 'formatDate']);
        $engine->registerFunction('formatDatetime', [$this, 'formatDatetime']);
        $engine->registerFunction('formatRupiah', [$this, 'formatRupiah']);
        $engine->registerFunction('active', [$this, 'menuActive']);
        $engine->registerFunction('in', [$this, 'menuIn']);
        $engine->registerFunction('collapsed', [$this, 'menuCollapsed']);
    }

    public function asset($path = null)
    {
        return rtrim($this->baseDir, '/') . '/asset/' . $path;
    }

    public function url($path = null)
    {
        return rtrim($this->baseDir, '/') . $path;
    }

    /**
     * mengubah format tgl inggris ke Indonesia
     * @param string $strdate 2015-12-31
     * @return string tgl dlm format Indonesia: 31-12-2015
     */
    public function formatDate($strdate='')
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

    /**
     * sama dengan formatDate dengan tambahan waktu
     * @param string $strdate 2015-12-31
     * @return string tgl dlm format Indonesia: 31-12-2015
     */
    public function formatDatetime($strdate='')
    {
        if (!$strdate) {
            return '';
        }
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $strdate);
        if ($date) {
            return $date->format('d-m-Y H:i:s');
        }
        return '';
    }
    /**
     * mengubah format angka inggris ke Indonesia
     * @param mixed $number 1,200,000
     * @return string 1.200.000
     */
    public function formatRupiah($number)
    {
        return number_format($number, 0, ',', '.');
    }

    public function menuActive($menuAktif, $menuCek)
    {
        return $menuAktif == $menuCek ? ' active' : '';
    }

    public function menuIn($menuAktif, $menuCek)
    {
        return $menuAktif == $menuCek ? ' in' : '';
    }

    public function menuCollapsed($menuAktif, $menuCek)
    {
        return $menuAktif == $menuCek ? '' : ' collapsed';
    }

}