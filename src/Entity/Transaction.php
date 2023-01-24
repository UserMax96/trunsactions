<?php

declare(strict_types=1);

namespace TransactionsSystem\Entity;

use DateTimeImmutable;
use TransactionsSystem\Entity\ValueObject\Id;
use Webmozart\Assert\Assert;

class Transaction implements TransactionInterface
{
    private readonly float $amount;

    public readonly string $comment;

    public readonly DateTimeImmutable $date;

    public function __construct(
        private readonly Id $id,
        float $amount,
        string $comment,
        public readonly TransactionType $type,
        private readonly AccountInterface $account,
    ) {
        Assert::notEq($amount, 0);
        Assert::notEmpty($comment);

        $this->amount = $amount;
        $this->comment = $comment;
        $this->date = new DateTimeImmutable();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }
}
