<?php

namespace App\Services;

use App\Interfaces\TreeNodeInterface;

class TreeNode implements TreeNodeInterface
{
    private int $value;
    private ?TreeNodeInterface $left = null;
    private ?TreeNodeInterface $right = null;
    private array $userData;

    public function __construct(int $value, array $userData = [])
    {
        $this->value = $value;
        $this->userData = $userData;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getLeft(): ?TreeNodeInterface
    {
        return $this->left;
    }

    public function getRight(): ?TreeNodeInterface
    {
        return $this->right;
    }

    public function setLeft(?TreeNodeInterface $node): void
    {
        $this->left = $node;
    }

    public function setRight(?TreeNodeInterface $node): void
    {
        $this->right = $node;
    }

    public function getUserData(): array
    {
        return $this->userData;
    }
}