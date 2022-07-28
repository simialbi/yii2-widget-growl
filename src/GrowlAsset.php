<?php
/**
 * @package yii2-widget-growl
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace simialbi\yii2\growl;

use simialbi\yii2\web\AssetBundle;

/**
 * Asset bundle for \simialbi\yii2\growl\Growl
 *
 * @package simialbi\yii2\growl
 */
class GrowlAsset extends AssetBundle
{
    /**
     * {@inheritDoc}
     */
    public $sourcePath = '@npm/bootstrap4-notify';
    /**
     * {@inheritDoc}
     */
    public $js = [
        'bootstrap-notify.min.js'
    ];
}
