<?php

/** Zend_Form_Decorator_Abstract */
require_once 'Zend/Form/Decorator/Abstract.php';

/**
 * Wraps the content (radio items) in a <div>.
 */
class App_Form_Decorator_RadioWrapper extends Zend_Form_Decorator_Abstract
{
    /**
     * Default placement: surround content
     * @var string
     */
    protected $_placement = null;

    /**
     * Render
     *
     * Renders as the following:
     * <div class="radioWrapper"></div>
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        
        return '<div class="radioWrapper">' . $content . '</div>';
    }
}
