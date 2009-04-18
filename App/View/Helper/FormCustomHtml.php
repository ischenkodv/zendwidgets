<?php
/**
 * Custom html element. Allows to add arbitrary html inside form.
 */
class App_View_Helper_FormCustomHtml extends Zend_View_Helper_FormElement
{
    /**
     * Helper to show arbitrary html inside form
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     * @return string The element XHTML.
     */
    public function formCustomHtml($name, $value = null, $options = null, $attribs = null)
    {
        return $options['html'];
    }

}