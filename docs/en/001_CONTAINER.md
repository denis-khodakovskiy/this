# THIS — That Handles It Somehow

## Container

THIS uses a **lazy-loading service container** with explicit configuration and zero runtime magic.

The container stores **only service definitions**, not instantiated services.
A service is created **only when it is explicitly requested**.

There is no container compilation.
There is no container cache.
There is no reflection-based auto-wiring.

What you configure is exactly what exists at runtime.

---

### Container Configuration

Container configuration can be split across multiple files.
All configuration files must be **explicitly included** in `_bootstrap.php`.

By convention, container configuration files are stored in:

```
bootstrap/container/
```

Each configuration file **must return a lambda** with the following signature:

```php
static function (ContainerInterface $container): void {}
```

Example:

```php
return static function (ContainerInterface $container): void {
    // service registration
};
```

If a configuration file is not included in `_bootstrap.php`, it does not exist for the container.

There is no automatic loading.

---

### Service Registration

The container provides two methods for registering services.

`bind(string $id, callable $definition)`  
Registers a service that is created **every time** it is requested.

`singleton(string $id, callable $definition)`  
Registers a service that is created **once** and reused on subsequent requests.

The `id` is an arbitrary string used as a lookup key.
Most commonly, fully qualified interface or class names are used.

The `definition` is any callable that returns a fully configured service instance.

---

### Simple Services

Example of a simple service:

```php
$container->bind(
    id: ValidatorInterface::class,
    definition: static fn () => new Validator(),
);
```

Singleton service:

```php
$container->singleton(
    id: RequestProviderInterface::class,
    definition: static fn () => new RequestProvider(),
);
```

---

### Services with Dependencies

A service definition may accept a single argument — `ContainerInterface`.

This allows resolving dependencies explicitly.

Example:

```php
$container->bind(
    id: RouterMiddleware::class,
    definition: static fn (ContainerInterface $container) => new RouterMiddleware(
        $container->get(id: RouteRegistry::class),
        $container->get(id: RouterPolicyRegistryInterface::class),
    ),
);
```

There is no auto-wiring.
All dependencies are resolved manually.

---

### Factories

Complex service configuration can be extracted into factories.

Factory without dependencies:

```php
$container->bind(
    id: RouterPolicyRegistryInterface::class,
    definition: new RouterPolicyRegistryFactory(),
);
```

Factory with dependencies:

```php
$container->singleton(
    id: LoggerInterface::class,
    definition: static fn (ContainerInterface $container) => (new LoggerFactory(
        $container->get(id: KernelConfigProviderInterface::class),
    ))(),
);
```

The factory is created inside the lambda and immediately invoked.

---

### Registration Rules

The order of service registration does not matter.

However, everything used as a dependency and all handlers referenced by the router must be registered.
If a service is not registered, it does not exist.