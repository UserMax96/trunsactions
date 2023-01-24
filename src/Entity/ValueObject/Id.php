<?php

declare(strict_types=1);

namespace TransactionsSystem\Entity\ValueObject;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

readonly class Id
{
    public string $value;

    public function __construct(string $uuid)
    {
        Assert::uuid($uuid);

        $this->value = $uuid;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid7()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
