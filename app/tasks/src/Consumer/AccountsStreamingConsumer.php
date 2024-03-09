<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Application\Service\HandleUserData;
use App\Application\Service\HandleUserDataUseCase;
use App\Entity\User;
use Enqueue\Client\TopicSubscriberInterface;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Symfony\Component\Serializer\SerializerInterface;

final class AccountsStreamingConsumer implements Processor, TopicSubscriberInterface
{
    private SerializerInterface $serializer;
    private HandleUserDataUseCase $service;

    public function __construct(SerializerInterface $serializer, HandleUserDataUseCase $service)
    {
        $this->serializer = $serializer;
        $this->service = $service;
    }

    public function process(Message $message, Context $context): void
    {
        $payload = $message->getBody();
        $user = $this->serializer->deserialize($payload, User::class, 'json');

        $this->service->execute(
            new HandleUserData($user)
        );
    }

    public static function getSubscribedTopics(): array
    {
        return ['account_streaming'];
    }
}
