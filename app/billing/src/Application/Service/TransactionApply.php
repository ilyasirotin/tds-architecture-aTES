<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;

final class TransactionApply
{
    private string $type;

    private Task $task;

    public function __construct(Task $task, string $type)
    {
        $this->task = $task;
        $this->type = $type;
    }

    public function task(): Task
    {
        return $this->task;
    }

    public function type(): string
    {
        return $this->type;
    }
}
