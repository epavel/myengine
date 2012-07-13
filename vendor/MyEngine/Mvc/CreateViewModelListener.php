<?php

namespace MyEngine\Mvc;

use MyEngine\Event\EventManager;
use MyEngine\Event\AggregateInterface;
use MyEngine\View\ViewModel;

class CreateViewModelListener implements AggregateInterface
{
    /**
     * Attach listeners
     *
     * @param  Events $events
     * @return void
     */
    public function attach(EventManager $eventManager)
    {
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'createViewModelFromArray'), -80);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'createViewModelFromNull'), -80);
    }

    /**
     * Inspect the result, and cast it to a ViewModel if an assoc array is detected
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function createViewModelFromArray(MvcEvent $e)
    {
        $model = new ViewModel($e->getResult());
        $e->setResult($model);
    }

    /**
     * Inspect the result, and cast it to a ViewModel if null is detected
     *
     * @param MvcEvent $e
     * @return void
    */
    public function createViewModelFromNull(MvcEvent $e)
    {
        if (null !== $e->getResult()) {
            return;
        }

        $model = new ViewModel;
        $e->setResult($model);
    }
}
