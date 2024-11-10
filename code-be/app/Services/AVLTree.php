<?php
namespace App\Services;

use App\Interfaces\TreeNodeInterface;

class AVLTree extends BinarySearchTree
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

    private function getHeight(?TreeNodeInterface $node): int
    {
        if ($node === null) {
            return 0;
        }
        return 1 + max($this->getHeight($node->getLeft()), $this->getHeight($node->getRight()));
    }

    private function getBalance(?TreeNodeInterface $node): int
    {
        if ($node === null) {
            return 0;
        }
        return $this->getHeight($node->getLeft()) - $this->getHeight($node->getRight());
    }

    private function rightRotate(TreeNodeInterface $y): TreeNodeInterface
    {
        $x = $y->getLeft();
        $T2 = $x->getRight();

        $x->setRight($y);
        $y->setLeft($T2);

        return $x;
    }

    private function leftRotate(TreeNodeInterface $x): TreeNodeInterface
    {
        $y = $x->getRight();
        $T2 = $y->getLeft();

        $y->setLeft($x);
        $x->setRight($T2);

        return $y;
    }

    protected function insertNode(TreeNodeInterface $root, TreeNodeInterface $newNode): TreeNodeInterface
    {
        // Base case: return new node if root is null
        if ($root === null) {
            return $newNode;
        }

        // Perform standard BST insert
        if ($newNode->getValue() < $root->getValue()) {
            if ($root->getLeft() === null) {
                $root->setLeft($newNode);
            } else {
                $root->setLeft($this->insertNode($root->getLeft(), $newNode));
            }
        } elseif ($newNode->getValue() > $root->getValue()) {
            if ($root->getRight() === null) {
                $root->setRight($newNode);
            } else {
                $root->setRight($this->insertNode($root->getRight(), $newNode));
            }
        } else {
            return $root; // Duplicate values not allowed
        }

        // Balance the tree
        $balance = $this->getBalance($root);

        // Left Left Case
        if ($balance > 1 && $newNode->getValue() < $root->getLeft()->getValue()) {
            return $this->rightRotate($root);
        }

        // Right Right Case
        if ($balance < -1 && $newNode->getValue() > $root->getRight()->getValue()) {
            return $this->leftRotate($root);
        }

        // Left Right Case
        if ($balance > 1 && $newNode->getValue() > $root->getLeft()->getValue()) {
            $root->setLeft($this->leftRotate($root->getLeft()));
            return $this->rightRotate($root);
        }

        // Right Left Case
        if ($balance < -1 && $newNode->getValue() < $root->getRight()->getValue()) {
            $root->setRight($this->rightRotate($root->getRight()));
            return $this->leftRotate($root);
        }

        return $root;
    }
}