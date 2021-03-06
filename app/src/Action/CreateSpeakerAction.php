<?php

namespace PHPMinds\Action;

use PHPMinds\Model\Email;
use PHPMinds\Model\Twitter;
use PHPMinds\Repository\SpeakersRepository;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use PHPMinds\Model\Event\Entity\Speaker;

final class CreateSpeakerAction
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
     * @var SpeakersRepository
     */
    private $speakersRepository;

    public function __construct(Twig $view, LoggerInterface $logger, SpeakersRepository $speakersRepository)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->speakersRepository = $speakersRepository;
    }

    public function dispatch(Request $request, Response $response, $args)
    {
        if($request->isPost()) {
            $speaker = new Speaker(
                $request->getParam('first_name'),
                $request->getParam('last_name'),
                new Email($request->getParam('email')),
                new Twitter($request->getParam('twitter'))
            );

            $msg = [];
            try {
                $this->speakersRepository->save($speaker);
                $msg['id'] = $speaker->id;
            } catch (\Exception $e) {
                $this->logger->debug($e->getMessage());
                return $response->withStatus(406)
                    ->withHeader('Content-Type', 'application/json')
                    ->write(json_encode(['error' => $e->getMessage()]));
            }

            return $response->withStatus(201)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode($msg));

        }
    }
}