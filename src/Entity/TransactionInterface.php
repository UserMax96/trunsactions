<?php

declare(strict_types=1);

namespace TransactionsSystem\Entity;

use TransactionsSystem\Entity\ValueObject\Id;

interface TransactionInterface
{
    public function getId(): Id;
    public function getAmount(): float;
    public function getAccount(): AccountInterface;
}
