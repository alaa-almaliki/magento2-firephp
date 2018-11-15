# Magento 2 Fire PHP Module

A simple module for sending debug data to the browser console.


**Installation**

```
composer require alaa/magento2-firephp
```

and then enable the module

**How to use**

To use the module, a fire php browser plugin need to be installed

```
consoleLog(string $message, array $context);
```
Just use the method with message and data array and they will be logged into the browser console.

By default, the method will not work on production mode unless intentionally enabled from the admin panel.

**License**

MIT
