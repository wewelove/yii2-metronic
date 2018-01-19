<?php

/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace iamok\metronic\widgets;

use iamok\metronic\Metronic;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\widgets\ActiveForm as CoreActiveForm;

/**
 * Metronic menu displays a multi-level menu using nested HTML lists.
 *
 * The main property of Menu is [[items]], which specifies the possible items in the menu.
 * A menu item can contain sub-items which specify the sub-menu under that menu item.
 *
 * Menu checks the current route and request parameters to toggle certain menu items
 * with active state.
 *
 * Note that Menu only renders the HTML tags about the menu. It does do any styling.
 * You are responsible to provide CSS styles to make it look like a real menu.
 *
 * The following example shows how to use Menu:
 *
 * ```php
 * echo Menu::widget([
 *     'items' => [
 *         // Important: you need to specify url as 'controller/action',
 *         // not just as 'controller' even if default action is used.
 *         [
 *           'icon' => '',
 *           'label' => 'Home',
 *           'url' => ['site/index']
 *         ],
 *         // 'Products' menu item will be selected as long as the route is 'product/index'
 *         ['label' => 'Products', 'url' => ['product/index'], 'items' => [
 *             ['label' => 'New Arrivals', 'url' => ['product/index', 'tag' => 'new']],
 *             ['label' => 'Most Popular', 'url' => ['product/index', 'tag' => 'popular']],
 *         ]],
 *         ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
 *     ],
 *     'search' => [
 *         // required, whether search box is visible. Defaults to 'true'.
 *         'visible' => true,
 *         // optional, the configuration array for [[ActiveForm]].
 *         'form' => [],
 *         // optional, input options with default values
 *         'input' => [
 *             'name' => 'search',
 *             'value' => '',
 *             'options' => [
 *             'placeholder' => 'Search...',
 *         ]
 *     ],
 * ]
 * ]);
 * ```
 *
 */
class Menu extends \yii\widgets\Menu {

    /**
     * @var array item options.
     */
    public $itemOptions = [
        'class' => 'nav-item'
    ];

    /**
     * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
     * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
     */
    public $activateParents = true;

    /**
     * @var string the CSS class that will be assigned to the active item in the main menu or each submenu.
     */
    public $activeCssClass = 'active open';

    /**
     * @var string the CSS class that will be assigned to the first item in the main menu.
     */
    public $firstItemCssClass = 'start';

    /**
     * @var string the CSS class that will be assigned to the last item in the main menu.
     */
    public $lastItemCssClass = 'last';
    
    /**
     * @var string the CSS class that will be assigned to the header item in the main menu.
     */
    public $headerCssClass = 'heading';

    /**
     * @var string the template used to render a list of sub-menus.
     * In this template, the token `{items}` will be replaced with the renderer sub-menu items.
     */
    public $submenuTemplate = "\n<ul class='sub-menu'>\n{items}\n</ul>\n";

    /**
     * @var string the template used to render the body of a menu which is a link.
     * In this template, the token `{url}` will be replaced with the corresponding link URL;
     * while `{label}` will be replaced with the link text.
     * The token `{icon}` will be replaced with the corresponding link icon.
     * The token `{arrow}` will be replaced with the corresponding link arrow.
     * This property will be overridden by the `template` option set in individual menu items via [[items]].
     */
    public $linkTemplate = '{icon}{label}{badge}{selected}{arrow}';

    /**
     * @var int icon level.
     */
    public $iconLevel = 2;
    
    /**
     * @var bool Indicates whether menu is visible.
     */
    public $visible = true;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        Metronic::registerThemeAsset($this->getView());

        $this->_initOptions();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::beginTag('div', ['class' => 'page-sidebar-wrapper']);
        echo Html::beginTag('div', ['class' => 'page-sidebar navbar-collapse collapse']);

        parent::run();

