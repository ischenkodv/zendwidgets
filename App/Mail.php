<?php
/* 
 * Fixed Zend_Mail that has fix for letters with cyrillic content
 */
class App_Mail extends Zend_Mail {

    /**
     * Encode header fields
     *
     * @param  string $value
     * @return string
     */
    protected function _encodeHeader($value)
    {
        if (Zend_Mime::isPrintable($value)) {
            return $value;
        } else {
            return '=?' . $this->_charset . '?B?' . base64_encode($value) . '?=';
        }
    }

}