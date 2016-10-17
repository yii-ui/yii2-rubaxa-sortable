<?php
/**
 * @link http://www.yii-ui.com/
 * @copyright Copyright (c) Christoph Moeke, Yii UI, 2016
 * @license http://www.yii-ui.com/license/
 */

namespace yiiui\rubaxasortable;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Create reorderable drag-and-drop lists for modern browsers and touch devices.
 *
 * For example:
 *
 * ```php
 * echo Sortable::widget([
 *     'items' => [
 *         'Item 1',
 *         ['content' => 'Item2'],
 *         [
 *             'content' => 'Item3',
 *             'options' => ['class' => 'text-danger'],
 *         ],
 *     ],
 *     'containerOptions' => ['class' => 'list-group'],
 *     'itemOptions' => ['class' => 'list-group-item'],
 *     'clientOptions' => ['animation' => 0],
 * ]);
 * ```
 *
 * @see https://github.com/RubaXa/Sortable
 * @author Christoph Moeke <christophmoeke@gmail.com>
 * @since 1.0.0
 */
class Sortable extends Widget
{
    /**
     * Option to display the sortable widget with custom elements and styles
     */
    const TYPE_CUSTOM = 0;

    /**
     * Option to display the sortable widget as bootstrap list-group.
     */
    const TYPE_BS_LIST = 1;

    /**
     * @var int the display type of the sortable widget.
     * Should be set with on of the [[Sortable::TYPE]] constants.
     */
    public $type = self::TYPE_BS_LIST;

    /***
     * @inheritDoc
     */
    public static $autoIdPrefix = 'YIIUIRS';

    /**
     * @var string the tag name for the container element.
     */
    public $containerElement = '';

    /**
     * @var array the HTML attributes for the container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $containerOptions = [];

    /**
     * @var string the tag name for the item element. This will be overwritten
     * by the "element" set in individual [[items]].
     */
    public $itemElement = '';

    /**
     * @var array the HTML attributes for the item tag. This will be overwritten
     * by the "options" set in individual [[items]].
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $itemOptions = [];

    /**
     * @var array list of sortable items. Each item can be a string representing the item content
     * or an array of the following structure:
     *
     * ~~~
     * [
     *     'content' => 'item content',
     *     // the HTML attributes of the item tag. This will overwrite "itemOptions".
     *     'options' => [],
     *     // the tag name for the item element. This will overwrite "itemElement".
     *     'element' => 'li',
     *     // add handle to the item. This will overwrite "addHandle".
     *     'addHandle' => true,
     *     // the handle label. This will overwrite "handleLabel".
     *     'handleLabel' => '::',
     *     // the tag name for the handle element. This will overwrite "handleElement".
     *     'handleElement' => 'span',
     *     // the HTML attributes for the handle tag. This will overwrite "handleOptions".
     *     'handleOptions' => []
     * ]
     * ~~~
     */
    public $items = [];

    /**
     * @var bool disable items by default. This will be overwritten
     * by the "disabled" set in individual [[items]].
     */
    public $disabled = false;

    /**
     * @var string css class to style disabled items.
     */
    public $disabledClass = 'disabled';

    /**
     * @var string the handle label, this is not HTML encoded. This will be overwritten
     * by the "handleLabel" set in individual [[items]].
     */
    public $handleLabel = '::';

    /**
     * @var bool add handle to each item. This will be overwritten
     * by the "addHandle" set in individual [[items]].
     */
    public $addHandle = false;

    /**
     * @var string the tag name for the handle element. This will be overwritten
     * by the "handleElement" set in individual [[items]].
     */
    public $handleElement = 'span';

    /**
     * @var array the HTML attributes for the handle tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $handleOptions = [];

    /**
     * @var array the options for the underlying RubaXa Sortable widget.
     * Please refer to the RubaXa Sortable Documentation for possible options.
     * @see https://github.com/RubaXa/Sortable#options
     */
    public $clientOptions = [];

    /**
     * @var array the event handlers for the underlying sortable widget.
     *
     * For example you could write the following in your widget configuration:
     *
     * ```php
     * 'clientEvents' => [
     *     'move' => 'function () { alert("element has been moved"); }'
     * ],
     * ```
     * @see https://github.com/RubaXa/Sortable#options
     */
    public $clientEvents = [];

    /**
     * @var array List of available events
     * @see https://github.com/RubaXa/Sortable#options
     */
    private $_availableClientEvents = ['choose', 'start', 'end', 'add', 'update', 'sort', 'remove', 'filter', 'move', 'clone'];

