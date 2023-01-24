<?php

declare(strict_types=1);

namespace TransactionsSystem\Entity;

use TransactionsSystem\Entity\Exception\InsufficientFundsException;
use TransactionsSystem\Entity\Exception\TransactionException;
use TransactionsSystem\Entity\ValueObject\Id;

class Account implements AccountInterface
{
    private readonly TransactionCollection $transactions;

    public function __construct(
        private readonly Id                $id,
        public readonly \DateTimeImmutable $openingDate,
        private float                      $balance,
        TransactionCollection              $transactions = null,
    ) {
        $this->transactions = $transactions ?? new TransactionCollection();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function applyTransaction(TransactionInterface $transaction): void
    {
        if ($this->id->value !== $transaction->getAccount()->id->value) {
            throw new TransactionException(
                "Transaction '{$transaction->getId()->value}' belongs to another account '{$transaction->getAccount()->id->value}'."
            );
        }

        if ($transaction->getAmount() < 0 && abs($transaction->getAmount()) > $this->balance) {
            throw new InsufficientFundsException(
                "The transaction amount '{$transaction->getAmount()}' is more than the balance '{$this->balance}'."
            );
        }

        $this->balance += $transaction->getAmount();

        $this->transactions->add($transaction);
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions->toArray();
    }
}
