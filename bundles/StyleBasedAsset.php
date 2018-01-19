<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o. 
 * @license http://www.digitaldeals.cz/license/
 */

namespace iamok\metronic\bundles;

use yii\helpers\ArrayHelper;
use iamok\metronic\Metronic;

class StyleBasedAsset extends BaseAssetBundle {

    /**
     * @var array depended bundles
     */
    public $depends = [
        'iamok\metronic\bundles\CoreAsset',
    ];

    /**
     * @var array css assets
     */
    public $css = [];

    /**
     * @var array style based css
     */
    private $styleBasedCss = [
        Metronic::THEME_STYLE_SQUARE => [
            'global/css/components.css',
            'global/css/plugins.css',
        ],
        Metronic::THEME_STYLE_ROUNDED => [
            'global/css/components-rounded.css',
            'global/css/plugins.css',
        ],
        Metronic::THEME_STYLE_MATERIAL => [
            'global/css/components-md.css',
            'global/css/plugins-md.css',
        ]
    ];

    /**
     * Inits bundle
     */
    public function init()
    {
        $this->_handleStyleBased();

        return parent::init();
    }

    /**
     * Handles style based files
     */
    private function _handleStyleBased()
    {
        if (Metronic::getComponent())
        {
            $css = $this->styleBasedCss[Metronic::getComponent()->themeStyle];
            $this->css = ArrayHelper::merge($css, $this->css);
        }
    }
}