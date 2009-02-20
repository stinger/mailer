<?php
class Zend_View_Helper_ActiveIcon
{
	protected $_view;

	function setView($view)
	{
		$this->_view = $view;
	}

	function activeIcon($active, $controller, $action, $id)
	{
		if ($active)
		{
			return '<a href="#" id="item'.$id.'_active" onclick="new Ajax.Request(\''.$this->_view->baseUrl.'/'.$controller.'/'.$action.'/'.$id.'\', {evalScripts:true, method: \'post\', parameters: {active: 0, id: '.$id.'}, onComplete: function(request,json){if (json.status==\'Failed\'){alert(json.message);}}}); return false;"><img src="'.$this->_view->baseUrl.'/images/layout/actions/ok.png" alt=""/></a>';
			
		}
		else
		{
			return '<a href="#" id="item'.$id.'_active" onclick="new Ajax.Request(\''.$this->_view->baseUrl.'/'.$controller.'/'.$action.'/'.$id.'\', {evalScripts:true, method: \'post\', parameters: {active: 1, id: '.$id.'}, onComplete: function(request,json){if (json.status==\'Failed\'){alert(json.message);}}}); return false;"><img src="'.$this->_view->baseUrl.'/images/layout/actions/cancel.png" alt=""/></a>';
		}
	}
}
?>