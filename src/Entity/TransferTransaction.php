<?php

declare(strict_types=1);

namespace TransactionsSystem\Entity;

use TransactionsSystem\Entity\ValueObject\Id;

class TransferTransaction extends Transaction
{
    public function __construct(
        Id $id,
        float $amount,
        string $comment,
        Account $account,
        TransactionType $type,
        public readonly AccountInterface $recipient,
    ) {
        parent::__construct($id, $amount, $comment, $type, $account);
    }
}
