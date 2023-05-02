<?php

use Phalcon\Mvc\Controller;
use handler\Aware\Aware;
use handler\Listener\Listener;
use Phalcon\Events\Manager as EventsManager;

session_start();
class AccessController extends Controller
{
    public function indexAction()
    {
        $eventsManager = new EventsManager();
        $componant = new Aware();

        $componant->setEventsManager($eventsManager);
        $eventsManager->attach(
            'access',
            new Listener()
        );
        $componant->process();
    }

    public function changeAction()
    {
        $str = "";
        foreach ($this->session->get('AccessControl') as $key => $value) {
            if ($key == $this->request->getPost('name')) {
                foreach ($value as $key1 => $value1) {
                    $str .= "<option>$value1</option>";
                }
            }
        }
        return $str;
    }
    public function saveAction()
    {
        $role = $this->request->getPost('role');
        $controller = $this->request->getPost('controller');
        $action = $this->request->getPost('action');
        if ($action == "") {
            $action = 'index';
        }
        if (!$this->session->has('role')) {
            $_SESSION['role'] = array('guest', 'user', 'admin');
        }
        if ($this->session->has($role)) {
            if (array_key_exists($controller, $_SESSION[$role]) && $_SESSION[$role][$controller] == $action) {
                //already exist
            } else {
                $_SESSION[$role] = array_merge($_SESSION[$role], [$controller => $action]);
            }
        } else {
            $this->session->set($role, array($controller => $action));
        }
        $this->response->redirect('access');
    }
}
