<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\BinarySearchTree;
use App\Services\AVLTree;
use Illuminate\Support\Str;

class UserRepository
{
    private BinarySearchTree $bst;
    private AVLTree $avl;

    public function __construct()
    {
        $this->bst = new BinarySearchTree();
        $this->avl = new AVLTree();
    }

    public function createUser(array $data): User
    {
        // Add default password if not provided
        if (!isset($data['password'])) {
            $data['password'] = bcrypt(Str::random(16));
        }

        // Calculate _lft and _rgt values
        $maxRgt = User::where('tree_type', $data['tree_type'])->max('_rgt') ?? 0;
        $data['_lft'] = $maxRgt + 1;
        $data['_rgt'] = $maxRgt + 2;

        // Create user
        $user = User::create($data);

        // Update tree structure
        if ($data['tree_type'] === 'bst') {
            $this->bst->insert($user->toArray());
        } else {
            $this->avl->insert($user->toArray());
        }

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $oldLft = $user->_lft;
        $oldRgt = $user->_rgt;
        $width = $user->_rgt - $user->_lft + 1;

        // Update tree structure
        if ($user->tree_type === 'bst') {
            $this->bst->update($user->id, array_merge($user->toArray(), $data));
        } else {
            $this->avl->update($user->id, array_merge($user->toArray(), $data));
        }

        // Update nested set
        if (isset($data['parent_id']) && $data['parent_id'] !== $user->parent_id) {
            $newParent = User::find($data['parent_id']);
            $newLft = $newParent ? $newParent->_rgt : (User::max('_rgt') + 1);
            
            // Move the node and its children
            User::where('_lft', '>=', $oldLft)
                ->where('_rgt', '<=', $oldRgt)
                ->update([
                    '_lft' => \DB::raw("_lft - $oldLft + $newLft"),
                    '_rgt' => \DB::raw("_rgt - $oldLft + $newLft")
                ]);

            // Update the space between old and new positions
            if ($newLft > $oldLft) {
                User::where('_lft', '>', $oldRgt)
                    ->where('_lft', '<=', $newLft)
                    ->update(['_lft' => \DB::raw('_lft - ' . $width)]);
                    
                User::where('_rgt', '>', $oldRgt)
                    ->where('_rgt', '<=', $newLft)
                    ->update(['_rgt' => \DB::raw('_rgt - ' . $width)]);
            } else {
                User::where('_lft', '>=', $newLft)
                    ->where('_lft', '<', $oldLft)
                    ->update(['_lft' => \DB::raw('_lft + ' . $width)]);
                    
                User::where('_rgt', '>=', $newLft)
                    ->where('_rgt', '<', $oldLft)
                    ->update(['_rgt' => \DB::raw('_rgt + ' . $width)]);
            }
        }

        $user->update($data);
        return $user->fresh();
    }

    public function deleteUser(User $user): bool
    {
        // Remove from tree structure
        if ($user->tree_type === 'bst') {
            $this->bst->delete($user->id);
        } else {
            $this->avl->delete($user->id);
        }

        // Update nested set
        $lft = $user->_lft;
        $rgt = $user->_rgt;
        $width = $rgt - $lft + 1;

        User::where('_lft', '>', $lft)->update(['_lft' => \DB::raw('_lft - ' . $width)]);
        User::where('_rgt', '>', $rgt)->update(['_rgt' => \DB::raw('_rgt - ' . $width)]);

        return $user->delete();
    }

    public function getAllUsers(string $treeType = 'bst'): array
    {
        return $treeType === 'bst' ? $this->bst->getAllNodes() : $this->avl->getAllNodes();
    }
}

