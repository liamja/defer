# Defer

Postpone the calling of a function or callable.


## Why?

From _Go by Example_:

> Defer is used to ensure that a function call is performed later in a program’s execution, usually for purposes of cleanup. 
> `defer` is often used where e.g. `ensure` and `finally` would be used in other languages.

Common use cases are:
* Cleaning up temporary files.
* Closing network connections.
* Closing database connections.

Comparing `defer` to `finally`, this implementation of `defer` will allow us to have better control over when our
deferred functions are called; we can decide when to start stacking deferred functions, and where to finally call them.


## Examples

### Usage

```php
// Create an instance of Defer.
// When $defer falls out of scope,
// your deferred callables will be,
// called in reverse order.
$defer = new Defer;

// Push your deferred tasks to the $defer object.
$defer->push(function () {
    echo "I'm echoed last!";
});

// As a convenience, you can also call $defer as a function
$defer(function () {
    echo "I'm echoed second!";
});

echo "I'm called first!";
```


### Closing Resources

Defer can be used for ensuring the closing of open files:

```php
$fp = fopen('/tmp/file', 'w');

$defer(function () use ($fp) {
   fclose($fp);
});

fwrite($fp, 'Some temporary data.');
```
