<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Transaction\Domain\Command;

use Broadway\CommandHandling\CommandHandler;
use OpenLoyalty\Component\Transaction\Domain\Transaction;
use OpenLoyalty\Component\Transaction\Domain\TransactionRepository;

/**
 * Class TransactionCommandHandler.
 */
class TransactionCommandHandler extends CommandHandler
{
    /**
     * @var TransactionRepository
     */
    protected $repository;

    /**
     * TransactionCommandHandler constructor.
     *
     * @param TransactionRepository $repository
     */
    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handleRegisterTransaction(RegisterTransaction $command)
    {
        $transaction = Transaction::createTransaction(
            $command->getTransactionId(),
            $command->getTransactionData(),
            $command->getCustomerData(),
            $command->getItems(),
            $command->getPosId(),
            $command->getExcludedDeliverySKUs(),
            $command->getExcludedLevelSKUs(),
            $command->getExcludedCategories(),
            $command->getRevisedDocument()
        );

        $this->repository->save($transaction);
    }

    public function handleAssignCustomerToTransaction(AssignCustomerToTransaction $command)
    {
        /** @var Transaction $transaction */
        $transaction = $this->repository->load($command->getTransactionId()->__toString());
        $transaction->assignCustomerToTransaction($command->getCustomerId());
        $this->repository->save($transaction);
    }
}
