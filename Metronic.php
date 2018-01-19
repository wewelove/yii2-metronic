<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace iamok\metronic;

use iamok\metronic\bundles\IonRangeSliderAsset;
use iamok\metronic\traits\HtmlTrait;
use Yii;
use yii\helpers\Html;
use yii\web\AssetBundle;
use yii\base\InvalidConfigException;
use iamok\metronic\bundles\ThemeAsset;

/**
 * This is the class of Metronic Component
 */
class Metronic extends \yii\base\Component
{

    /**
     * @var AssetBundle
     */
    public static $assetsBundle;

    /**
     * Assets link
     */
    const ASSETS_LINK = __DIR__ . '/assets';

    /**
     * Classes paths
     */
    const CLASS_HTML = '@vendor/iamok/yii2-metronic/helpers/Html.php';

    /**
     * Search string
     */
    const PARAM_LAYOUT = '{layout}';
    const PARAM_THEME_COLOR = '{themeColor}';

    /**
     * @var string layout
     */
    const LAYOUT_1 = 'layout';
    const LAYOUT_2 = 'layout2';
    const LAYOUT_3 = 'layout3';
    const LAYOUT_4 = 'layout4';
    const LAYOUT_5 = 'layout5';
    const LAYOUT_6 = 'layout6';
    const LAYOUT_7 = 'layout7';

    public $layout = self::LAYOUT_1;

    /**
     * @var string Layout mode
     */
    const LAYOUT_MODE_FLUID = 'default';
    const LAYOUT_MODE_BOXED = 'boxed';

    public $layoutMode = self::LAYOUT_MODE_FLUID;

    /**
     * @var string Theme color
     */
    const THEME_COLOR_DARK = 'default';
    const THEME_COLOR_LIGHT = 'light';

    const THEME_COLOR_BLUE = 'blue';
    const THEME_COLOR_BLUE_HOKI = 'blue-hoki';
    const THEME_COLOR_BLUE_STEEL = 'blue-steel';
    const THEME_COLOR_BLUE_MADISON = 'blue-madison';
    const THEME_COLOR_BLUE_CHAMBRAY = 'blue-chambray';
    const THEME_COLOR_BLUE_EBONYCLAY = 'blue-ebonyclay';

    const THEME_COLOR_GREEN = 'green';
    const THEME_COLOR_GREEN_MEADOW = 'green-meadow';
    const THEME_COLOR_GREEN_SEAGREEN = 'green-seagreen';
    const THEME_COLOR_GREEN_TORQUOISE = 'green-torquoise';
    const THEME_COLOR_GREEN_JUNGLE = 'green-jungle';
    const THEME_COLOR_GREEN_HAZE = 'green-haze';

    const THEME_COLOR_RED = 'red';
    const THEME_COLOR_RED_PINK = 'red-pink';
    const THEME_COLOR_RED_SUNGLO = 'red-sunglo';
    const THEME_COLOR_RED_INTENSE = 'red-intense';
    const THEME_COLOR_RED_THUNDERBIRD = 'red-thunderbird';
    const THEME_COLOR_RED_FLAMINGO = 'red-flamingo';
    const THEME_COLOR_RED_HAZE = 'red-haze';

    const THEME_COLOR_YELLOW = 'yellow';
    const THEME_COLOR_YELLOW_GOLD = 'yellow-gold';
    const THEME_COLOR_YELLOW_CASABLANCA = 'yellow-casablanca';
    const THEME_COLOR_YELLOW_CRUSTA = 'yellow-crusta';
    const THEME_COLOR_YELLOW_LEMON = 'yellow-lemon';
    const THEME_COLOR_YELLOW_SAFFRON = 'yellow-saffron';

    const THEME_COLOR_PURPLE = 'purple';
    const THEME_COLOR_PURPLE_PLUM = 'purple-plum';
    const THEME_COLOR_PURPLE_MEDIUM = 'purple-medium';
    const THEME_COLOR_PURPLE_STUDIO = 'purple-studio';
    const THEME_COLOR_PURPLE_WISTERIA = 'purple-wisteria';
    const THEME_COLOR_PURPLE_SEANCE = 'purple-seance';

