<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\BillingCycle;
use App\Entity\Transaction;
use App\Repository\BillingCycleRepository;
use App\Repository\TaskRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

final class TransactionApplyService implements TransactionApplyUseCase
{
    private BillingCycleRepository $billingCycles;
    private TransactionRepository $transactions;
    private EntityManagerInterface $em; // TODO: Try to opt out of this dependency
    private UserRepository $users;
    private TaskRepository $tasks;

    public function __construct(
        BillingCycleRepository $billingCycles,
        TransactionRepository $transactions,
        UserRepository $users,
        TaskRepository $tasks,
        EntityManagerInterface $em
    )
    {
        $this->billingCycles = $billingCycles;
        $this->transactions = $transactions;
        $this->users = $users;
        $this->em = $em;
        $this->tasks = $tasks;
    }

    public function execute(TransactionApply $command): Transaction
    {
        $task = $this->tasks->findOneBy(['publicId' => $command->task()->getPublicId()]);

        if (!isset($task)) {
            throw new Exception(sprintf('Task can not be find by the provided id: %s', $task->getPublicId()));
        }

        return $this->em->wrapInTransaction(function () use ($task, $command) {
            $assignee = $task->getAssignee();
            $billingCycle = $this->billingCycles->findActiveForOwner($assignee);

            if (!isset($billingCycle)) {
                $billingCycle = new BillingCycle();
                $billingCycle->setOwner($assignee);
                $this->billingCycles->add($billingCycle);
            }

            $transaction = new Transaction();
            $transaction->setAccount($assignee->getAccount());
            $transaction->setTask($task);
            $transaction->setBillingCycle($billingCycle);
            $transaction->setType($command->type());

            // TODO: Think about refactoring
            if ($command->type() === Transaction::WITHDRAW) {
                $cost = (float) $task->getCost()->getCredit();
                $balance = (float) $assignee->getAccount()->getBalance() - $cost;
                $transaction->setCredit(sprintf("%f", $cost));
            } else {
                $cost = (float) $task->getCost()->getDebit();
                $balance = (float) $assignee->getAccount()->getBalance() + $cost;
                $transaction->setDebit(sprintf("%f", $cost));
            }

            $assignee->getAccount()->setBalance(sprintf("%f", $balance));
            $this->users->update($assignee);

            return $this->transactions->add($transaction);
        });
    }
}
