<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse;

    private UserRepository $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $users = User::all();
            return $this->successResponse([
                'data' => UserResource::collection($users),
                'meta' => [
                    'total' => $users->count(),
                    'tree_type' => $request->query('tree_type', 'bst')
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tree_type' => 'required|in:bst,avl'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $this->userRepository->createUser($validator->validated());
            return $this->createdResponse(
                new UserResource($user),
                'User created successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tree_type' => 'required|in:bst,avl'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updatedUser = $this->userRepository->updateUser($user, $validator->validated());
            return $this->successResponse(
                new UserResource($updatedUser),
                'User updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            $this->userRepository->deleteUser($user);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}