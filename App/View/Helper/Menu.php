<?php
/* 
 * Menu View Helper
 */
class App_View_Helper_Menu extends Zend_View_Helper_Placeholder_Container_Standalone {

    /**
     * @var string registry key
     */
    protected $_regKey = 'App_View_Helper_Menu';

    /**
     * Menu items.
     * Array can be as follows:
     *  array(
     *      'menu name' => array(
     *          item1, item2, item3
     *      ),
     *      ...
     *  )
     *
     * @var array
     */
    protected $_items = array();

    /**
     * Variable that shows if menu text should be escaped
     * 
     * @var boolean
     */
    protected $_escape = true;

    /**
     * Name of the current menu
     *
     * @var string
     */
    protected $_currentMenu;

    /**
     * menu() - View Helper Method
     *
     * Returns current object instance. Must have the name of the menu
     *
     * @return Zend_View_Helper_View
     */
    public function menu($menuName)
    {
        $this->_currentMenu = ($menuName);

        if (!array_key_exists($this->_currentMenu, $this->_items)) {
            $this->_items[$menuName] = array();
        }

        return $this;
    }

    public function addItem($text, $link = null, $selected = false, $linkAttr = null)
    {
        if (!$this->_currentMenu) {
            return $this;
        }

        $item = new stdClass();
        $item->text = $text;
        $item->link = $link;
        $item->selected = $selected;
        $item->link_attr = $linkAttr;

        array_push($this->_items[$this->_currentMenu], $item);

        return $this;
    }

    public function clear()
    {
        $this->_items = array();

        return $this;
    }

    public function setEscape($escape)
    {
        $this->_escape = $escape;

        return $this;
    }

    public function setId($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * Convert object to string
     * 
     * @return string
     */
    public function toString()
    {
        $result = '';

        if (!$this->_currentMenu) {
            return $result;
        }

        if (array_key_exists($this->_currentMenu, $this->_items)) {

            $result .= '<ul';

            if ($this->_id) {
                $result .= ' id="'.$this->_id.'"';
            }

            $result .= '>';

            foreach ($this->_items[$this->_currentMenu] as $item) {
                $result .= $this->_renderItem($item);
            }

            $result .= '</ul>';

        }

        return $result;
    }

    /**
     * Render menu item
     * 
     * @param object $item
     * @return string
     */
    private function _renderItem($item)
    {
        if (!$item) {
            return '';
        }

        $result = '<li';

        $classes = array();

        if ($item->class) {
            array_push($classes, $item->class);
        }

        if ($item->selected) {
            array_push($classes, 'selected');
        }

        if (count($classes))
            $result .= ' class="'.implode(' ', $classes).'"';

        $result .= '>';

        if ($item->link) {
            $result .= '<a href="'.$item->link.'"';

            if (is_array($item->link_attr)) {
                foreach ($item->link_attr as $key=>$value) {
                    $name = (string) $key;
                    $result .=  " $name=\"$value\"";
                }
            }

            $result .= '>';
        }

        if ($this->_escape) {
            $result .= htmlentities($item->text, ENT_QUOTES, 'UTF-8');
        }
        else {
            $result .= $item->text;
        }

        if ($item->link) {
            $result .= '</a>';
        }
        $result .= '</li>';

        return $result;
    }

}
