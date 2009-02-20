<?php
class forms_LoginForm extends Zend_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
        $this->setMethod('post')
	->setAttrib('class', 'loginform')
	->addElementPrefixPath('CMS_Decorator','CMS/Decorator/','decorator')
	->setElementDecorators(array('Composite'))
	->setDecorators(array('FormElements',array('HtmlTag',array('tag' => '<div>')),'Form',));
	$frontController = Zend_Controller_Front::getInstance();
	$submitLabel = $frontController->getBaseUrl().'/public/images/layout/buttons/login.gif';
		
	$username = $this->createElement('text', 'username');
	$username->addValidator('stringLength', false, array(1, 255))
			->setRequired(true)
			->setLabel('Username')
			->setDecorators(array('Composite'));

	$password = $this->createElement('password', 'password');
	$password->addValidator('StringLength', false, array(6))
			->setRequired(true)
			->setLabel('Password')
			->setDecorators(array('Composite'));

	$submit = $this->createElement('image','submit',array('src'=>$submitLabel, 'id'=>'loginSubmit'));
	$submit->setDecorators(array(array('ViewHelper'),array('Errors'),array('HtmlTag', array('tag' => 'div', 'class'=>'submit'))));

	$this->addElements(array($username,$password,$submit));
    }
}
?>