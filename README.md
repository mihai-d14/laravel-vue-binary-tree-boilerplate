# Binary Tree User Management System

A full-stack application that manages users in both Binary Search Tree (BST) and AVL Tree data structures, with a Laravel backend and Vue.js frontend.

## Project Structure

```
laravel-vue-boilerplate-mihai/
├── code-be/    # Backend Laravel Application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/     # Request handlers
│   │   │   └── Requests/        # Form validation
│   │   ├── Models/              # Database models
│   │   ├── Services/            # Tree implementations
│   │   ├── Interfaces/          # SOLID interfaces
│   │   └── Repositories/        # Data access layer
│   └── tests/                   # Backend tests
├── code-fe/           # Frontend Vue Application
│   ├── src/
│   │   ├── components/          # Vue components
│   │   ├── views/               # Page components
│   │   └── services/            # API services
│   └── tests/                   # Frontend tests
└── docker-compose.yml           # Docker configuration
```

## Architecture and SOLID Principles

### Backend Architecture
- **Interfaces**: Define contracts for tree operations (`BinaryTreeInterface`, `TreeNodeInterface`)
- **Abstract Classes**: Provide base implementations (`AbstractBinaryTree`)
- **Concrete Implementations**: Specific tree types (`BinarySearchTree`, `AVLTree`)
- **Repository Pattern**: Abstracts data persistence (`UserRepository`)

### SOLID Principles Application
1. **Single Responsibility Principle (SRP)**
   - Each tree class handles only tree-specific operations
   - Repository class handles data persistence
   - Controllers handle HTTP requests

2. **Open/Closed Principle (OCP)**
   - Tree implementations extend base classes without modification
   - New tree types can be added without changing existing code

3. **Liskov Substitution Principle (LSP)**
   - Both BST and AVL implementations can be used interchangeably

4. **Interface Segregation Principle (ISP)**
   - Separate interfaces for tree operations and node operations

5. **Dependency Inversion Principle (DIP)**
   - High-level modules depend on abstractions
   - Repository pattern decouples data access

## Database Schema

```sql
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    tree_type VARCHAR(3) NOT NULL,
    _lft INTEGER,
    _rgt INTEGER,
    parent_id BIGINT REFERENCES users(id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Running with Docker

1. Clone the repository:
```bash
git clone [repository-url]
cd laravel-vue-boilerplate-mihai
```

2. Create environment files:
```bash
# Backend
cp code-be/.env.example code-be/.env

# Set these values in .env:
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:HcDfhpDdywWQ4McmD15a0cN8t6P0bvd5TvSyix9WkDk=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=binary-tree-project
DB_USERNAME=postgres
DB_PASSWORD=postgres

BROADCAST_DRIVER=log
CACHE_DRIVER=array
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000/api/v1
```

3. Start the containers:
```bash
docker compose up -d
```

4. Run migrations:
```bash
docker compose exec backend php artisan migrate
```

5. Access the applications:
- Frontend: http://localhost:5173
- Backend API: http://localhost:8000

## Alternative Approaches and Improvements

1. **Tree Implementation**
   - Could use a more memory-efficient representation
   - Could implement Red-Black trees for comparison
   - Could add batch operations for better performance

2. **Database Design**
   - Could use materialized paths for hierarchical data
   - Could implement caching for frequent queries
   - Could add soft deletes for data recovery

3. **API Design**
   - Could implement GraphQL for more flexible queries
   - Could add pagination for large datasets
   - Could implement real-time updates with WebSockets

4. **Testing**
   - Could add more integration tests
   - Could implement performance tests
   - Could add stress tests for tree operations

## Testing

Run backend tests:
```bash
docker compose exec backend php artisan test
```

Run frontend tests:
```bash
docker compose exec frontend npm test
```