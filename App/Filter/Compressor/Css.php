<?php
/**
 * Strips all necessery characters from CSS
 */
class App_Filter_Compressor_Css implements Zend_Filter_Interface
{

    public function filter($value)
    {
		$value = preg_replace(
                    array(
                        '/;?[\s\n\t]*([{}:;,])[\s\n\t]*/',
                        '/\/\*.*?\*\//'
                    ),
                    array(
                        '\1',
                        ''
                    ),
                    trim($value)
                );

        return $value;
    }

}