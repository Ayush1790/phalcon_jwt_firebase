<?php

use Phalcon\Mvc\Controller;
use handler\Aware\Aware;
use handler\Listener\Listener;
use Phalcon\Events\Manager as EventsManager;

class ProductController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }

    public function addAction()
    {
        $eventsManager = new EventsManager();
        $componant = new Aware();

        $componant->setEventsManager($eventsManager);
        $eventsManager->attach(
            'product',
            new Listener()
        );
        $componant->process();
        $data = [
            'name' => $this->escaper->escapeHtml($this->request->getPost('name')),
            'desc' => $this->escaper->escapeHtml($this->request->getPost('desc')),
            'tags' => $this->escaper->escapeHtml($this->request->getPost('tags')),
            'price' => $this->escaper->escapeHtml($this->request->getPost('price')),
            'stock' => $this->escaper->escapeHtml($this->request->getPost('stock'))
        ];
        $products = new Products();
        $products->assign(
            $data,
            [
                'name', 'desc', 'tags', 'price', 'stock'
            ]
        );
        $res = $products->save();
        if ($res) {
            $this->response->redirect();
        } else {
            echo "error";
        }
    }
    public function viewAction()
    {
        $product = $this->db->fetchAll(
            "SELECT * FROM products",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $product;
    }
}
