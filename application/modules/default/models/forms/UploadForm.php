<?php
class forms_UploadForm extends Zend_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
        $this->setName('upload')
        	->setAttrib('enctype', 'multipart/form-data')
		->setAttrib('id', 'uploadForm')
		->addElementPrefixPath('CMS_Decorator','CMS/Decorator/','decorator')
		->setDecorators(array('FormElements',array('HtmlTag',array('tag' => '<div>')),'Form',));
	$frontController = Zend_Controller_Front::getInstance();
	$submitLabel =  $frontController->getBaseUrl().'/public/images/layout/buttons/upload.gif';

	$uploader = new Zend_Form_Element_File('uploader');
	$uploader->setLabel('Upload a file:')
		->setName('uploader')
		->setDestination(ROOT_DIR.'/public/files/uploaded')
		->addValidator('Size', false, 102400)
		->setDecorators(array('Composite'));

	// ensure max 3 files are uploaded
 	$uploader->addValidator('Count', false, array('min' => 1, 'max' => $this->max_allowed_file_uploads));

	// only JPEG, PNG, and GIFs
	// $element->addValidator('Extension', false, 'jpg,png,gif');

 	$uploader->setMultiFile(3);

	$submit = $this->createElement('image','submit',array('src'=>$submitLabel, 'id'=>'uploadSubmit'));
	$submit->setDecorators(array(array('ViewHelper'),array('Errors'),array('HtmlTag', array('tag' => 'div', 'class'=>'submit'))));
	$this->addElements(array(array($uploader,'uploader'),$submit));
    }
}
?>