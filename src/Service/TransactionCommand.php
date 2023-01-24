<?php

declare(strict_types=1);

namespace TransactionsSystem\Service;
readonly class TransactionCommand
{
    public function __construct(
        public string $accountId,
        public float  $amount,
        public string $comment,
    ) {
    }
}
