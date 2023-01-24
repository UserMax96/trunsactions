<?php

declare(strict_types=1);

namespace TransactionsSystem\Entity;

enum TransactionType: string
{
    case Deposit          = 'Deposit';
    case Withdrawal       = 'Withdrawal';
    case IncomingTransfer = 'Incoming transfer';
    case OutgoingTransfer = 'Outgoing transfer';
}
