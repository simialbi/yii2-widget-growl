<?php
/**
 * @package yii2-widget-growl
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\growl;


use simialbi\yii2\web\AnimationAsset;
use simialbi\yii2\widgets\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class Growl extends Widget
{
    const TYPE_INFO = 'info';
    const TYPE_DANGER = 'danger';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';

    /**
     * @var string the type of the alert to be displayed. One of the `TYPE_` constants.
     * Defaults to `TYPE_INFO`
     */
    public $type = self::TYPE_INFO;

    /**
     * @var string the class name for the icon
     */
    public $icon = '';

    /**
     * @var string the title for the alert
     */
    public $title = '';

    /**
     * @var string the url to redirect to on clicking the alert. If this is <code>null</code> or not set,
     * the alert will not be clickable.
     */
    public $linkUrl = '';

    /**
     * @var string the target to open the linked notification
     */
    public $linkTarget = '_blank';

    /**
     * @var string the alert message body
     */
    public $body = '';

    /**
     * @var array the HTML options and settings for the bootstrap progress bar. Defaults to:
     * ```
     *  [
     *      'role' => 'progressbar',
     *      'aria-valuenow' => '0',
     *      'aria-valuemin' => '0',
     *      'aria-valuemax' => '100',
     *      'style' => '100',
     *  ]
     * ```
     * The following special options are recognized:
     * - `title`: the progress bar title text/markup.
     */
    public $progressBarOptions = [];

    /**
     * @var integer the delay in microseconds after which the alert will be displayed.
     * Will be useful when multiple alerts are to be shown.
     */
    public $delay;

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    /**
     * @var boolean use animations
     */
    public $useAnimation = true;

    /**
     * @var array the HTML attributes for the growl icon container.
     */
    public $iconOptions = [];

    /**
     * @var array the HTML attributes for the growl title container.
     */
    public $titleOptions = [];

    /**
     * @var array the HTML attributes for the growl message body.
     */
    public $bodyOptions = [];

    /**
     * @var array the HTML attributes for the growl progress bar container.
     */
    public $progressContainerOptions = [];

    /**
     * @var array the HTML attributes for the growl url link
     */
    public $linkOptions = [];

    /**
     * @var array the bootstrap growl plugin configuration options
     * @see http://bootstrap-growl.remabledesigns.com/
     */
    public $clientOptions = [];

    /**
     * @var array the first part of growl plugin settings/options
     */
    private $_settings;

    /**
     * Initializes the widget
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->_settings = [
            'message' => $this->body,
            'icon' => $this->icon,
            'title' => $this->title,
            'url' => $this->linkUrl,
            'target' => $this->linkTarget
        ];
        $this->progressBarOptions += [
            'role' => 'progressbar',
            'aria-valuenow' => '0',
            'aria-valuemin' => '0',
            'aria-valuemax' => '100',
            'style' => [
                'width' => '100%'
            ]
        ];
        $this->clientOptions['type'] = $this->type;
        $class = 'progress';
        $progressTitle = ArrayHelper::remove($this->progressBarOptions, 'title', '');
        Html::addCssClass($this->progressContainerOptions, $class);
        Html::addCssClass($this->progressBarOptions, 'progress-bar bg-{0}');
        $class = "alert alert-{0}";
        if (empty($this->options['class'])) {
            $this->options['class'] = "col-11 col-md-3 {$class}";
        } else {
            Html::addCssClass($this->options, $class);
        }
        $this->options['role'] = 'alert';
        $this->options['data-notify'] = 'container';
        $this->iconOptions['data-notify'] = 'icon';
        $this->titleOptions['data-notify'] = 'title';
        $this->bodyOptions['data-notify'] = 'message';
        $this->progressContainerOptions['data-notify'] = 'progressbar';
        $this->linkOptions['data-notify'] = 'url';
        $this->linkOptions['target'] = '{4}';
        $iconTag = ArrayHelper::getValue($this->clientOptions, 'icon_type', 'class') === 'class' ? 'span' : 'img';
        $content = $this->renderCloseButton() . "\n" .
            Html::tag($iconTag, '', $this->iconOptions) . "\n" .
            Html::tag('span', '{1}', $this->titleOptions) . "\n" .
            Html::tag('span', '{2}', $this->bodyOptions) . "\n" .
            Html::tag('div', Html::tag('div', $progressTitle, $this->progressBarOptions),
                $this->progressContainerOptions) . "\n" .
            Html::a('', '{3}', $this->linkOptions);
        $this->clientOptions['template'] = Html::tag('div', $content, $this->options);
        $this->registerPlugin('notify');
    }

    /**
     * Renders the close button.
     *
     * @return string the rendering result
     */
    protected function renderCloseButton()
    {
        if ($this->closeButton !== null) {
            $tag = ArrayHelper::remove($this->closeButton, 'tag', 'button');
            $label = ArrayHelper::remove($this->closeButton, 'label', '&times;');
            $label = '<span aria-hidden="true">' . $label . '</span>';
            Html::addCssClass($this->closeButton, 'close');
            if ($tag === 'button' && !isset($this->closeButton['type'])) {
                $this->closeButton['type'] = 'button';
            }
            $this->closeButton['data-notify'] = 'dismiss';
            return Html::tag($tag, $label, $this->closeButton);
        } else {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function registerPlugin($pluginName = null)
    {
        GrowlAsset::register($this->view);

        if ($this->useAnimation) {
            AnimationAsset::register($this->view);
        }

        $js = "jQuery.$pluginName(" . Json::encode($this->_settings) . ", " . Json::encode($this->clientOptions) . ");";
        if (!empty($this->delay) && $this->delay > 0) {
            $js = "setTimeout(function () { $js }, {$this->delay});";
        }

        $this->view->registerJs($js);
    }
}