        echo Html::endTag('div');
        echo Html::endTag('div');
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @param integer $level the item level, starting with 1
     * @return string the rendering result
     */
    protected function renderItems($items, $level = 1)
    {
        $last  = count($items) - 1;
        $lines = [];

        foreach ($items as $i => $item) {

            $tag     = ArrayHelper::remove($options, 'tag', 'li');
            $header  = ArrayHelper::getValue($item, 'header', false);

            if ($header && $level === 1) {
                $options['class'] = $this->headerCssClass;
                $content = $this->renderHeader($item);
            } else {
                $menu    = '';
                $submenu = '';

                $options = ArrayHelper::merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
                $active  = ArrayHelper::getValue($item, 'active', false);
                $items   = ArrayHelper::getValue($item, 'items', []);
                $class   = isset($options['class']) ? [$options['class']] : [];
                
                if ($active) {
                    $class[] = $this->activeCssClass;
                }

                if ($level === 1 && $i === 0 && $this->firstItemCssClass !== null) {
                    $class[] = $this->firstItemCssClass;
                }

                if ($level === 1 && $i === $last && $this->lastItemCssClass !== null) {
                    $class[] = $this->lastItemCssClass;
                }

                $options['class'] = implode(' ', $class);

                $item['level'] = $level;
                $menu = $this->renderItem($item);

                if (!empty($items)) {
                    $submenu = strtr($this->submenuTemplate, [
                        '{items}' => $this->renderItems($items, $level + 1),
                    ]);
                }

                $content = $menu . $submenu;
            }

            $lines[] = Html::tag($tag, $content, $options);
        }

        return implode("\n", $lines);
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     */
    protected function renderItem($item)
    {
        $header = ArrayHelper::getValue($item, 'header', false);
        $level  = ArrayHelper::getValue($item, 'level', 1);
        $label  = ArrayHelper::getValue($item, 'label', '');
        $items  = ArrayHelper::getValue($item, 'items', []);

        $options['class'] = empty($items) ? 'nav-link' : 'nav-link nav-toggle';

        $text = strtr(ArrayHelper::getValue($item, 'template', $this->linkTemplate), [
            '{icon}' => $this->renderItemIcon($item),
            '{label}' => $this->renderItemLabel($item),
            '{badge}' => $this->renderItemBadge($item),
            '{selected}' => $this->renderItemSelected($item),
            '{arrow}' => $this->renderItemArrow($item),
        ]);

        $url = $this->renderItemUrl($item);
        
        return Html::a($text, $url, $options);
    }

    /**
     * Renders the content of a menu header.
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     */
    protected function renderHeader($item)
    {
        $label  = ArrayHelper::getValue($item, 'label', '');
 
        return Html::tag('h3', $label, ['class' => 'uppercase']);
    }

    /**
     * Renders out item url
     * @param array $item given item
     * @return string item url
     */
    private function renderItemUrl($item)
    {
        $url = ArrayHelper::getValue($item, 'url', '#');

        if ('#' === $url) {
            return 'javascript:;';
        }

        return Url::toRoute($item['url']);
    }

    /**
     * Renders out item label
     * @param array $item given item
     * @return string item label
     */
    private function renderItemLabel($item)
    {
        $label = ArrayHelper::getValue($item, 'label', '');

        return Html::tag('span', $label, ['class' => 'title']);  
    }

    /**
     * Renders out item icon
     * @param array $item given item
     * @return string item icon
     */
    private function renderItemIcon($item)
    {
        $icon = ArrayHelper::getValue($item, 'icon', null);
        $level = ArrayHelper::getValue($item, 'level', 1);

        if ($icon && $level <= $this->iconLevel) {
            return Html::tag('i', '', ['class' => $icon]);
        }

        return '';
    }

    /**
     * Renders out item arrow
     * @param array $item given item
     * @return string item arrow
     */
    private function renderItemArrow($item)
    {
        $items = ArrayHelper::getValue($item, 'items', []);

        if (empty($items)) {
            return '';
        }

        $active = ArrayHelper::getValue($item, 'active', false);
        $options['class'] = $active ? 'arrow open' : 'arrow';

        return Html::tag('span', '',  $options);
    }

    /**
     * Renders out item selected
     * @param array $item given item
     * @return string item selected
     */
    private function renderItemSelected($item)
    {
        $active = ArrayHelper::getValue($item, 'active', false);

        if ($active) {
            return Html::tag('span', '',  ['class' => 'selected']);
        }

        return '';
    }

    /**
     * Renders out item badge
     * @param array $item given item
     * @return string item badge
     */
    private function renderItemBadge($item)
    {
        return ArrayHelper::getValue($item, 'badge', '');
    }

    /**
     * Inits options
     */
    private function _initOptions()
    {
        Html::addCssClass($this->options, 'page-sidebar-menu');

        if (Metronic::getComponent() && Metronic::SIDEBAR_STYLE_LIGHT === Metronic::getComponent()->sidebarStyle) {
            Html::addCssClass($this->options, 'page-sidebar-menu-light');
        }

        if (Metronic::getComponent() && Metronic::SIDEBAR_MENU_HOVER === Metronic::getComponent()->sidebarMenu) {
            Html::addCssClass($this->options, 'page-sidebar-menu-hover-submenu');
        }

        $this->options['data-slide-speed'] = 200;
        $this->options['data-auto-scroll'] = 'true';
        $this->options['data-keep-expanded'] = 'false';

    }

}
