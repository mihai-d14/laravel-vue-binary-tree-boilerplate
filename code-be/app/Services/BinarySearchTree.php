<?php
namespace App\Services;

use App\Interfaces\TreeNodeInterface;

class BinarySearchTree extends AbstractBinaryTree
{
    public function insert(array $userData): void
    {
        $newNode = new TreeNode($userData['id'], $userData);
        if ($this->root === null) {
            $this->root = $newNode;
            return;
        }
        $this->root = $this->insertNode($this->root, $newNode);
    }

    protected function insertNode(TreeNodeInterface $root, TreeNodeInterface $newNode): TreeNodeInterface
    {
        if ($newNode->getValue() < $root->getValue()) {
            if ($root->getLeft() === null) {
                $root->setLeft($newNode);
            } else {
                $this->insertNode($root->getLeft(), $newNode);
            }
        } else {
            if ($root->getRight() === null) {
                $root->setRight($newNode);
            } else {
                $this->insertNode($root->getRight(), $newNode);
            }
        }
        return $root;
    }

    public function find(int $userId): ?array
    {
        $node = $this->findNode($this->root, $userId);
        return $node instanceof TreeNode ? $node->getUserData() : null;
    }

    protected function findNode(?TreeNodeInterface $root, int $value): ?TreeNodeInterface
    {
        if ($root === null || $root->getValue() === $value) {
            return $root;
        }

        if ($value < $root->getValue()) {
            return $root->getLeft() ? $this->findNode($root->getLeft(), $value) : null;
        }

        return $root->getRight() ? $this->findNode($root->getRight(), $value) : null;
    }

    public function delete(int $userId): void
    {
        if ($this->root === null) {
            return;
        }
        $this->root = $this->deleteNode($this->root, $userId);
    }

    protected function deleteNode(?TreeNodeInterface $root, int $value): ?TreeNodeInterface
    {
        if ($root === null) {
            return null;
        }

        if ($value < $root->getValue()) {
            $root->setLeft($this->deleteNode($root->getLeft(), $value));
            return $root;
        }
        
        if ($value > $root->getValue()) {
            $root->setRight($this->deleteNode($root->getRight(), $value));
            return $root;
        }

        // Node with only one child or no child
        if ($root->getLeft() === null) {
            return $root->getRight();
        }
        if ($root->getRight() === null) {
            return $root->getLeft();
        }

        // Node with two children
        $minValue = $this->getMinValue($root->getRight());
        $newRoot = new TreeNode($minValue->getValue(), $minValue instanceof TreeNode ? $minValue->getUserData() : []);
        $newRoot->setLeft($root->getLeft());
        $newRoot->setRight($this->deleteNode($root->getRight(), $minValue->getValue()));

        return $newRoot;
    }

    public function update(int $userId, array $userData): void
    {
        $this->delete($userId);
        $this->insert($userData);
    }

    private function getMinValue(TreeNodeInterface $root): TreeNodeInterface
    {
        $current = $root;
        while ($current->getLeft() !== null) {
            $current = $current->getLeft();
        }
        return $current;
    }

    public function getAllNodes(): array
    {
        $result = [];
        $this->inOrderTraversal($this->root, $result);
        return $result;
    }
}