<?php

require_once dirname(__FILE__) . '/../lib/Baseadvanced_generator_plugin_utilitiesActions.class.php';

/**
 * advanced_generator_plugin_utilities actions.
 * 
 * @package    net7AdvancedGeneratorPlugin
 * @subpackage advanced_generator_plugin_utilities
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class advanced_generator_plugin_utilitiesActions extends Baseadvanced_generator_plugin_utilitiesActions {

    public function executeShowColorPopup(sfWebRequest $request) {
        $this->color = $request->getParameter("color");
        $this->setLayout(false);
    }

    public function executeGoToPage(sfWebRequest $request) {
        $this->redirect('/' . $request->getParameter('moduleName') . '?page=' . $request->getParameter('page'));
    }

    public function executeAjaxAutocomplete(sfWebRequest $request) {
        $field = $request->getParameter('f');
        $toSearch = $request->getParameter('term');
        $tmpDefaultValues = explode('|||', $request->getParameter('defaultValues'));

        unset($tmpDefaultValues[count($tmpDefaultValues) - 1]);

        foreach ($tmpDefaultValues as $key => $value) {
            if (strpos(strtoupper($value), strtoupper($toSearch)) === false)
                unset($tmpDefaultValues[$key]);
        }

        $tmpResults = Doctrine::getTable($request->getParameter('t'))->createQuery('a')
                ->select('a.' . $field)
                ->addWhere('a.' . $field . ' like "%' . $toSearch . '%"')
                ->orderBy('a.' . $field)
                ->fetchArray();

        $results = array();
        foreach ($tmpResults as $result)
            $results[] = $result[$field];


        $a = array_unique(array_merge($tmpDefaultValues, $results));
        //print_r($a);die;

        $resultsMap = array();
        //$resultsMap['results'] = $results;
        $resultsMap['results'] = $a;
        echo json_encode($resultsMap);
    }

    public function executeCreateRelationItem(sfWebRequest $request) {
        $relationToPersist = $request->getParameter('relationClass');

        $obj = new $relationToPersist();
        call_user_func_array(array($obj, '__construct'), array());

        $obj->set($request->getParameter('relation_id'), $request->getParameter('objId'));
        $obj->set($request->getParameter('mainField'), $request->getParameter('mainFieldValue'));
        $obj->save();

        $this->itemsToShow = $this->getItemsToShow($request->getParameter('relationClass'), $request->getParameter('relation_id'), $request->getParameter('objId'));

        $this->mainField = $request->getParameter('mainField');
        $this->relationClass = $request->getParameter('relationClass');
        $this->relationId = $request->getParameter('relation_id');
        $this->objId = $request->getParameter('objId');
        $this->editRoute = $request->getParameter('editRoute');
    }

    public function executeSaveAssociatedItems(sfWebRequest $request) {
        $this->mainField = $request->getParameter('mainField');
        $this->relationClass = $request->getParameter('relationClass');
        $this->relationId = $request->getParameter('relation_id');
        $this->objId = $request->getParameter('objId');
        $this->outboundRelationClass = $request->getParameter('outboundRelationClass');
        $this->outboundRelationClassAlias = $request->getParameter('outboundRelationClassAlias');
		$this->outboundRelationId = $request->getParameter('outboundRelationId');
        $this->editRoute = $request->getParameter('editRoute');
    	
        //die($this->relationId.' '.$this->objId);
    	$prevAssociatedElements = Doctrine::getTable($this->relationClass)->createQuery('a')->where($this->relationId. '= ?', $this->objId)->execute();
    	foreach ($prevAssociatedElements as $item) $item->delete();
    	
    	$relationClass = $this->relationClass;
    	
    	foreach ($request->getParameter('associated', array()) as $itemToAssociate){
    		$obj = new $relationClass();
        	call_user_func_array(array($obj, '__construct'), array());
        	$obj->set($this->relationId, $this->objId);
        	$obj->set($this->outboundRelationId, $itemToAssociate);
        	$obj->save();
    	}
    	
    	$this->itemsToShow = $this->getItemsToShow($this->relationClass, $this->relationId, $this->objId);

        $this->setTemplate('createNNRelationItem');
    }
    
    public function executeCreateNNRelationItem(sfWebRequest $request) {
    	$relationToPersist = $request->getParameter('relationClass');
    	$outboundRelationToPersist = $request->getParameter('outboundRelationClass');
		
		$obj = new $outboundRelationToPersist();
        call_user_func_array(array($obj, '__construct'), array());

        $obj->set($request->getParameter('mainField'), $request->getParameter('mainFieldValue'));
        $obj->save();
    	
    	
        $relationObj = new $relationToPersist();
        call_user_func_array(array($relationObj, '__construct'), array());

        $relationObj->set($request->getParameter('relation_id'), $request->getParameter('objId'));
        $relationObj->set($request->getParameter('outboundRelationId'), $obj->id);
        $relationObj->save();

        $this->itemsToShow = $this->getItemsToShow($request->getParameter('relationClass'), $request->getParameter('relation_id'), $request->getParameter('objId'));

        $this->mainField = $request->getParameter('mainField');
        $this->outboundRelationClass = $request->getParameter('outboundRelationClass');
        $this->outboundRelationClassAlias = $request->getParameter('outboundRelationClassAlias');
		$this->outboundRelationId = $request->getParameter('outboundRelationId');
		$this->relationClass = $request->getParameter('relationClass');
        $this->relationId = $request->getParameter('relation_id');
        $this->objId = $request->getParameter('objId');
        $this->editRoute = $request->getParameter('editRoute');
    }

    
    
    public function executeOrderRelationItems(sfWebRequest $request) {
        foreach ($request->getParameter("item") as $position => $itemId) {
            $item = Doctrine::getTable($request->getParameter('relationClass'))->find($itemId);
            $item->moveToLast();
            $item->save();
        }

        $this->itemsToShow = $this->getItemsToShow($request->getParameter('relationClass'), $request->getParameter('relation_id'), $request->getParameter('objId'), $request->getParameter('editRoute'));
    }

    public function executeDeleteRelationItem(sfWebRequest $request) {
        $item = Doctrine::getTable($request->getParameter('relationClass'))->find($request->getParameter('itemId'));

        $item->delete();

        $this->itemsToShow = $this->getItemsToShow($request->getParameter('relationClass'), $request->getParameter('relation_id'), $request->getParameter('objId'));

        $this->mainField = $request->getParameter('mainField');
        $this->relationClass = $request->getParameter('relationClass');
        $this->relationId = $request->getParameter('relation_id');
        $this->objId = $request->getParameter('objId');
        $this->editRoute = $request->getParameter('editRoute');

        $this->setTemplate('createRelationItem');
    }
    
	public function executeDeleteNNRelationItem(sfWebRequest $request) {
        $item = Doctrine::getTable($request->getParameter('relationClass'))->find($request->getParameter('itemId'));

        $item->delete();

        $this->itemsToShow = $this->getItemsToShow($request->getParameter('relationClass'), $request->getParameter('relation_id'), $request->getParameter('objId'));

        $this->mainField = $request->getParameter('mainField');
        $this->relationClass = $request->getParameter('relationClass');
        $this->relationId = $request->getParameter('relation_id');
        $this->objId = $request->getParameter('objId');
        $this->outboundRelationClass = $request->getParameter('outboundRelationClass', '');
        $this->outboundRelationClassAlias = $request->getParameter('outboundRelationClassAlias', '');
		$this->outboundRelationId = $request->getParameter('outboundRelationId', '');
        $this->editRoute = $request->getParameter('editRoute');

        $this->setTemplate('createNNRelationItem');
    }

    public function executeUpdateMainRelationItemField(sfWebRequest $request) {
        $params = explode('|||', $request->getParameter('text'));

        if (isset($params[1]) && $params[1] != '-1') {
            $item = Doctrine::getTable($params[3])->find($params[1]);
            $item->set($params[2], $params[0]);
            $item->save();
        }

        //$this->setTemplate('updateInlineName');
        $this->name = $params[0];

        $this->setLayout(false);
        sfView::NONE;
    }

    public function executeUpdateEdinlineField(sfWebRequest $request) {
        $relatedModel = $request->getParameter('relatedModel');
        if ($relatedModel) {
            $recordToSave = Doctrine::getTable($request->getParameter('modelName'))->find($request->getParameter('id'));
            $recordToSave->$relatedModel->set($request->getParameter('fieldName'), $request->getParameter('value'));
            $recordToSave->$relatedModel->save();
        } else {
            $recordToSave = Doctrine::getTable($request->getParameter('modelName'))->find($request->getParameter('id'));
            $recordToSave->set($request->getParameter('fieldName'), $request->getParameter('value'));
            $recordToSave->save();
        }
        echo $request->getParameter('value');
        die;
    }

    public function executeUpdateEditinlineBooleanField(sfWebRequest $request) {
        $valueToChange = ($request->getParameter('value') == 1) ? 0 : 1;
        $relatedModel = $request->getParameter('relatedModel');
        if ($relatedModel) {
            $recordToSave = Doctrine::getTable($request->getParameter('modelName'))->find($request->getParameter('id'));
            $recordToSave->$relatedModel->set($request->getParameter('fieldName'), $valueToChange);
            $recordToSave->$relatedModel->save();
        } else {
            $recordToSave = Doctrine::getTable($request->getParameter('modelName'))->find($request->getParameter('id'));
            $recordToSave->set($request->getParameter('fieldName'), $valueToChange);
            $recordToSave->save();
        }

        $this->modelName = $request->getParameter('modelName');
        $this->field = $request->getParameter('fieldName');
        $this->id = $request->getParameter('id');
        $this->value = $valueToChange;
        $this->relatedModel = $relatedModel;
    }

    private function getItemsToShow($relationClass, $relationId, $objectId) {
        return Doctrine::getTable($relationClass)->createQuery('a')
                        ->where($relationId . ' = ?', $objectId)
                        ->orderBy('a.position')
                        ->execute();
    }

}
