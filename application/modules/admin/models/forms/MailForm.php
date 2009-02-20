<?php
class forms_MailForm extends Zend_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
        $this->setMethod('post')
	->setAttrib('class', 'mailform')
	->setAttrib('id', 'mailForm')
	->addElementPrefixPath('CMS_Decorator','CMS/Decorator/','decorator')
	->setElementDecorators(array('Composite'))
	->setDecorators(array('FormElements',array('HtmlTag',array('tag' => '<div>')),'Form',));
	$frontController = Zend_Controller_Front::getInstance();
	$submitLabel = $frontController->getBaseUrl().'/public/images/layout/buttons/mail.gif';
	
	$subject = $this->createElement('text', 'subject');
	$subject->addValidator('stringLength', false, array(1, 255))
		->setRequired(true)
		->setLabel('Subject')
		->setDecorators(array('Composite'));

	$recipients = $this->createElement('textarea', 'recipients');
	$recipients->addValidator('StringLength', false, array(6))
		->setRequired(true)
		->setAttrib('id','recipients')
		->setLabel('Recipients')
		->setDecorators(array('Composite'));
		

	$submit = $this->createElement('image','submit',array('src'=>$submitLabel, 'id'=>'mailSubmit'));
	$submit->setDecorators(array(array('ViewHelper'),array('Errors'),array('HtmlTag', array('tag' => 'div', 'class'=>'submit'))));

	$this->addElements(array($subject,$recipients,$submit));
    }
}
?>