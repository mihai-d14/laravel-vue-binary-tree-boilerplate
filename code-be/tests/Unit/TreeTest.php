<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BinarySearchTree;
use App\Services\AVLTree;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TreeTest extends TestCase
{
    use RefreshDatabase;

    private BinarySearchTree $bst;
    private AVLTree $avl;
    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bst = new BinarySearchTree();
        $this->avl = new AVLTree();
        $this->repository = new UserRepository();
    }

    public function test_avl_self_balancing()
    {
        // Create nodes in ascending order
        $users = [
            ['id' => 1, 'name' => 'User 1', 'email' => 'user1@test.com', 'tree_type' => 'avl'],
            ['id' => 2, 'name' => 'User 2', 'email' => 'user2@test.com', 'tree_type' => 'avl'],
            ['id' => 3, 'name' => 'User 3', 'email' => 'user3@test.com', 'tree_type' => 'avl']
        ];

        // Insert each user
        foreach ($users as $userData) {
            $this->avl->insert($userData);
        }

        // Get all nodes in inorder traversal
        $allNodes = $this->avl->getAllNodes();
        
        // After AVL balancing, node with value 2 should be the root (middle value)
        $this->assertCount(3, $allNodes);
        $this->assertEquals(1, $allNodes[0]['value']); // Left child
        $this->assertEquals(2, $allNodes[1]['value']); // Root
        $this->assertEquals(3, $allNodes[2]['value']); // Right child
    }

    public function test_user_repository_creates_user_in_correct_tree()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'tree_type' => 'bst',
            'password' => 'password123' // Add password
        ];

        $user = $this->repository->createUser($userData);
        
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'tree_type' => 'bst'
        ]);

        $this->assertNotNull($user->_lft);
        $this->assertNotNull($user->_rgt);
        $this->assertTrue($user->_lft < $user->_rgt);
    }
}