<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class EventSubscriber implements EventSubscriberInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Get errors form and format them in json
     * @param GetResponseForExceptionEvent $event
     */
    public function processException(GetResponseForExceptionEvent $event) : void
    {
        if(method_exists($event->getException(), 'getStatusCode')) {
            $code = $event->getException()->getStatusCode();
        } else {
            $code = $event->getException()->getCode() === 0 ? 500 : $event->getException()->getCode();
        }
        $result = [
            'code' => $code,
            'message' => $event->getException()->getMessage()
        ];

        $body = $this->serializer->serialize($result, 'json');

        $event->setResponse(new Response($body, $result['code'], ['Content-Type' => 'application/json']));
    }

    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::EXCEPTION => ['processException', 255]
        ];
    }

}