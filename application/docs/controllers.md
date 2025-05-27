# CodeIgniter Controllers Structure

## Overview

This document explains the controller structure in this CodeIgniter application, particularly focusing on the relationship between `CI_Controller` (core framework controller) and `MY_Controller` (our custom extension).

## Controller Hierarchy

1. **CI_Controller** (`system/core/Controller.php`)  
   This is the core controller class provided by the CodeIgniter framework. It should never be modified directly.

2. **MY_Controller** (`application/core/MY_Controller.php`)  
   This is our custom controller extension that adds application-specific functionality. All application controllers should ideally extend this class to benefit from common functionality.

## Features in MY_Controller

- PHPDoc annotations for proper IDE support
- Common template rendering with `setView()`
- Authentication checks with `accesoCheck()`
- Asset loading with `getAssets()`
- Common data preparation with `getDatos()`
- Title and menu handling with `titles()`

## How to Use

When creating a new controller, it should extend `MY_Controller`:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class YourController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Your controller-specific initialization
    }
    
    // Your controller methods...
}
```

## IDE Support

For better IDE support and code completion, we include a helper file:

- `.phpdoc.php` in the application directory

This file provides type hints for various CodeIgniter components and your custom models and libraries.

## Best Practices

1. Never modify the core `CI_Controller` class
2. Add application-wide functionality to `MY_Controller`
3. Avoid duplicating functionality that already exists in the parent classes
4. Use the PHPDoc annotations for better IDE support
5. Always call `parent::__construct()` in your controller constructors
