<?php
	class IndexController extends Zend_Controller_Action
	{
		private $max_allowed_file_uploads = 3;

		public function indexAction()
		{
			$this->view->assign('title', 'Athlon Mailer');
			$this->view->mailform = new forms_MailForm();
			$this->view->uploadform = new forms_UploadForm();

			$this->view->uploadform->setAction($this->view->url(array('controller'=>'index','action'=>'upload'),'default'));
			$this->view->mailform->setAction($this->view->url(array('controller'=>'index','action'=>'send'),'default'));
			
			$this->view->assign('description', 'Organize your email testing');
			$this->view->headTitle($this->view->title);
			Zend_Loader::loadClass('Files');
			$files = new Files();
			$this->view->list = $files->listFiles('public/files/uploaded/');
		}

		public function uploadAction()
		{
			$form = new forms_UploadForm();
			if ($this->getRequest()->isPost())
			{
				$dType = $this->_getParam('dataType','html');
				$this->view->dType = $dType;
				if ($dType == 'json')
				{
					$this->_helper->layout->setLayout('json');
				}

				if ($form->isValid($_POST))
				{
					$adapter = new Zend_File_Transfer_Adapter_Http();
					$adapter->setDestination(ROOT_DIR.'/public/files/uploaded');
					
					if (!$adapter->receive())
					{
						$this->view->errors = implode("<br />",$adapter->getMessages());
						$this->view->message = '';
					}
					else
					{
						if ($dType == 'json')
						{
							$this->view->errors = '';
							$this->view->message = 'File uploaded successfully';
						}
						else
						{
							$redirector = $this->_helper->getHelper('Redirector');
							setGotoSimple("action","controller");
							$redirector->setCode(303)->setExit(false)->setGotoSimple("index","index");
							$redirector->redirectAndExit();
						}
					}
				}
				else
				{
					$this->view->errors = 'Error: Invalid form';
					$this->view->message = '';
				}
			}
			else
			{
				$redirector = $this->_helper->getHelper('Redirector');
				//setGotoSimple("action","controller");
				$redirector->setCode(303)->setExit(false)->setGotoSimple("index","index");
				$redirector->redirectAndExit();
			}
		}

		public function destroyAction()
		{
			$params = $this->_getAllParams();
			$exclude = array('controller','action','module');
			$counter = 0;
			
			foreach ($params as $name=>$value)
			{
				
				if (!in_array($name,$exclude))
				{
					$counter++;
					unlink(ROOT_DIR.'/public/files/uploaded/'.$value);
				}
			}
			$this->view->status = 'Success';
			$this->view->message = 'Operation completed successfully. '.$counter.' files have been destroyed';
		}

		public function listAction()
		{
			$format = $this->_getParam('format','ajax');
			switch ($format)
			{
				case 'xml': $this->_helper->layout->setLayout('xml'); $this->_helper->viewRenderer('list-xml'); break;
				default: $this->_helper->layout->setLayout('ajax');
			}
			Zend_Loader::loadClass('Files');
			$files = new Files();
			$this->view->list = $files->listFiles('public/files/uploaded/');
			
		}

		public function sendAction()
		{
			$message = $status = $invalid_mails = '';
			
			$form = new forms_MailForm();

			if ($this->getRequest()->isPost())
			{
				if ($form->isValid($_POST))
				{
					$tr = new Zend_Mail_Transport_Sendmail('-filian@athlondps.com');
					Zend_Mail::setDefaultTransport($tr);
					$params = $this->_getAllParams();
					$exclude = array('controller','action','module','subject','recipients');
					$subject = $this->_getParam('subject','Athlon mailer test mail');
					$recipients = $this->_getParam('recipients','');
					$mail_addresses = array_map("trim",explode("\n",$recipients));
					foreach ($params as $name=>$value)
					{
						if (!in_array($name,$exclude))
						{
							$filename = ROOT_DIR.'/public/files/uploaded/'.$value;
							$handle = fopen($filename,'r');
							$content = fread($handle, filesize($filename));
							fclose($handle);
	
							foreach ($mail_addresses as $recipient)
							{
								$validator = new Zend_Validate_EmailAddress();
								if ($validator->isValid($recipient))
								{
									$mail = new Zend_Mail();
									$mail->setBodyHtml($content);
									$mail->setFrom('admin@athlondps.com');
									$mail->addTo($recipient);
									$mail->setSubject($subject);
									$mail->send();
								}
								else
								{
									foreach ($validator->getMessages() as $messageId => $message)
									{
										$invalid_mails .= '<strong>'.$messageId.': </strong>'.$message.'<br />';
									}
								}
								
							}
						}
					}
					$status = 'Success';
					$message = '';
					if (strlen($invalid_mails) > 0)
					{
						$status = 'Error';
						$message.='<div class=\'errorExplanation\' id=\'errorExplanation\'><h2>There were some errors during the process</h2><p>Please review them below</p><p>'.$invalid_mails.'</p></div>';
					}
					else
					{
						$message = 'Done sending emails to '.sizeof($mail_addresses).' recipients';
					}
				}
				else
				{
					$status = 'Error';
					foreach ($form->getMessages() as $element=>$errorMessages)
					{
						foreach ($errorMessages as $messageId=>$message)
						{
							$invalid_mails .= '<strong>'.$element.' '.$messageId.': </strong>'.$message.'<br />';
						}
					}
					$message='<div class=\'errorExplanation\' id=\'errorExplanation\'><h2>There were some errors during the process</h2><p>Please review them below</p><p>'.$invalid_mails.'</p></div>';
				}
				$this->view->status = $status;
				$this->view->message = $message;
			}
			else
			{
				$redirector = $this->_helper->getHelper('Redirector');
				//setGotoSimple("action","controller");
				$redirector->setCode(303)->setExit(false)->setGotoSimple("index","index");
				$redirector->redirectAndExit();
			}
		}

	}
?>