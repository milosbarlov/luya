CMS Page Properties
========================

What are properties?
--------------------

As the name already says, those are properties you can attache to a page you want. For example you like to use different colors on different pages you can create a color proprtie where the user can select a color and re use the propertie in your blocks or layouts.

Example use cases:

+ Background image.
+ Colors, css properties for contents
+ Specific meta informations
+ Protected a page

Create a new propertie
------------------------

All properties must be in a folder called `properties` and must contain the suffix `Propertie`, LUYA will automatically detect and setup all properties when you run the [import](luya-console.md) command. You can create the properties folder inside your app namespace or inside a module so you can create reusable modules with properties who will be attached automatically.

Example propertie who creates where you can add a class an re use it somewhere:

```php
<?php

namespace app\properties;

class TestProperty extends \admin\base\Property
{
    public function varName()
    {
        return 'foobar';
    }    
    
    public function label()
    {
        return 'Foo Bar Label';
    }
    
    public function type()
    {
        return 'zaa-text';
    }
}
```

After running the import command you will see the propertie in the CMS admin.


### Class methods

|methode	|Optional	|Description
|---		|---		|---
|varName	|no		|The name of the variable, under this name you can access the propertie later
|label		|no		|The human reabeld name of the propertie. This will be visibile to the administration users.
|type		|no		|Choose the type of your variable [available types](app-block-types.md).
|options	|yes	|If the variable type does have options you have to define them in this method.
|defaultValue|yes	|This will be the initvalue of the variable, default is `false`.

Access the propertie
---------------------------

You can access the properties in

+ blocks
+ layouts

> If you access a propertie but the properties has not been append to this page you will get the `defaultValue` defined from your block object.

### in blocks

```php
$this->getEnvOption('pageObject')->nav->getProperty('foobar');
```

Get all properties for this page

```php
$this->getEnvOption('pageObject)->nav->properties;
```

### in Layouts

There is a page component `Yii::$app->page` which will be registered when using the same, the page component contains information about your current page.


```php
Yii::$app->page->getProperty('my-prop');
```

get all properties

```php
Yii::$app->page->properties;
```

Events
------

You can use events inside your block to modified the behavior of your page:

|Name | Description |
|---  | ---
|EVENT_BEFORE_RENDER    |This event will be triggered beofore the page get render, you can set `$event->isValid` to false to prevent the system from further outputs.

### Example of EVENT_BEFORE_RENDER

```php
public function init()
{
    $this->on(self::EVENT_BEFORE_RENDER, [$this, 'beforeRender']);
}

public function beforeRender($event)
{
	if ($this->thisMethodReturnsFalseWhyEver()) {
		Yii::$app->response->redirect('https://luya.io');
    	$event->isValid = false;
	}
}
```



