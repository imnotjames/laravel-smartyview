smartyview
==========

Allows you to use [Smarty 3](http://www.smarty.net/) in Laravel 4

Installation
============

Add `imnotjames\smartyview` as a requirement to composer.json

```javascript
{
	"require": {
		"imnotjames\smartyview": "*"
	}
}
```

Run `composer update` and it should update the packages.

Next you must register SmartyView with Laravel, in `app/config/app.php`.  Add `'SmartyView\SmartyServiceProvider'` to the `providers` key.
