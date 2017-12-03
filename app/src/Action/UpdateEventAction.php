<?php

namespace PHPMinds\Action;

use PHPMinds\Config\EventsConfig;
use PHPMinds\Factory\EventFactory;
use PHPMinds\Model\Auth;
use PHPMinds\Model\Event\EventManager;
use PHPMinds\Model\Event\EventModel;
use PHPMinds\Model\Form\CreateEventForm;
use PHPMinds\Validator\EventValidator;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use PHPMinds\Service\EventsService;
use Slim\Flash\Messages;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;

final class UpdateEventAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventsService
     */
    private $eventService;

    /**
     * @var Guard
     */
    private $csrf;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var EventsConfig
     */
    private $eventsConfig;

    /**
     * @var Messages
     */
    private $flash;


    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService,
                                Guard $csrf, EventsConfig $eventsConfig,
                                Auth $auth, Messages $flash)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
        $this->csrf = $csrf;
        $this->eventsConfig = $eventsConfig;
        $this->auth = $auth;
        $this->flash = $flash;
    }

    public function dispatch(Request $request, Response $response, $args)
    {

        $meetupID = $request->getAttribute('meetup_id', null);
        /** @var EventModel $eventInfo */
        $eventInfo = $this->eventService->getInfoByMeetupID($meetupID);

        if (!$eventInfo->isRegistered() && !is_null($meetupID)) {
            $this->flash->addMessage('event', 'No event found for meetupID provided. Please create a new event.');
            return $response->withStatus(302)->withHeader('Location', '/create-event');
        }

        $this->view->render($response, 'admin/update-event.twig', [
            'event' => $eventInfo,
        ]);



        if ($request->isPost()) {
            if ($eventInfo->isRegistered()) {

                $this->eventService->createMainEvents($eventInfo, $this->auth->getUserId(), $meetupID);

            }
        }

        return $response;

    }


    protected function getCsrfValues(Request $request)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return [
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $name,
            'value' => $value
        ];
    }
}