    /***
     * @inheritDoc
     */
    public function run()
    {
        if (empty($this->containerOptions['id'])) {
            $this->containerOptions['id'] = $this->getId();
        }

        $id = $this->containerOptions['id'];

        $this->initClientOptions();

        echo Html::beginTag($this->containerElement, $this->containerOptions);
        echo $this->renderItems();
        echo Html::endTag($this->containerElement);

        $this->registerClientWidget($id);
        $this->registerClientEvents($id);
    }

    /**
     * Initializes the client widget options.
     */
    protected function initClientOptions()
    {
        if ($this->addHandle || $this->itemHasEnabledOption('addHandle')) {
            if (empty($this->clientOptions['handle'])) {
                $this->clientOptions['handle'] = '.rubaxa-sortable-handle';
            }
        }

        if ($this->disabled || $this->itemHasEnabledOption('disabled')) {
            if (empty($this->clientOptions['filter'])) {
                $this->clientOptions['filter'] = '.rubaxa-sortable-disabled';
            }
        }

        switch ($this->type) {
            case self::TYPE_BS_LIST:
                Html::addCssClass($this->containerOptions, 'list-group');

                if (empty($this->containerElement)) {
                    $this->containerElement = 'ul';
                }

                if (empty($this->itemElement)) {
                    $this->itemElement = 'li';
                }
                break;
        }

        if (empty($this->containerElement)) {
            $this->containerElement = 'div';
        }

        if (empty($this->itemElement)) {
            $this->itemElement = 'div';
        }
    }

    /**
     * Check if there is any item which has the passed option enabled
     *
     * @param string $option key name of the array element
     * @return bool
     */
    protected function itemHasEnabledOption($option)
    {
        foreach ($this->items as $item) {
            if (ArrayHelper::getValue($item, $option, false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Registers a sortable widget
     * @param string $id the ID of the widget
     */
    protected function registerClientWidget($id)
    {
        $clientOptions = Json::htmlEncode($this->clientOptions);
        SortableAsset::register($this->getView());

        $this->getView()->registerJs('jQuery(\'#'.$id.'\').sortable('.$clientOptions.');');
    }

    /**
     * Registers sortable widget events
     * @param string $id the ID of the widget
     * @throws InvalidConfigException if `$clientEvents` array contains an unsupported event name.
     */
    protected function registerClientEvents($id)
    {
        if (!empty($this->clientEvents)) {
            $js = [];

            foreach ($this->clientEvents as $event => $handler) {
                if (isset($this->_availableClientEvents[$event])) {
                    $js[] = "jQuery('#$id').on('$event', $handler);";
                } else {
                    throw new InvalidConfigException('Unknow event "'.$event.'".');
                }
            }

            $this->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }

    /**
     * Renders the item list of the sortable container as specified on [[items]].
     * @return string the rendering result.
     */
    private function renderItems()
    {
        $items = '';

        foreach ($this->items as $item) {
            $itemOptions = ArrayHelper::merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));

            if (ArrayHelper::getValue($item, 'disabled', $this->disabled)) {
                Html::addCssClass($itemOptions, [substr($this->clientOptions['filter'], 1), $this->disabledClass]);
            }

            switch ($this->type) {
                case self::TYPE_BS_LIST:
                    Html::addCssClass($itemOptions, 'list-group-item');
                    break;
            }

            $addHandle = ArrayHelper::getValue($item, 'addHandle', $this->addHandle);

            if ($addHandle) {
                $handleOptions = ArrayHelper::merge($this->handleOptions, ArrayHelper::getValue($item, 'handleOptions', []));

                Html::addCssClass($handleOptions, substr($this->clientOptions['handle'], 1));

                $handleElement = ArrayHelper::getValue($item, 'handleElement', $this->handleElement);
                $handleLabel = ArrayHelper::getValue($item, 'handleLabel', $this->handleLabel);
                $content = Html::tag($handleElement, $handleLabel, $handleOptions);
            } else {
                $content = '';
            }

            if (is_array($item)) {
                $content .= ArrayHelper::getValue($item, 'content', '');
            } else {
                $content .= $item;
            }

            $element = ArrayHelper::getValue($item, 'element', $this->itemElement);
            $items .= Html::tag($element, $content, $itemOptions) . PHP_EOL;
        }
        return $items;
    }
}
