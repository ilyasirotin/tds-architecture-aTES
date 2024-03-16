<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Application\Service\TransactionApply;
use App\Application\Service\TransactionApplyUseCase;
use App\Entity\Task;
use App\Entity\Transaction;
use Enqueue\Client\TopicSubscriberInterface;
use Exception;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Symfony\Component\Serializer\SerializerInterface;

final class TaskLifecycleConsumer implements Processor, TopicSubscriberInterface
{
    private const EVENT_CREATED = 'task_created';
    private const EVENT_COMPLETED = 'task_completed';
    private const EVENT_SHUFFLED = 'task_shuffled';
    private SerializerInterface $serializer;
    private TransactionApplyUseCase $service;

    public function __construct(
        SerializerInterface $serializer,
        TransactionApplyUseCase $service
    )
    {
        $this->serializer = $serializer;
        $this->service = $service;
    }

    public function process(Message $message, Context $context): string
    {
        $event = $message->getProperty('enqueue.topic');
        $payload = $this->serializer->deserialize($message->getBody(), Task::class, 'json');

        switch ($event) {
            case self::EVENT_CREATED:
            case self::EVENT_SHUFFLED:
                $this->service->execute(
                    new TransactionApply($payload, Transaction::WITHDRAW)
                );
                break;
            case self::EVENT_COMPLETED:
                $this->service->execute(
                    new TransactionApply($payload, Transaction::ENROLL)
                );
                break;
            default:
                throw new Exception(sprintf("Unknown event type: %s", $event));
        }

        return self::ACK;
    }

    public static function getSubscribedTopics(): array
    {
        return [
            self::EVENT_CREATED,
            self::EVENT_COMPLETED,
            self::EVENT_SHUFFLED,
        ];
    }
}
