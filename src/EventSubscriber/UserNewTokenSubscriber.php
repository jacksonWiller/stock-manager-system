<?php


namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Uloc\ApiBundle\Event\UlocApiBundleEvents;
use Uloc\ApiBundle\Event\UserNewTokenEvent;

class UserNewTokenSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            UlocApiBundleEvents::EVENT_USER_NEW_TOKEN => 'onTokenGet'
        ];
    }

    public function onTokenGet(UserNewTokenEvent $event){
        $data = $event->getData();
        // $data['response']['hello'] = 'world';
        // $event->setData($data);
    }

}