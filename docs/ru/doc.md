# Работа с репозиториями

## Какие проблемы решает данная библиотека

### Абстракция взаимодействия с хранилищем данных
Низкоуровневая логика взаимодействия с хранилищем данных может быть вынесена в репозиторий. Это позволяет писать удобный для тестирования и легко поддающийся изменению код.

### Оптимизация системных ресурсов
Репозитории не должны содержать мутируемого внутреннего состояния. Внутреннее состояние, при необходимости, задается в конструкторе и не должно меняться во время выполнения программы чтобы гарантировать стабильность работы. Соответственно, все репозитории должны заводиться в систему как синглтоны.
Библиотека предполагает работу с репозиториями через специальный класс `Prozorov\Repositories\RepositoryFactory`, который выступает в роли сервис контейнера для репозиториев. Он сам следит за тем, чтобы репозитории создавались только по запросу, и существовали в единственном экземпляре.

### Удобство тестирования
Библиотека ставит удобство написания модульных тестов во главу всего и предоставляет ряд инструментов для удобства тестирования проекта.

## Использование

Хотя репозитории могут использоваться отдельно, предполагается, что управлять ими будет фабрика `Prozorov\Repositories\RepositoryFactory`. Нужно убедиться, что на уровне системы фабрика является синглтоном. Для создания фабрики нужно передать ей в конструктор класс, реализующий интерфейс `Prozorov\Repositories\Contracts\ResolverInterface`. Задача этого класса - создавать репозитории. В системе есть 3 штатных класса для этого:
- `Prozorov\Repositories\Resolvers\HardResolver` - этот класс пытается создать заданный класс через new.
- `Prozorov\Repositories\Resolvers\ContainerAwareResolver` - этот класс может работать с PSR-11 контейнером.
- `Prozorov\Repositories\Resolvers\ChainResolver` - этот класс позволяет использовать цепочку из загрузчиков. Он принимает в конструктор массив из других классов, реализующих `ResolverInterface`, и для разрешения класса репозитория он будет последовательно обращаться к каждому из них, пока какой-нибудь из этих классов не разрешит репозиторий.

Пример использования с `HardResolver`:
```
use Prozorov\Repositories\RepositoryFactory;
use Prozorov\Repositories\Resolvers\HardResolver;
use App\Repositories\UserRepository; // класс, реализующий `Prozorov\Repositories\Contracts\RepositoryInterface`

factory = new RepositoryFactory(new HardResolver, [
    'users' => UserRepository::class,
]);
```

### Создание репозиториев
Репозитории должны реализовывать интерфейс `Prozorov\Repositories\Contracts\RepositoryInterface`. Однако, рекомендуется наследоваться от класса `Prozorov\Repositories\AbstractRepository` и реализовать там абстрактные методы. В этом классе уже реализована поддержка удобной загрузки фикстур.

### Загрузка определений в фабрику
Чтобы фабрика могла работать с репозиториями, нужно предоставить ей инструкции по созданию репозиториев. Инструкции - это массив, ключи которого - это символьные коды репозитория, а значения - это строки либо анонимные функции. В случае, если передано замыкание, оно будет выполнено, результатом его выполнения должен быть класс, реализующий `RepositoryInterface`.
В случае, если передана строка, то она будет передана резолверу (классу, реализующему интерфейс `ResolverInterface`). В случае, если резолвер не смог создать репозиторий, он выбросит исключение `Prozorov\Repositories\Exceptions\CouldNotResolve`

Пример:
```
use Prozorov\Repositories\RepositoryFactory;
use Prozorov\Repositories\Resolvers\HardResolver;
use Prozorov\Repositories\Tests\Support\UserRepository;
use Prozorov\Repositories\Tests\Support\ProductRepository;

$bindings = [
    'users' => UserRepository::class,
    'products' => function () {
        return new ProductRepository();
    },
];

$repositoryFactory = new RepositoryFactory(new HardResolver(), $bindings);
```

## Тестирование

### Загрузка тестовых данных в репозиторий
Библиотека позволяет загрузить в репозиторий тестовые данные, тогда вместо настоящих данных репозиторий будет отдавать их. Для реализации этой возможности нужно наследовать свой репозиторий от абстрактного класса `Prozorov\Repositories\AbstractRepository`.

Пример:
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

var_dump($repositoryFactory->getRepository('products')->getById(70)); // вернет $product
```

Для более специфичного тестирования можно использовать метод `mock()`, который вернет тестовый двойник указанного репозитория.

Пример:
```
$product = [
    'id' => 70,
    'name' => 'product70',
    'price' => 700,
];

$mock = $repositoryFactory->mock('products');

$mock->shouldReceive('getById')->once()->with(70)->andReturn($product);

var_dump($repositoryFactory->getRepository('products')->getById(70)); // вернет $product
```
