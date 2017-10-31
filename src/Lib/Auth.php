<?php declare(strict_types=1);
namespace Rakitan\Lib;

use Aura\Session\Session;
use Noodlehaus\Config;

/**
 * Manajemen autentikasi dan otorisasi
 * @author arifk
 *
 */
class Auth
{
    private $session;
    public $username;
    public $nama;
    public $group;

    public function __construct(Session $session, Config $config)
    {
        $this->session = $session;
        $segment = $session->getSegment($config->get('sessionName'));
        if ($segment->get('username')) {
            $this->username = $segment->get('username');
            $this->nama = $segment->get('nama');
            $this->group = strtolower($segment->get('group'));
        }
    }

    public function isAuthenticated()
    {
        return $this->username != null;
    }

    public function getNamaDepan()
    {
        list($depan) = explode(' ', $this->nama);
        return $depan;
    }

    public function isAdmin()
    {
        return $this->group == 'admin';
    }

    public function getCsrfToken()
    {
        return $this->session->getCsrfToken();
    }

    public function getCsrfField()
    {
        return '<input name="__csrf_value" value="'
            . $this->getCsrfToken()->getValue()
            . '" type="hidden" />';
    }
}