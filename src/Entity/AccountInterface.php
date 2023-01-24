<?php

declare(strict_types=1);

namespace TransactionsSystem\Entity;

use TransactionsSystem\Entity\ValueObject\Id;

interface AccountInterface
{
    public function getId(): Id;
    public function getBalance(): float;
    public function applyTransaction(TransactionInterface $transaction): void;
    /**
     * @return Transaction[]
     */
    public function getTransactions(): array;
}
