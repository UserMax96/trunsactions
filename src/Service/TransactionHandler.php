<?php

declare(strict_types=1);

namespace TransactionsSystem\Service;

use TransactionsSystem\Entity\Account;
use TransactionsSystem\Entity\AccountInterface;
use TransactionsSystem\Entity\Exception\IncorrectAmountException;
use TransactionsSystem\Entity\Exception\InsufficientFundsException;
use TransactionsSystem\Entity\Exception\NotFoundException;
use TransactionsSystem\Entity\Exception\TransactionException;
use TransactionsSystem\Entity\Repository\AccountRepositoryInterface;
use TransactionsSystem\Entity\Transaction;
use TransactionsSystem\Entity\TransactionType;
use TransactionsSystem\Entity\TransferTransaction;
use TransactionsSystem\Entity\ValueObject\Id;

readonly class TransactionHandler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function replenishment(TransactionCommand $command): void
    {
        if ($command->amount <= 0) {
            throw new TransactionException("Replenishment with incorrect amount: {$command->amount}");
        }

        $account = $this->getAccount($command->accountId);

        $account->applyTransaction(
            new Transaction(
                Id::generate(),
                $command->amount,
                $command->comment,
                TransactionType::Deposit,
                $account,
            )
        );

        $this->accountRepository->update($account);
    }

    public function withdraw(TransactionCommand $command): void
    {
        if ($command->amount >= 0) {
            throw new IncorrectAmountException("Withdraw with incorrect amount: {$command->amount}");
        }

        $account = $this->getAccount($command->accountId);

        if ($account->getBalance() < $command->amount) {
            throw new InsufficientFundsException(
                "Account id: {$account->getId()}, balance: {$account->getBalance()}, withdraw amount: {$command->amount}"
            );
        }

        $account->applyTransaction(
            new Transaction(
                Id::generate(),
                $command->amount,
                $command->comment,
                TransactionType::Withdrawal,
                $account,
            )
        );

        $this->accountRepository->update($account);
    }
    
    public function transfer(TransferCommand $command): void
    {
        if ($command->amount <= 0) {
            throw new IncorrectAmountException("Transfer with incorrect amount: {$command->amount}");
        }

        $sender    = $this->getAccount($command->accountId);
        $recipient = $this->getAccount($command->recipientId);

        if ($sender->getBalance() < $command->amount) {
            throw new InsufficientFundsException(
                "Account id: {$sender->getId()}, balance: {$sender->getBalance()}, withdraw amount: {$command->amount}"
            );
        }

        $sender->applyTransaction(
            new TransferTransaction(
                Id::generate(),
                -$command->amount,
                $command->comment,
                $sender,
                TransactionType::OutgoingTransfer,
                $recipient,
            )
        );

        $recipient->applyTransaction(
            new TransferTransaction(
                Id::generate(),
                $command->amount,
                $command->comment,
                $sender,
                TransactionType::OutgoingTransfer,
                $recipient,
            )
        );

        $this->accountRepository->update($sender);
        $this->accountRepository->update($recipient);
    }

    private function getAccount(string $id): AccountInterface
    {
        $account = $this->accountRepository->find($id);

        if (null === $account) {
            throw new NotFoundException("Incorrect account id: {$id}");
        }

        return $account;
    }
}
