# THIS — That Handles It Somehow

**THIS** is not a framework for quick setup, scaffolding, or reflection-driven magic.

It is a framework for developers who deliberately choose **explicit control over implicit behavior**.

THIS is built for people who want to understand *exactly* what happens in their application, when it happens, and why.

---

## Philosophy

Most modern frameworks optimize for speed of onboarding. They rely on auto-configuration, container compilation, reflection-based discovery, hidden registries, and conventions that silently shape application behavior.

THIS intentionally goes in the opposite direction.

There is no automatic service discovery.  
There is no container caching or compilation.  
There is no reflection-based auto-wiring.  
There are no hidden registries or global state.

If something exists in the system, it exists because **you explicitly created it and wired it**.

This is not a missing feature — it is a conscious design choice.

---

## Explicit Over Implicit

In THIS, behavior is preferred over conventions.

You do not “enable” features by installing packages.  
You do not get functionality by naming things correctly.  
You do not rely on side effects of scanning or bootstrapping.

Every important decision is made in code, in one place, in plain sight.

This makes the system slightly more verbose, but significantly more predictable.

---

## No Magic by Design

THIS avoids architectural shortcuts that trade clarity for convenience.

There is no container compilation step that changes runtime behavior.  
There is no reflection-driven registration that silently alters execution flow.  
There is no autoconfiguration that depends on environment, cache, or build artifacts.

Runtime behavior is the same every time the application starts.

What you read in code is what the system does.

---

## Manual Configuration as a Feature

All configuration in THIS is manual.

Pipelines are assembled explicitly.  
Middleware is added intentionally and in a defined order.  
Message handlers are registered by hand.  
Validation rules are declared, not inferred.

This approach shifts responsibility from the framework to the developer — on purpose.

The result is a system where nothing happens accidentally.

---

## Who THIS Is For

THIS is designed for developers who:

- prefer control over convenience
- want to understand and own their architecture
- are comfortable with explicit wiring
- value predictability over shortcuts
- are building long-lived systems, not prototypes

THIS is **not** aimed at rapid prototyping, tutorials, or “five minutes to hello world”.

---

## A Conscious Trade-off

THIS trades:

- speed of initial setup  
  for
- long-term clarity and control

It trades:

- implicit magic  
  for
- explicit behavior

If you are comfortable with that trade-off, THIS will feel natural.

If you are not, THIS will feel intentionally uncomfortable.

That is by design.
