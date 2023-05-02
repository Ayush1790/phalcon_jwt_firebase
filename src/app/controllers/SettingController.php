<?php

use Phalcon\Mvc\Controller;

class SettingController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }
    public function addAction()
    {
        $data = [
            'title' => $this->escaper->escapeHtml($this->request->getPost('title')),
            'price' => $this->escaper->escapeHtml($this->request->getPost('price')),
            'stock' => $this->escaper->escapeHtml($this->request->getPost('stock')),
            'zipcode' => $this->escaper->escapeHtml($this->request->getPost('zipcode'))
        ];
        $product = $this->db->fetchAll(
            "SELECT * FROM settings",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        if (empty($product)) {
            $setting = new Settings();
            $setting->assign(
                $data,
                [
                    'title', 'price', 'stock', 'zipcode',
                ]
            );
            $setting->save();
        } else {
            $connection = $this->container->get('db');
            $connection->query("update `settings` set `title`='$data[title]' ,`price`='$data[price]' , `stock`='$data[stock]' , `zipcode`='$data[zipcode]' where `id`='1' ");
        }
        $this->response->redirect("setting");
    }
}
