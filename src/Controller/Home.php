<?php declare(strict_types=1);
namespace Rakitan\Controller;

class Home extends Base
{
    public function index()
    {
        if ($this->auth->isAuthenticated()) {
            return $this->forward('Home::indexAdmin');
        }
        return $this->view->render('home/index');
    }

    public function indexAdmin()
    {
        return $this->view->render('home/indexAdmin');
    }

    public function error()
    {
        return 'home error';
    }
}