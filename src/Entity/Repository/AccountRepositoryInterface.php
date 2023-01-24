<?php

namespace TransactionsSystem\Entity\Repository;

use TransactionsSystem\Entity\AccountCollection;
use TransactionsSystem\Entity\AccountInterface;

interface AccountRepositoryInterface
{
    public function findAll(): AccountCollection;
    public function find($id): ?AccountInterface;
    public function save(AccountInterface $account);
    public function update(AccountInterface $account);
}