    const THEME_COLOR_GREY = 'grey';
    const THEME_COLOR_GREY_CASCADE = 'grey-cascade';
    const THEME_COLOR_GREY_SILVER = 'grey-silver';
    const THEME_COLOR_GREY_STEEL = 'grey-steel';
    const THEME_COLOR_GREY_CARARRA = 'grey-cararra';
    const THEME_COLOR_GREY_GALLERY = 'grey-gallery';

    public $themeColor = self::THEME_COLOR_LIGHT;

    /**
     * @var string Theme style
     */
    const THEME_STYLE_SQUARE = 'default';
    const THEME_STYLE_ROUNDED = 'rounded';
    const THEME_STYLE_MATERIAL = 'material';

    public $themeStyle = self::THEME_STYLE_SQUARE;

    /**
     * @var string Header mode
     */
    const HEADER_MODE_DEFAULT = 'default';
    const HEADER_MODE_FIXED = 'fixed';

    public $headerMode = self::HEADER_MODE_FIXED;

    /**
     * @var string Header dropdowns
     */
    const HEADER_DROPDOWN_DARK = 'dark';
    const HEADER_DROPDOWN_LIGHT = 'light';

    public $headerDropdown = self::HEADER_DROPDOWN_DARK;

    /**
     * @var string Sidebar mode
     */
    const SIDEBAR_MODE_DEFAULT = 'default';
    const SIDEBAR_MODE_FIXED = 'fixed';

    public $sidebarMode = self::SIDEBAR_MODE_DEFAULT;

    /**
     * @var string Sidebar menu
     */
    const SIDEBAR_MENU_ACCORDION = 'accordion';
    const SIDEBAR_MENU_HOVER = 'hover';

    public $sidebarMenu = self::SIDEBAR_MENU_ACCORDION;

    /**
     * @var string Sidebar style
     */
    const SIDEBAR_STYLE_DEFAULT = 'default';
    const SIDEBAR_STYLE_LIGHT = 'light';

    public $sidebarStyle = self::SIDEBAR_STYLE_DEFAULT;

    /**
     * @var string Sidebar position
     */
    const SIDEBAR_POSITION_LEFT = 'left';
    const SIDEBAR_POSITION_RIGHT = 'right';

    public $sidebarPosition = self::SIDEBAR_POSITION_LEFT;

    /**
     * @var string Footer mode
     */
    const FOOTER_MODE_DEFAULT = 'defalut';
    const FOOTER_MODE_FIXED = 'fixed';

    public $footerMode = self::FOOTER_MODE_DEFAULT;

    /** @var string IonRangeSlider skin */
    public $ionSliderSkin = IonRangeSliderAsset::SKIN_SIMPLE;

    /**
     * @var array resources paths
     */
    public $resources;

    /**
     * @var string Component name used in the application
     */
    public static $componentName = 'metronic';

    /**
     * Inits module
     */
    public function init()
    {
        if (!$this->resources) {
            throw new InvalidConfigException('You have to specify resources locations to be able to create symbolic links. Specify "admin" and "global" theme folder locations.');
        }

        if (!is_link(self::ASSETS_LINK) && !is_dir(self::ASSETS_LINK)) {
            symlink($this->resources, self::ASSETS_LINK);
        }

        if (self::SIDEBAR_MODE_FIXED === $this->sidebarMode) {
            $this->sidebarMenu = self::SIDEBAR_MENU_HOVER;
        }

    }

    public function parseAssetsParams(&$string)
    {
        if (preg_match('/\{[a-z]+\}/', $string)) {
            $string = str_replace(static::PARAM_LAYOUT, $this->layout, $string);

            $string = str_replace(static::PARAM_THEME_COLOR, $this->themeColor, $string);
        }
    }

    /**
     * @return Metronic Get Metronic component
     */
    public static function getComponent()
    {
        try {
            return \Yii::$app->get(static::$componentName);
        } catch (InvalidConfigException $ex) {
            return null;
        }
    }

    /**
     * Get base url to metronic assets
     * @param $view View
     * @return string
     */
    public static function getAssetsUrl($view)
    {
        if (static::$assetsBundle === null) {
            static::$assetsBundle = static::registerThemeAsset($view);
        }

        return (static::$assetsBundle instanceof AssetBundle) ? static::$assetsBundle->baseUrl : '';
    }

    /**
     * Register Theme Asset
     * @param $view View
     * @return AssetBundle
     */
    public static function registerThemeAsset($view)
    {
        return static::$assetsBundle = ThemeAsset::register($view);
    }
}