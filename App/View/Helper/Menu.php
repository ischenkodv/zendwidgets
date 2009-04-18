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

    public function  __construct()
    {
        parent::__construct();
        
        $this->setPrefix('<ul>');
        $this->setPostfix('</ul>');
    }

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

    /**
     * Add new menu item in the current menu
     *
     * @param string $text
     * @param string $link
     * @param boolean $selected
     * @param array> $linkAttr
     * @return Zend_View_Helper_View
     */
    public function addItem($text, $link = null, $selected = false, $linkAttr = null)
    {
        if (!$this->_currentMenu) {
            return $this;
        }

        array_push(
            $this->_items[$this->_currentMenu],
            $this->_createItem($text, $link, $selected, $linkAttr)
        );

        return $this;
    }

    protected function _createItem($text, $link, $selected = false, $linkAttr = null)
    {
        $item = new stdClass();
        $item->text = $text;
        $item->link = $link;
        $item->selected = $selected;
        $item->link_attr = $linkAttr;

        return $item;
    }

    public function addSubmenu(array $subItems)
    {
        if (!$this->_currentMenu) {
            return $this;
        }

        $items = array();

        foreach ($subItems as $i) {
            array_push(
                $items,
                $this->_createItem($i[0], $i[1], $i[2], $i[3])
            );
        }

        $currentMenu = $this->_items[$this->_currentMenu];
        $lastMenuItem = $currentMenu[count($currentMenu) - 1];
        $lastMenuItem->submenu = $items;

        return $this;
    }

    /**
     * Clear all menu items
     *
     * @return Zend_View_Helper_View
     */
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
        if (!$this->_currentMenu) {
            return '';
        }

        $result = $this->getPrefix();

        if (array_key_exists($this->_currentMenu, $this->_items)) {

            $items = array();
            foreach ($this->_items[$this->_currentMenu] as $item) {
                array_push($items, $this->_renderItem($item));
            }

            $result .= implode($this->getSeparator(), $items);
        }

        $result .= $this->getPostfix();

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

        // render submenu if it exists
        if (is_array($item->submenu)) {

            $result .= '<ul>';
            foreach ($item->submenu as $subItem) {
                $result .= $this->_renderItem($subItem);
            }
            $result .= '</ul>';

        }

        $result .= '</li>';

        return $result;
    }

}
