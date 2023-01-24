<?php

declare(strict_types=1);

namespace TransactionsSystem\Entity;

use Ramsey\Collection\AbstractCollection;

class TransactionCollection extends AbstractCollection
{
    public function getType(): string
    {
        return TransactionInterface::class;
    }
}
