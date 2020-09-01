# Repositories in action

## Problems to be solved by the current library

### Additional abstraction layer between data storage and the business logic

Low-level algorythms can be moved to repositories. This helps create easy-to-test and business-oriented code.

### System resources optimization

Repositories should not contain mutable inner state. All dependencies can be passed to the constructr if needed. So all repositories must be created as singletons within the system.
This library provides a special factory class `Repositories\Core\RepositoryFactory`, that acts as a repository container. It ensures that every repository is created only once.

### Testing made easy: test you algorythm instead of testing particular cases

This library is test-oriented. It provides helper functions for easy testing of repositories.

## Usage

Repositories can be used independently but it is supposed that there is a factory `Repositories\Core\RepositoryFactory` to rule them all.
One has to make sure that this factory exists in the application as a singleton. It expects an instance of  `Repositories\Core\Contracts\ResolverInterface` to its constructor. Resolver is responsible for repository creation.
There are several resolvers available out of th box:
- `Repositories\Core\Resolvers\HardResolver` - Creates the provided repository with `new`.
- `Repositories\Core\Resolvers\ContainerAwareResolver` - Created the provided repository with PSR-11 compatible container.
- `Repositories\Core\Resolvers\ExistingRepositoryResolver` - Checks if the provided class is an instance of `Repositories\Core\Contracts\HasRepositoryInterface` then the method `getRepository` is called and the result is returned.
- `Repositories\Core\Resolvers\ChainResolver` - this is a resolver that can accept any other resolvers so you can combine them.

`HardResolver` example:
```
use Repositories\Core\RepositoryFactory;
use Repositories\Core\Resolvers\HardResolver;
use App\Repositories\Core\UserRepository; // instance of `Repositories\Core\Contracts\RepositoryInterface`

factory = new RepositoryFactory(new HardResolver, [
    'users' => UserRepository::class,
]);
```

### Repository creation

Repositories must implement `Repositories\Core\Contracts\RepositoryInterface` but it is recommended that repositories extend `Repositories\Core\AbstractRepository`. This class provides helper functions for easy unit testing.

### Repository definitions

You can pass an array of definitions (aliases) as a second parameter to repository factory constructor. The array must be structered so that its keys are repository aliases and values are instructions how to create it. Both string class names and closures are allowed.

Example:
```
use Repositories\Core\RepositoryFactory;
use Repositories\Core\Resolvers\HardResolver;
use Repositories\Core\Tests\Support\UserRepository;
use Repositories\Core\Tests\Support\ProductRepository;

$bindings = [
    'users' => UserRepository::class,
    'products' => function () {
        return new ProductRepository();
    },
];

$repositoryFactory = new RepositoryFactory(new HardResolver(), $bindings);
```

## Testing

### Loading fake data into repositories

The library allows to load fake data into repository with factory. In order to use this feature you must use repositories that extend `Repositories\Core\AbstractRepository`.

Example
```
$product = [
    'id' => 70,
    'name' => 'product70',
    'price' => 700,
];

$faked = [
    $product,
];

$repositoryFactory->loadFixtures('products', $faked);

var_dump($repositoryFactory->getRepository('products')->getById(70)); // $product will be returned
```

In order to create more complex tests you can use `mock()` method that will return a fake repository.

Example:
```
$product = [
    'id' => 70,
    'name' => 'product70',
    'price' => 700,
];

$mock = $repositoryFactory->mock('products');

$mock->shouldReceive('getById')->once()->with(70)->andReturn($product);

var_dump($repositoryFactory->getRepository('products')->getById(70)); // $product will be returned
```
