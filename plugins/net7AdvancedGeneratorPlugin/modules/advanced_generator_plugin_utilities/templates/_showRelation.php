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
        <span class="button">
        	<a href='#' class="button-inner add" onclick="return openRelationDialog('<?php echo $relationClass ?>');">Nuovo Elemento</a>
        </span>
    </div>

    <ul id="relation_list_<?php echo $relationClass ?>">
        <?php
        include_partial('advanced_generator_plugin_utilities/relation_item_list', 
                array('itemsToShow' => $itemsToShow,
                      'mainField' => $mainField,
                      'relationClass' => $relationClass,
                      'objId' => $objId,
                      'relationId' => $relationId,
                      'editRoute'=>$editRoute))
        ?>
    </ul>



    <div id="item_form_<?php echo $relationClass ?>" style="display: none;">
        <div class="fieldList">
            <input type="hidden" value="<?php echo $relationClass ?>" name="relationClass">
            <input type="hidden" value="<?php echo $objId ?>" name="objId">
            <input type="hidden" value="<?php echo $relationId ?>" name="relation_id">
            <input type="hidden" value="<?php echo $mainField ?>" name="mainField">
            <input type="hidden" value="<?php echo $editRoute ?>" name="editRoute">
            
            <div class="field">
                <label>Insert the <?php echo $mainField ?></label>
                <input type="text" name="mainFieldValue" />
            </div>
            <span class="button">
                <input type="submit" class="button" value="Save" onclick="return saveRelation('<?php echo $relationClass ?>', '<?php echo url_for('@net7_advanced_generator_save_relation_item') ?>');">
            </span>
        </div>
    </div>
</div>

