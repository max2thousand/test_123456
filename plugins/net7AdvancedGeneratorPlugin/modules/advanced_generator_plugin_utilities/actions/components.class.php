<?php

class advanced_generator_plugin_utilitiesComponents extends sfComponents
{
	public function executeShowRelation(sfWebRequest $request) {
		$this->itemsToShow = $this->retrieveRelationItems($this->params['class'], $this->objId, $this->params['relation_id']);
		$this->mainField = $this->params['main_field'];	
		$this->objId = $this->objId;	
		$this->relationClass = $this->params['class'];
		$this->relationId = $this->params['relation_id'];
		$this->editRoute = $this->params['edit_route'];
	}
	
	public function executeShowNNRelation(sfWebRequest $request) {
		$this->itemsToShow = $this->retrieveRelationItems($this->params['class'], $this->objId, $this->params['relation_id']);
		
		
		$this->mainField = $this->params['main_field'];	
		$this->objId = $this->objId;	
		$this->relationClass = $this->params['class'];
		$this->outboundRelationClass = $this->params['outbound_class'];
		$this->outboundRelationClassAlias = $this->params['outbound_class_alias'];
		$this->outboundRelationId = $this->params['outbound_relation_id'];
		$this->relationId = $this->params['relation_id'];
		$this->editRoute = $this->params['edit_route'];
		$this->hideNewButton = $this->params['hide_new_button'] ? true : false;
		$this->hideAssociateButton = $this->params['hide_associate_button'] ? true : false;
		
		$notInArray = array();
        foreach ($this->itemsToShow as $elm) {
        	$relation = $this->outboundRelationClassAlias;
            $notInArray[] = $elm->$relation->id;
        }
        $this->unassociatedItems = Doctrine::getTable($this->outboundRelationClass)->createQuery('a')->whereNotIn('id', $notInArray)->execute();
	}
	
	private function retrieveRelationItems($relationClass, $objectId, $objDbField){
		$q = Doctrine::getTable($relationClass)->createQuery('a')
			->where($objDbField.' = ?', $objectId)
			->orderBy('a.position')
			->execute();
			
		return $q;	
	}
	
	
}