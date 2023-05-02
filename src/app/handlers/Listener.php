<?php
// listener class for event handling
namespace handler\Listener;

use Exception;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Application;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Listener extends Injectable
{
    public function beforeAddProduct()
    {
        $res = $this->db->fetchAll(
            "SELECT * FROM settings",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );

        if ($res[0]['title'] == 'tag1') {
            $_POST['name'] = $_POST['name'] . "_" . $_POST['tags'];
        }
        if ($_POST['price'] == '' || $_POST['price'] == 0) {
            $_POST['price'] = $res[0]['price'];
        }
        if ($_POST['stock'] == '' || $_POST['stock'] == 0) {
            $_POST['stock'] = $res[0]['stock'];
        }
    }

    public function beforeOrderProduct()
    {
        $res = $this->db->fetchAll(
            "SELECT zipcode FROM settings",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        if ($_POST['zipcode'] == '') {
            $_POST['zipcode'] = $res[0]['zipcode'];
        }
    }

    public function beforeAccessChange()
    {
        $controllers = [];
        $c = 1;
        foreach (glob(APP_PATH . '/controllers/*Controller.php') as $controller) {
            $className = basename($controller, '.php');
            if ($c == 1) {
                $c++;
                continue;
            }
            $controllers[$className] = [];
            $methods = (new \ReflectionClass($className))->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if (\Phalcon\Text::endsWith($method->name, 'Action')) {
                    $controllers[$className][] = strtolower($method->name);
                }
            }
        }
        $this->session->set('AccessControl', $controllers);
    }

    public function beforeHandleRequest(Event $event, Application $app, Dispatcher $dis)
    {
        $aclFile = APP_PATH.'/security/acl.cache';
        if (true !== is_file($aclFile)) {

            $acl = new Memory();
            /**
             * Add the roles
             */
            if ($this->session->has('role')) {
                foreach ($this->session->get('role') as $key => $value) {
                    $acl->addRole($value);
                }
            }
            //add components
            if ($this->session->has('AccessControl')) {
                foreach ($this->session->get('AccessControl') as $key => $value) {
                    foreach ($value as $index => $action) {
                        $acl->addComponent(
                            strtolower(substr($key, 0, -10)),
                            [
                                strtolower(substr($action, 0, -6))
                            ]
                        );
                    }
                }
            }
            $acl->addComponent(
                'access',
                [
                    'index',
                    'change',
                    'save'
                ]
            );

            if ($this->session->has('role')) {
                foreach ($this->session->get('role') as $key => $value) {
                    if ($this->session->has($value)) {
                        foreach ($this->session->get($value) as $controller => $action) {
                            $acl->allow($value, strtolower(substr($controller, 0, -10)), strtolower(substr($action, 0, -6)));
                        }
                    }
                }
            }
            $acl->allow('admin', '*', '*');
            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        } else {
            $acl = unserialize(
                file_get_contents($aclFile)
            );
        }
        $controller = "index";
        $action = "index";
        if (!empty($dis->getControllerName())) {
            $controller = $dis->getControllerName();
        }
        if (!empty($dis->getActionName())) {
            $action = $dis->getActionName();
        }
        if (!empty($app->request->get('bearer'))) {
            $role = $app->request->get('bearer');
        }
        if ($role) {
            try {
                $key = 'example_key';
                $decoded = JWT::decode($role, new Key($key, 'HS256'));
                if (true === $acl->isAllowed($decoded->role, $controller, $action)) {
                    echo "permission granted";
                } else {
                    echo "Access denied";
                    die;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                die;
            }
        } else {
            echo "token not received ";
            die;
        }
        
    }
}
