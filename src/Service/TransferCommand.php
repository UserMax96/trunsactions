<?php

declare(strict_types=1);

namespace TransactionsSystem\Service;

readonly class TransferCommand extends TransactionCommand
{
    public function __construct(
        string        $accountId,
        float         $amount,
        string        $comment,
        public string $recipientId
    ) {
        parent::__construct($accountId, $amount, $comment);
    }
}
