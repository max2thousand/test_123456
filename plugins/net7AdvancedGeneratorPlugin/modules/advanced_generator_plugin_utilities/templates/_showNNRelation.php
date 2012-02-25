<?php 
use_javascript('/net7AdvancedGeneratorPlugin/js/jq/plugins/ui/jquery-ui-1.8.13.min.js');

echo javascript_tag('

	$(function() {
		$("#relation_list_' . $relationClass . '").sortable({
          	update : function () { 
          		var order = $("#relation_list_' . $relationClass . '").sortable("serialize"); 
          		$.ajax({
          			url: "' . url_for("@net7_advanced_generator_order_relation_items") . '?"+order+"&relationClass=' . $relationClass . '&relation_id=' . $relationId . '&objId=' . $objId . '"         		
          		});
   			}
      	});
	});

'); ?>

<div class="relation-wrapper">
	    <div class="new-relation-item">	
		    <?php if (!$hideNewButton):?>
		        <span class="button">
		        	<a href='#' class="button-inner add" onclick="return openRelationDialog('<?php echo $relationClass ?>');">Nuovo Elemento</a>
		        </span>
		    <?php endif;?>
		    <?php if (!$hideAssociateButton):?>
		        <span class="button">
		        	<a href='#' class="button-inner add" onclick="return openAssociateDialog('<?php echo $relationClass ?>');">Associa Esistente</a>
		        </span>
		    <?php endif;?>
	    </div>

    <ul id="relation_list_<?php echo $relationClass ?>">
        <?php
        include_partial('advanced_generator_plugin_utilities/relation_item_list', 
                array('itemsToShow' => $itemsToShow,
                      'mainField' => $mainField,
                      'outboundRelationClassAlias' => $outboundRelationClassAlias,
                      'outboundRelationClass' => $outboundRelationClass,
                      'outboundRelationId' => $outboundRelationId,
                      'relationClass' => $relationClass,
                      'objId' => $objId,
                      'relationId' => $relationId,
                      'editRoute'=>$editRoute))
        ?>
    </ul>



    <div id="item_form_<?php echo $relationClass ?>" style="display: none;">
        <div class="fieldList">
            <input type="hidden" value="<?php echo $relationClass ?>" name="relationClass">
            <input type="hidden" value="<?php echo $outboundRelationClass ?>" name="outboundRelationClass">
            <input type="hidden" value="<?php echo $outboundRelationClassAlias ?>" name="outboundRelationClassAlias">
            <input type="hidden" value="<?php echo $objId ?>" name="objId">
            <input type="hidden" value="<?php echo $relationId ?>" name="relation_id">
            <input type="hidden" value="<?php echo $outboundRelationId ?>" name="outboundRelationId">
            <input type="hidden" value="<?php echo $mainField ?>" name="mainField">
            <input type="hidden" value="<?php echo $editRoute ?>" name="editRoute">
            
            <div class="field">
                <label>Insert the <?php echo $mainField ?></label>
                <input type="text" name="mainFieldValue" />
            </div>
            <span class="button">
                <input type="submit" class="button" value="Save" onclick="return saveRelation('<?php echo $relationClass ?>', '<?php echo url_for('@net7_advanced_generator_save_nn_relation_item') ?>');">
            </span>
        </div>
    </div>
    <div id="associate_form_<?php echo $relationClass ?>" style="display: none;">
		<div class="selection-wrapper">
			
			<span class="label">Unassociated Items</span>
			<div class="source">
				<div class="source-inner">
					<ul id="unassociated_elementi">
						<?php $counter = 0; 
							foreach ($unassociatedItems as $elemento):?>
							<?php ?>
							<li id="un_<?php echo $elemento->id?>" value="<?php echo $elemento->id?>" onclick="return associateOption(this, '<?php echo $relationClass?>');"
								class="<?php if (++$counter % 2 == 1) echo 'odd'; else echo 'even'; if ($counter == count($unassociatedItems)) echo ' last';?>"
								title="<?php echo $elemento->$mainField?>"
							>
								<?php echo $elemento->$mainField?>
							</li>
						<?php endforeach ;?>
						<?php foreach ($itemsToShow as $elemento):?>
						<li value="<?php echo $elemento->$outboundRelationClassAlias->id?>" id="un_<?php echo $elemento->$outboundRelationClassAlias->id?>" onclick="return unassociateOption(this);"
							class="<?php if (++$counter % 2 == 1) echo 'odd'; else echo 'even'; if ($counter == count($itemsToShow)) echo ' last';?>"
							title="<?php echo $elemento->$outboundRelationClassAlias->$mainField?>" style="display:none;"
						>
							<?php echo $elemento->$outboundRelationClassAlias->$mainField?>
						</li>
					<?php endforeach ;?>
					</ul>
				</div>
			</div>
			
			<span class="label">Associated Items</span>
			<div class="source">
				<div class="source-inner">
				<ul id="associated_elementi">
					<?php
						 $counter = 0; 
						foreach ($itemsToShow as $elemento):?>
						<li value="<?php echo $elemento->$outboundRelationClassAlias->id?>" id="as_<?php echo $elemento->$outboundRelationClassAlias->id?>" onclick="return unassociateOption(this);"
							class="<?php if (++$counter % 2 == 1) echo 'odd'; else echo 'even'; if ($counter == count($itemsToShow)) echo ' last';?>"
							title="<?php echo $elemento->$outboundRelationClassAlias->$mainField?>"
						>
							<?php echo $elemento->$outboundRelationClassAlias->$mainField?>
						</li>
					<?php endforeach ;?>
				
				</ul>
				</div>
			</div>
			<div id="to_associate_<?php echo $relationClass?>">
				<input type="hidden" value="<?php echo $relationClass ?>" name="relationClass">
	            <input type="hidden" value="<?php echo $outboundRelationClass ?>" name="outboundRelationClass">
	            <input type="hidden" value="<?php echo $outboundRelationClassAlias ?>" name="outboundRelationClassAlias">
	            <input type="hidden" value="<?php echo $objId ?>" name="objId">
	            <input type="hidden" value="<?php echo $relationId ?>" name="relation_id">
	            <input type="hidden" value="<?php echo $outboundRelationId ?>" name="outboundRelationId">
	            <input type="hidden" value="<?php echo $mainField ?>" name="mainField">
	            <input type="hidden" value="<?php echo $editRoute ?>" name="editRoute">
			<?php foreach ($itemsToShow as $elemento):?>
				<input type="hidden" name="associated[]" value="<?php echo $elemento->$outboundRelationClassAlias->id?>" id="ids_<?php echo $elemento->$outboundRelationClassAlias->id?>">
			<?php endforeach;?>
	            
	            <span class="button">
	                <input type="submit" class="button" value="Save" onclick="return saveAssociatedItems('<?php echo $relationClass ?>', '<?php echo url_for('@net7_advanced_generator_save_associated_items') ?>');">
	            </span>
	    </div>
    </div>
</div>

