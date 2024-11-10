<?php

namespace App\Interfaces;

interface BinaryTreeInterface
{
    public function insert(array $userData): void;
    public function delete(int $userId): void;
    public function update(int $userId, array $userData): void;
    public function find(int $userId): ?array;
    public function getAllNodes(): array;
}