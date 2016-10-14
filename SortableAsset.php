<?php
/**
 * @link http://www.yii-ui.com/
 * @copyright Copyright (c) Christoph Moeke, Yii UI, 2016
 * @license http://www.yii-ui.com/license/
 */

namespace yiiui\rubaxasortable;

use yii\web\AssetBundle;

/**
 * Asset bundle for yii-ui/yii2-rubaxa-sortable
 * Create reorderable drag-and-drop lists for modern browsers and touch devices.
 *
 * @see https://github.com/RubaXa/Sortable
 * @author Christoph Moeke <christophmoeke@gmail.com>
 * @since 1.0.0
 */
class SortableAsset extends AssetBundle
{
    /***
     * @inheritdoc
     */
    public $sourcePath = '@bower/sortablejs';

    /**
     * Adds JS Files depending on [[YII_ENV_PROD]]
     */
    public function init()
    {
        parent::init();

        $this->js = [
            (YII_ENV_DEV?'Sortable.min.js':'Sortable.js'),
            'jquery.binding.js',
        ];
    }

    /***
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
