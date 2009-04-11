<?php
/**
 * Render layouts
 */
class App_Layout_Controller_Plugin_Layout extends Zend_Layout_Controller_Plugin_Layout
{

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        parent::postDispatch($request);

        $response = $this->getResponse();

        $chain = new Zend_Filter();
        $chain->addFilter(
                new App_Filter_Compressor_Html(true)
            );

        $body = $chain->filter($response->getBody());
        $response->setBody($body);
    }

}