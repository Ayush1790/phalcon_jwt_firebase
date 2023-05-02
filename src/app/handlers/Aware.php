<?php
// custom component to call the listener
namespace handler\Aware;

use Phalcon\Di\Injectable;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;


class Aware extends Injectable implements EventsAwareInterface
{
    protected $eventsManager;

    public function getEventsManager(): ManagerInterface
    {
        return $this->eventsManager;
    }

    public function setEventsManager(ManagerInterface $eventsManager): void
    {
        $this->eventsManager = $eventsManager;
    }


    public function process()
    {
        $this->eventsManager->fire('product:beforeAddProduct', $this);
        $this->eventsManager->fire('order:beforeOrderProduct', $this);
        $this->eventsManager->fire('access:beforeAccessChange', $this);
    }
}
