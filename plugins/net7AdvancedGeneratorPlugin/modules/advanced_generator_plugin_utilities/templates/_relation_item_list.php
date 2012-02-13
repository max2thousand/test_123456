<?php use_javascript('/net7AdvancedGeneratorPlugin/js/jquery.inline-edit.min.js')?>
<?php use_helper('jQuery') ?>
<?php use_helper('I18N') ?>

<?php echo javascript_tag('
/*jQuery(function( $ ){
		$(".edit-item").inlineEdit({
			hover: "hover",
			href:  "'.url_for('@net7_advanced_generator_update_main_relation_item_field').'",
			form:  ".form",
			
		})
	});
*/	
')?>
<?php foreach ($itemsToShow as $item):?>
			<li class="collapsed relation-item" id="item_<?php echo  $item->id ?>">
				<div class="info">
					<div class="item-name">
						<div class="edit-item inline-edit">
							<span class='display' id="item_display_name_<?php echo $item->id?>">
								<?php if (isset($outboundRelationClassAlias)):?>
									<?php $obj = $item->$outboundRelationClassAlias; if (isset($obj[$mainField]))echo $obj[$mainField]; else echo '...'?>
								<?php else:?>
									<?php if (isset($item[$mainField]))echo $item[$mainField]; else echo '...'?>
								<?php endif;?>	
							</span>
							<span class='form'>
								<input type="text" class='text text_item' id="item_text_<?php echo $item->id?>" />
								
								<span>
									<input type='submit' class='save' value='Save' onclick="$('.text_item').val($('#item_text_<?php echo $item->id?>').val() + '|||' + <?php if (isset($item[$mainField])) echo $item['id'];else echo '-1' ?> + '|||' + '<?php echo $mainField?>' + '|||' + '<?php echo $relationClass?>' );"/>
									<input type='submit' class='cancel' value='Cancel' />
								</span>	
							</span>
						</div>
						
					</div>
				</div>
				<div class="actions">
					<ul>
						<li class="delete ">
							<?php if (isset($outboundRelationClassAlias)):?>
								<a href="#" onclick="return deleteNNRelationItem( <?php echo $item->id ?>, '<?php echo $outboundRelationClass?>', '<?php echo $outboundRelationClassAlias?>', '<?php echo $outboundRelationId?>', '<?php echo $relationClass ?>', '<?php echo $relationId ?>', '<?php echo $mainField ?>', '<?php echo $objId ?>', '<?php echo url_for('@net7_advanced_generator_delete_nn_relation_item')?>');">Delete</a>
							<?php else:?>
								<a href="#" onclick="return deleteRelationItem( <?php echo $item->id ?>, '<?php echo $relationClass ?>', '<?php echo $relationId ?>', '<?php echo $mainField ?>', '<?php echo $objId ?>', '<?php echo url_for('@net7_advanced_generator_delete_relation_item')?>');">Delete</a>
							<?php endif;?>	
						</li>
						<li class="edit">
                                <a href="<?php echo url_for(str_replace('%%id%%', $item->id, $editRoute)); ?>" onclick="return confirm('<?php echo __('Warning: you are leaving this page and maybe there are some elements not saved.') ?>');">Edit</a>
						</li>	
					</ul>
				</div>
			</li>	
	
		<?php endforeach;?>