<?php

declare(strict_types=1);

namespace TransactionsSystem\Tests\Entity;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use TransactionsSystem\Entity\Account;
use TransactionsSystem\Entity\Exception\InsufficientFundsException;
use TransactionsSystem\Entity\Exception\TransactionException;
use TransactionsSystem\Entity\Transaction;
use TransactionsSystem\Entity\TransactionType;
use TransactionsSystem\Entity\ValueObject\Id;

class AccountTest extends TestCase
{
    private Account $account;

    public function setUp(): void
    {
        $this->account = new Account(
            Id::generate(),
            new \DateTimeImmutable(),
            0
        );
    }

    public function testApplyTransaction(): void
    {
        $replenishment = new Transaction(
            Id::generate(),
            20,
            'Test',
            TransactionType::Deposit,
            $this->account,
        );

        $initialBalance = $this->account->getBalance();

        $this->account->applyTransaction($replenishment);

        Assert::assertEquals(($initialBalance + $replenishment->getAmount()), $this->account->getBalance());
        Assert::assertContains($replenishment, $this->account->getTransactions());
    }

    public function testInsufficientFunds(): void
    {
        $withdrawal = new Transaction(
            Id::generate(),
            -40,
            'Test',
            TransactionType::Withdrawal,
            $this->account,
        );

        $this->expectException(InsufficientFundsException::class);

        $this->account->applyTransaction($withdrawal);
    }

    public function testAnotherAccountBelong(): void
    {
        $transaction = new Transaction(
            Id::generate(),
            40,
            'Test',
            TransactionType::Deposit,
            new Account(
                Id::generate(),
                new \DateTimeImmutable(),
                0,
            ),
        );

        $this->expectException(TransactionException::class);

        $this->account->applyTransaction($transaction);
    }
}
