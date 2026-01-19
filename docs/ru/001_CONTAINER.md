# THIS — That Handles It Somehow

## Контейнер

THIS использует **контейнер с ленивой загрузкой сервисов** и полностью явной конфигурацией.

Контейнер хранит **только определения сервисов**, а не их экземпляры.
Сервис создаётся **только в момент явного запроса**.

Нет компиляции контейнера.
Нет кэширования контейнера.
Нет автосвязывания через рефлексию.

В рантайме существует ровно то, что вы сконфигурировали.

---

### Конфигурация контейнера

Конфигурацию контейнера можно разбивать на несколько файлов.
Все файлы конфигурации должны быть **явно подключены** в `_bootstrap.php`.

По соглашению файлы конфигурации контейнера располагаются в:

```
bootstrap/container/
```

Каждый файл конфигурации **обязан возвращать лямбду** со следующей сигнатурой:

```php
static function (ContainerInterface $container): void {}
```

Пример:

```php
return static function (ContainerInterface $container): void {
    // регистрация сервисов
};
```

Если файл не подключён в `_bootstrap.php`, контейнер о нём не знает.

Никакой автоматической загрузки нет.

---

### Регистрация сервисов

Контейнер предоставляет два метода регистрации сервисов.

`bind(string $id, callable $definition)`  
Регистрирует сервис, который создаётся **заново при каждом вызове** `get()`.

`singleton(string $id, callable $definition)`  
Регистрирует сервис, который создаётся **один раз** и переиспользуется.

`id` — произвольная строка, используемая как ключ.
Обычно используется полное имя интерфейса или класса.

`definition` — любой callable, возвращающий сконфигурированный сервис.

---

### Простые сервисы

Пример простого сервиса:

```php
$container->bind(
    id: ValidatorInterface::class,
    definition: static fn () => new Validator(),
);
```

Singleton-сервис:

```php
$container->singleton(
    id: RequestProviderInterface::class,
    definition: static fn () => new RequestProvider(),
);
```

---

### Сервисы с зависимостями

Лямбда определения сервиса может принимать один аргумент — `ContainerInterface`.

Это позволяет явно разрешать зависимости.

Пример:

```php
$container->bind(
    id: RouterMiddleware::class,
    definition: static fn (ContainerInterface $container) => new RouterMiddleware(
        $container->get(id: RouteRegistry::class),
        $container->get(id: RouterPolicyRegistryInterface::class),
    ),
);
```

Автосвязывания нет.
Все зависимости указываются вручную.

---

### Фабрики

Сложную конфигурацию сервисов можно выносить в фабрики.

Фабрика без зависимостей:

```php
$container->bind(
    id: RouterPolicyRegistryInterface::class,
    definition: new RouterPolicyRegistryFactory(),
);
```

Фабрика с зависимостями:

```php
$container->singleton(
    id: LoggerInterface::class,
    definition: static fn (ContainerInterface $container) => (new LoggerFactory(
        $container->get(id: KernelConfigProviderInterface::class),
    ))(),
);
```

Фабрика создаётся внутри лямбды и сразу вызывается.

---

### Правила регистрации

Порядок регистрации сервисов не имеет значения.

Однако должны быть зарегистрированы:
– все сервисы, используемые как зависимости
– все обработчики, объявленные в роутере

Если сервис не зарегистрирован — его не существует.