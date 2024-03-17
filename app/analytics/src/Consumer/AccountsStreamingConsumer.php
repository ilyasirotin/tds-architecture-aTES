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

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function process(Message $message, Context $context): string
    {
        return self::ACK;
    }

    public static function getSubscribedTopics(): array
    {
        return ['accounts_stream'];
    }
}
