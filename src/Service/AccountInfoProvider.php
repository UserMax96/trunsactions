<?php

declare(strict_types=1);

namespace TransactionsSystem\Service;

use Ramsey\Collection\Sort;
use TransactionsSystem\Entity\AccountCollection;
use TransactionsSystem\Entity\Exception\NotFoundException;
use TransactionsSystem\Entity\Repository\AccountRepositoryInterface;
use TransactionsSystem\Entity\TransactionCollection;

class AccountInfoProvider
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository
    ) {
    }

    public function getAll(): AccountCollection
    {
        return $this->accountRepository->findAll();
    }

    public function getAccountTransactions(string $accountId): TransactionCollection
    {
        $account =  $this->accountRepository->find($accountId);

        if (null === $account) {
            throw new NotFoundException("Incorrect account id: {$accountId}");
        }

        return new TransactionCollection($account->getTransactions());
    }

    public function getAccountTransactionsSortedByDate(string $accountId): TransactionCollection
    {
        $transactions = $this->getAccountTransactions($accountId);

        /** @var TransactionCollection $transactions */
        $transactions = $transactions->sort('date', Sort::Descending);

        return $transactions;
    }

    public function getAccountTransactionsSortedByComment(string $accountId): TransactionCollection
    {
        $transactions = $this->getAccountTransactions($accountId);

        /** @var TransactionCollection $transactions */
        $transactions = $transactions->sort('comment', Sort::Descending);

        return $transactions;
    }
}
