<?php
/**
 * Breadcrumbs View Helper
 */
class App_View_Helper_BreadCrumb extends Zend_View_Helper_Placeholder_Container_Standalone
{

    /**
     * @var string registry key
     */
	protected $_regKey = 'App_View_Helpers_BreadCrumb';

    /**
     * List of breadcrumb items
     *
     * @var array
     */
	protected $_items = array();


    public function __construct()
    {
        parent::__construct();
        
        $this->setSeparator('&raquo;');
    }

    public function breadCrumb()
    {
    	return $this;
    }

    /**
     * Add new element to the breadcrumb
     *
     * @param string $label
     * @param string $url
     * @return App_View_Helper_BreadCrumb
     */
    public function addItem($label, $url = null)
    {
        if (!$label) {
            return $this;
        }

        $item = new stdClass();

        $element->label = $label;
        $element->url = $url;
       
        array_push($this->_items, $element);

        return $this;
    }

    /**
     * Clear all breadcrumb elements
     *
     * @return App_View_Helper_BreadCrumb
     */
    public function clear()
    {
        $this->_items = array();

        return $this;
    }

    /**
     * Render link elements as string
     *
     * @param string|int $indent
     * @return string
     */
    public function toString($indent = null)
    {
        $indent = (null !== $indent)
                ? $this->getWhitespace($indent)
                : $this->getIndent();

        $xhtml = $this->getPrefix();

        if (count($this->_items) > 0) {

            $items = array();

            foreach($this->_items as $item)
            {
                array_push($items, $this->_renderItem($item));
            }

            $xhtml .= join($this->getSeparator(), $items);
        }

        $xhtml .= $this->getPostfix();
        
        return $indent.$xhtml;
    }

    /**
     * Render html code for item
     *
     * @param object $item
     * @return string
     */
    protected function _renderItem($item)
    {
        if (!$item || !$item->label) {
            return '';
        }

        $result = '';

        if ($item->url) {
            $res .= '<a href="'.$item->url.'">';
        }

        $res .= $item->label;

        if ($item->url) {
            $res .= '</a>';
        }

        return $res;
    }
}
