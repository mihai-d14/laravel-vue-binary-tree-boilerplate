<?php

namespace App\Services;

use App\Interfaces\BinaryTreeInterface;
use App\Interfaces\TreeNodeInterface;

abstract class AbstractBinaryTree implements BinaryTreeInterface
{
    protected ?TreeNodeInterface $root = null;

    abstract protected function insertNode(TreeNodeInterface $root, TreeNodeInterface $newNode): TreeNodeInterface;
    abstract protected function deleteNode(?TreeNodeInterface $root, int $value): ?TreeNodeInterface;
    abstract protected function findNode(?TreeNodeInterface $root, int $value): ?TreeNodeInterface;

    protected function inOrderTraversal(?TreeNodeInterface $node, array &$result = []): void
    {
        if ($node === null) {
            return;
        }

        $this->inOrderTraversal($node->getLeft(), $result);
        $result[] = [
            'value' => $node->getValue(),
            'data' => $node instanceof TreeNode ? $node->getUserData() : []
        ];
        $this->inOrderTraversal($node->getRight(), $result);
    }
}