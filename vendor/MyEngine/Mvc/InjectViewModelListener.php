<?php

namespace MyEngine\Mvc;

use MyEngine\Event\EventManager;
use MyEngine\Event\AggregateInterface;
use MyEngine\View\ModelInterface;

class InjectViewModelListener implements AggregateInterface
{
    /**
     * Attach listeners
     *
     * @param  Events $events
     * @return void
     */
    public function attach(EventManager $eventManager)
    {
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'injectViewModel'), -100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'injectViewModel'), -100);
    }

    /**
     * Insert the view model into the event
     *
     * Inspects the MVC result; if it is a view model, it then either (a) adds
     * it as a child to the default, composed view model, or (b) replaces it,
     * if the result  is marked as terminable.
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function injectViewModel(MvcEvent $e)
    {
        $result = $e->getResult();
        if (!$result instanceof ModelInterface) {
            return;
        }

        $model = $e->getViewModel();

        if ($result->terminate()) {
            $e->setViewModel($result);
            return;
        }

        $model->addChild($result);
    }
}
