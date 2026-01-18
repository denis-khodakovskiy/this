# THIS: That Handles It Somehow

**THIS** is a minimalistic PHP application core focused on explicit architecture,
predictable bootstrap behavior, and zero hidden magic.

The project deliberately avoids auto-configuration, code scanning, and
compile-time containers in favor of **explicit application assembly**
and **runtime transparency**.

THIS is designed for developers who value clarity, control, and architectural reasoning
over convenience abstractions.

---

## Core Principles

### 1. Explicit over convenient
- No class scanning
- No auto-wiring
- No implicit service discovery
- No “container guessing”

If something is used, it must be **explicitly registered**.

---

### 2. The container is read, not built
In THIS, the container is **not a compiled artifact**.

- No container build step
- No container cache
- No warm-up phase

The container is defined as data and simply loaded at runtime.
PHP OPcache does the rest.

Cold start is predictable and fast.

---

### 3. Architecture over DX sugar
THIS is not a framework for rapid CRUD or zero-config applications.

It is intended for:
- infrastructure code
- complex pipelines
- CLI and worker-based systems
- applications with strict responsibility boundaries

Developer experience is achieved through **structure**, not automation.

---

### 4. No global magic
- No global container
- No service locator
- No hidden dependencies

All dependencies are:
- explicitly defined
- or resolved at boundary layers (bootstrap, handlers)

---

## System Structure

THIS separates the system into **clear architectural layers**,
each with a strictly limited responsibility.

---

### Kernel
Responsible for:
- application bootstrap
- pipeline assembly
- middleware execution
- container usage

The kernel **contains no business logic**.

---

### Container
The container in THIS:
- does not scan code
- does not use reflection
- does not auto-configure services

It:
- reads configuration
- knows how to create services
- creates only what is actually requested

Container configuration can be split into multiple files:
- services
- handlers
- factories
- middleware
- vendor packages

---

### Middleware
Middleware in THIS:
- is linear
- does not branch the pipeline
- does not mutate the pipeline
- performs one clearly defined task

Middleware **must not**:
- contain business logic
- know about i18n
- resolve services dynamically

---

### Validation
The validation package:
- has no knowledge of i18n
- has no access to the container
- does not format messages
- performs no side effects

It:
- validates data
- returns validation results

`FormSchema` is a **declarative object**, not a service.

---

### i18n
i18n is an infrastructure concern.

- Translations are plain PHP files
- Translation keys may be human-readable text
- Missing translations are acceptable
- Locale is provided by execution context

i18n **never participates in validation directly**.

---

## Bootstrap and Boundaries

THIS strictly distinguishes between:
- declaration (schemas, rules, structure)
- execution (handlers, middleware)
- boundary logic (HTTP, CLI, workers)

Objects with dependencies may be created **only at boundary layers**.

---

## Why No Auto-Configuration

Auto-configuration:
- hides dependencies
- complicates debugging
- requires compile-time containers
- depends on container caching

THIS deliberately avoids it in favor of:
- transparency
- predictability
- architectural clarity

---

## Caching

THIS **does not require** container caching.

Possible caches:
- PHP OPcache
- environment configuration cache
- route cache (optional)

If a system requires caching to function correctly,
it is usually an architectural problem, not a performance one.

---

## Project Philosophy

THIS is not “yet another framework”.

It is:
- an architectural core
- a tool for conscious system design
- a system where it is always clear *why* things work the way they do

The project prioritizes:
- long-term maintainability
- reasoning simplicity
- minimal hidden decisions

---

## Who This Is For

THIS is suitable if you:
- want to understand the entire system
- value explicit dependencies
- build infrastructure-level software
- accept explicit code in exchange for clarity

THIS is **not suitable** if you:
- expect zero-config behavior
- rely on auto-scanning
- want a Symfony/Laravel replacement

---

## Status

THIS is under active development.
Architecture decisions are made deliberately,
without rushing and without backward-compatibility pressure.
