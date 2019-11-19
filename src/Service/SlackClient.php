<?php

namespace App\Service;

use Nexy\Slack\Client;
use App\Helper\LoggerTrait;

class SlackClient
{
    use LoggerTrait;
    private $slack;
    
    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }

    public function sendMessage(string $from, string $message)
    {
        $this->logInfo('Beaming a message to slack!', [
            'message' => $message
        ]);

        $slackMessage = $this->slack->createMessage()
            ->from($from)
            ->withIcon(':heart:')
            ->setText($message)
        ;

        $this->slack->sendMessage($slackMessage);
    }

}