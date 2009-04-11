<?php
/**
 * Strips all necessery characters from html
 */
class App_Filter_Compressor_Html implements Zend_Filter_Interface
{
    /**
     * Blocks of <script> tags
     *
     * @var array
     */
    protected $_scriptBlocks = array();

    /**
     * Blocks of <style> tags
     *
     * @var array
     */
    protected $_styleBlocks = array();

    /**
     * Blocks of <textarea> tags
     *
     * @var array
     */
    protected $_textareaBlocks = array();

    /**
     * Blocks of <pre> tags
     *
     * @var array
     */
    protected $_preBlocks = array();

    /**
     * Instance of CSS compressor
     *
     * @var App_Filter_Compressor_Css
     */
    protected $_styleFilter;

    /**
     * Instance of Javascript compressor
     *
     * @var App_Filter_Compressor_Js
     */
    protected $_javascriptFilter;


    public function __construct($filterCss = null, $filterJs = null)
    {
        $this->_filterCss = ($filterCss !== null) ? $filterCss : false;
        $this->_filterJs = ($filterJs !== null) ? $filterJs : false;

        if ($filterCss) {
            $this->_styleFilter = new App_Filter_Compressor_Css();
        }

        if ($filterJs) {
            $this->_javascriptFilter = new App_Filter_Compressor_Js();
        }
    }

    public function filter($value)
    {
        // clear blocks array
        $this->_protectedBlocks = array();

		// Pull out the script blocks
        $value = preg_replace_callback(
            array(
                '!(<script[^>]*>)(.*?)</script>!is',
                '!(<style[^>]*>)(.*?)</style>!is',
                '!(<pre[^>]*>)(.*?)</pre>!is',
                '!(<textarea[^>]*>)(.*?)</textarea>!is'
            ),
            array(&$this, '_pullOutProtectedBlocks'),
            $value
        );

        $value = preg_replace(
                array(
                    '/\>\s*\</',
                    '/\<\!--.*?--\>/'
                ),
                array(
                    '><',
                    ''
                ),
                trim($value)
            );

        $value = preg_replace_callback(
            array(
                '/{{FILTER:TRIM:SCRIPT}}/',
                '/{{FILTER:TRIM:STYLE}}/',
                '/{{FILTER:TRIM:TEXTAREA}}/',
                '/{{FILTER:TRIM:PRE}}/'
            ),
            array(&$this, '_pushInProtectedBlocks'),
            $value
        );

        return $value;
    }

    /**
     * Callback that replace script, pre or textarea blocks with placeholder
     *
     * @param array $matches Array of matches from preg_replace_callback
     * @return string
     */
    function _pullOutProtectedBlocks($matches)
    {
        if (isset($matches[1])) {
            switch (strtolower(substr($matches[1], 0, 4))) {
                case '<pre':
                    array_push($this->_preBlocks, $matches[2]);
                    return $matches[1].'{{FILTER:TRIM:PRE}}'.'</pre>';

                case '<scr':
                    array_push($this->_scriptBlocks, $matches[2]);
                    return $matches[1].'{{FILTER:TRIM:SCRIPT}}'.'</script>';

                case '<sty':
                    $styleStr = ($this->_filterCss) ? $this->_styleFilter->filter($matches[2]) : $matches[2];
                    array_push($this->_styleBlocks, $styleStr);
                    return $matches[1].'{{FILTER:TRIM:STYLE}}'.'</style>';

                case '<tex':
                    array_push($this->_textareaBlocks, $matches[2]);
                    return $matches[1].'{{FILTER:TRIM:TEXTAREA}}'.'</textarea>';
            }
        }

        return $matches[0];
    }

    /**
     * Callback that replace placeholder with previously stored
     * script, pre or textarea blocks.
     *
     * @param array $matches Array of matches from preg_replace_callback
     * @return string
     */
    function _pushInProtectedBlocks($matches)
    {
        if (count($matches) > 0) {
            switch($matches[0]) {
                case '{{FILTER:TRIM:SCRIPT}}':
                    return array_shift($this->_scriptBlocks);
                case '{{FILTER:TRIM:STYLE}}':
                    return array_shift($this->_styleBlocks);
                case '{{FILTER:TRIM:TEXTAREA}}':
                    return array_shift($this->_textareaBlocks);
                case '{{FILTER:TRIM:PRE}}':
                    return array_shift($this->_preBlocks);
            }
        }

        return $matches[0];
	}

}