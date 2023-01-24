<?php

namespace TransactionsSystem\Tests\Service;

use TransactionsSystem\Entity\AccountInterface;
use TransactionsSystem\Entity\Exception\IncorrectAmountException;
use TransactionsSystem\Entity\Exception\NotFoundException;
use TransactionsSystem\Entity\Exception\TransactionException;
use TransactionsSystem\Entity\Repository\AccountRepositoryInterface;
use TransactionsSystem\Entity\ValueObject\Id;
use TransactionsSystem\Service\TransactionCommand;
use TransactionsSystem\Service\TransactionHandler;
use PHPUnit\Framework\TestCase;
use TransactionsSystem\Service\TransferCommand;

class TransactionHandlerTest extends TestCase
{
    private TransactionHandler $transactionHandler;

    private AccountRepositoryInterface $accountRepository;

    public function setUp(): void
    {
        $this->accountRepository = $this->createMock(AccountRepositoryInterface::class);

        $this->transactionHandler = new TransactionHandler(
            $this->accountRepository
        );
    }

    /**
     * Need to repeat for other methods of applying transactions.
     */
    public function testReplenishment(): void
    {
        $id      = Id::generate();
        $command = new TransactionCommand($id->value, 20, 'Test');

        $account = $this->createMock(AccountInterface::class);
        $account->expects($this->once())
            ->method('applyTransaction');

        $this->accountRepository
            ->expects($this->once())
            ->method('find')
            ->with($id->value)
            ->willReturn($account);

        $this->transactionHandler->replenishment($command);
    }

    public function testReplenishmentIncorrectAmount(): void
    {
        $command = new TransactionCommand('Test Id', -20, 'test');

        $this->expectException(TransactionException::class);

        $this->transactionHandler->replenishment($command);
    }

    public function testWithdrawIncorrectAmount(): void
    {
        $command = new TransactionCommand('Test Id', 20, 'test');

        $this->expectException(IncorrectAmountException::class);

        $this->transactionHandler->withdraw($command);
    }

    public function testTransferIncorrectAmount(): void
    {
        $command = new TransferCommand('Test Id', -20, 'test', 'test');

        $this->expectException(IncorrectAmountException::class);

        $this->transactionHandler->transfer($command);
    }

    public function testNotFound(): void
    {
        $command = new TransactionCommand('Test Id', 20, 'test');

        $this->accountRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $this->expectException(NotFoundException::class);

        $this->transactionHandler->replenishment($command);
    }
}
