<?php
/**
 * Render layouts
 */
class App_Layout_Controller_Plugin_Layout extends Zend_Layout_Controller_Plugin_Layout
{

    /**
     * Work with layout content before post dispatch
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        parent::postDispatch($request);

        $response = $this->getResponse();


        $layout = $this->getLayout();

        if ($layout->getCompressHtml()) {

            $chain = new Zend_Filter();

            $htmlFilter = new App_Filter_Compressor_Html($layout->getCompressCss(), $layout->getCompressJs());
            $chain->addFilter($htmlFilter);

            $body = $chain->filter($response->getBody());
            $response->setBody($body);
        }

    }

}