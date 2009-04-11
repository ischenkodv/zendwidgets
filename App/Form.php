<?php
/* 
 * Базовая форма для всех форм
 */
class App_Form extends Zend_Form {

    /**
     * Shows if translator is defined
     *
     * @var boolean
     */
    static protected $_isTranslatorDefined = false;

    public function __construct()
    {
        parent::__construct();

        self::initTranslator();

        $this->addElementPrefixPath('App_Form_Decorator', 'App/Form/Decorator', 'decorator');
        $this->addElementPrefixPath('App_Validate', 'App/Validate', 'validate');


        // ***********************************************
        // set common decorators for all elements and form
        $htmlTag = new Zend_Form_Decorator_HtmlTag();
        $htmlTag->setTag('div')
                ->setOptions(array('class'=>'formElement'));

        $errorsDecorator = new Zend_Form_Decorator_Errors(array('placement' => 'prepend'));
        //$errorsDecorator->setOption();

        $this->setElementDecorators(array('ViewHelper', 'Label', $errorsDecorator, $htmlTag));
        $this->addDecorator('FormElements')
             ->addDecorator('Form')
             ->removeDecorator('HtmlTag');

        $elements = $this->getElements();

        // **********************************************
        // assign decorators depening on the element type
        while ($element = array_shift($elements)) {

            switch ($element->getType()) {
                case 'Zend_Form_Element_Radio':
                    $element->clearDecorators()
                            ->addDecorators(array(
                                'ViewHelper',
                                'RadioWrapper',
                                'Label',
                                $errorsDecorator,
                                $htmlTag
                            ));

                    break;

                case 'Zend_Form_Element_Submit':
                case 'Zend_Form_Element_Reset':
                    // set default class name for buttons
                    if (!$element->getAttrib('class')) {
                        $element->setAttrib('class','button');
                    }
                case 'Zend_Form_Element_Hidden':
                    $element->removeDecorator('Label');
                    $element->removeDecorator('Errors');
                    break;
            }
        }

    }

    static public function initTranslator($lang = 'ru')
    {
        if (!self::$_isTranslatorDefined) {
            // ****************************** //
            //  set default form translator   //
            $translate = new Zend_Translate(
                'array',
                array(
                    Zend_Validate_EmailAddress::INVALID            => "значение '%value%' не является действительным email адресом",
                    Zend_Validate_EmailAddress::INVALID_HOSTNAME   => "'%hostname%' не является действительным значением для email адреса '%value%'",
                    Zend_Validate_EmailAddress::INVALID_MX_RECORD  => "'%hostname%' не является действительной записью MX для email адреса '%value%'",
                    Zend_Validate_EmailAddress::DOT_ATOM           => "'%localPart%' не соответствует формату dot-atom",
                    Zend_Validate_EmailAddress::QUOTED_STRING      => "'%localPart%' не соответствует формату экранированной строки",
                    Zend_Validate_EmailAddress::INVALID_LOCAL_PART => "'%localPart%' является недействительной локальной частью email адреса '%value%'",
                    Zend_Validate_EmailAddress::LENGTH_EXCEEDED    => "'%value%' превышает допустимую длину",
                    Zend_Validate_StringLength::TOO_SHORT => 'Введите не менее \'%value%\' символов',
                    Zend_Validate_NotEmpty::IS_EMPTY => 'Это поле обязательно для заполнения',

                    // 'identical' validator
                    Zend_Validate_Identical::MISSING_TOKEN => 'Не передано значение для проверки идентичности',
                    Zend_Validate_Identical::NOT_SAME => 'Введенные значения не соответствуют друг другу'
                ),
                $lang
            );
            Zend_Validate_Abstract::setDefaultTranslator($translate);

            self::$_isTranslatorDefined = true;
        }
    }
}