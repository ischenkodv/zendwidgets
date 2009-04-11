<?php
/**
 * Strips all necessery characters from javascript
 */
class App_Filter_Compressor_Js implements Zend_Filter_Interface
{

    public function filter($value)
    {


        return $value;
    }

}