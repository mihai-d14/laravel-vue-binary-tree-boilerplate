<?php

namespace App\Interfaces;

interface TreeNodeInterface
{
    public function getValue(): int;
    public function getLeft(): ?TreeNodeInterface;
    public function getRight(): ?TreeNodeInterface;
    public function setLeft(?TreeNodeInterface $node): void;
    public function setRight(?TreeNodeInterface $node): void;
}