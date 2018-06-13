# yii2-widget-growl

A widget that turns standard Bootstrap alerts into "Growl-like" notifications. This widget is a wrapper for the
[Bootstrap Growl plugin](https://github.com/ciedooy/bootstrap-notify) by [ciedooy](https://github.com/ciedooy).

This extension is a bootstrap 4 port of [Kartik Visweswaran](https://github.com/kartik-v)'s great extension:
[yii2-widget-growl](https://github.com/kartik-v/yii2-widget-growl)

## Installation 

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require --prefer-dist simialbi/yii2-widget-growl
```

or add 

```
"simialbi/yii2-widget-growl": "*"
```

to the ```require``` section of your `composer.json`

## Usage

```php
<?php
use simialbi\yii2\growl\Growl;

echo Growl::widget([
	'type' => Growl::TYPE_SUCCESS,
	'icon' => 'fa fa-exclamation-triangle',
	'title' => 'Note',
	'body' => 'This is a successful growling alert.'
]);
```


## Example Usage

## License

**yii2-widget-growl** is released under MIT license. See bundled [LICENSE](LICENSE) for details.
