RubaXa Sortable for Yii 2
=========================

This is an [Yii framework 2.0](http://www.yiiframework.com) implementation of the [RubaXa Sortable](https://rubaxa.github.io/Sortable/) extension. To create reorderable drag-and-drop lists for modern browsers and touch devices.

[![Latest Stable Version](https://poser.pugx.org/yii-ui/yii2-rubaxa-sortable/v/stable.png)](https://packagist.org/packages/yii-ui/yii2-rubaxa-sortable)
[![Total Downloads](https://poser.pugx.org/yii-ui/yii2-rubaxa-sortable/downloads.png)](https://packagist.org/packages/yii-ui/yii2-rubaxa-sortable)
[![Code Climate](https://codeclimate.com/github/yii-ui/yii2-rubaxa-sortable/badges/gpa.svg)](https://codeclimate.com/github/yii-ui/yii2-rubaxa-sortable)
[![Dependency Status](https://www.versioneye.com/user/projects/5805f229c5b08c004af419a3/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5805f229c5b08c004af419a3)
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)
[![License](https://poser.pugx.org/yii-ui/yii2-rubaxa-sortable/license)](https://packagist.org/packages/yii-ui/yii2-rubaxa-sortable)

Installation
------------

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```
php composer.phar require yii-ui/yii2-rubaxa-sortable
```

or add

```
"yii-ui/yii2-rubaxa-sortable": "~1.0.0"
```

to the require section of your `composer.json` file.

Usage
-----

```php
use yiiui\rubaxasortable\Sortable;

echo Sortable::widget([
    'items' => [
        'Item 1',
        ['content' => 'Item2'],
        [
            'content' => 'Item3',
            'options' => ['class' => 'text-danger'],
        ],
    ],
    'containerOptions' => ['class' => 'list-group'],
    'itemOptions' => ['class' => 'list-group-item'],
    'clientOptions' => ['animation' => 0],
]);
```

More [Examples](https://www.yii-ui.com/yii2-rubaxa-sortable/examples) will be added soon at https://www.yii-ui.com/yii2-rubaxa-sortable/examples.
For plugin configuration see RubaXa Sortable [Documentation](https://rubaxa.github.io/Sortable/).

Documentation
------------

[Documentation](https://www.yii-ui.com/yii2-rubaxa-sortable/docs) will be added soon at https://www.yii-ui.com/yii2-rubaxa-sortable/docs.

License
-------

**yii2-rubaxa-sortable** is released under the BSD 3-Clause License. See the [LICENSE.md](LICENSE.md) for details.
