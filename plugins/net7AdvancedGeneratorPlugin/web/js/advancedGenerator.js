function associateOption(element, relationClass){
	ids = $(element).attr('id').split("_");
	var toAppend = '<li value="' + $(element).attr("value") + '" id="as_' + ids[1] + '" onclick="return unassociateOption(this);">' + $(element).html() + '</li>';
	$('#associated_elementi').append(toAppend);
	$(element).hide();
	$('#to_associate_' + relationClass).append('<input type="hidden" value="' + $(element).attr("value") + '" name="associated[]" id="ids_' + ids[1] + '">');
}

function unassociateOption(element){
	ids = $(element).attr('id').split("_");
	//var toAppend = '<option value="' + $(element).attr("value") + '" id="un_"' + ids[1] + ' onclick="return associateOption();">';
	$('#un_' + ids[1]).show();
	$(element).remove();
	$("#ids_"+ids[1]).remove();
}


function generateColorDiv(color, elementId){
	$("#colors_container_" + elementId).append("<div class='color-viewer-item' id='" + elementId + "_color_" + color + "' style='background-color: #" + color +"' ></div>");
	$("#" + elementId + "_color_" + color).click(function(){
		$("#" + elementId).val(color);
	});
}

function showColorPopup(elementId, url){
	color = "#" + $("#"+elementId).val();
	val =  $("#"+elementId).val();
	if (color != '#'){
		window.open (url + "?color=" + val ,"colorpopup","menubar=1,resizable=1,width=280,height=280");
	}
}

function generateSelectOptions(selectId, limit ){
	var i = 0;
	
	for (i = 0; i < Math.pow(10,limit);i++)
		$('#' + selectId).append('<option value="' + i + '">' + i + '</option>');
	
}


function toggleFieldsWithSelect(optionsArray, fieldsArray, element){
	//console.log(optionsArray);
	//console.log(fieldsArray);
	//console.log($(element).val());
	var value = $(element).val();
	var control = false;
	for (var i = 0; i < optionsArray.length;i++){
		if (value == optionsArray[i]){
			control = true;
			break;
		}
	}
	if (control) {
		for (var i = 0; i < fieldsArray.length; i++){
			$('.sf_admin_form_field_' + fieldsArray[i]).show();
		}
	} else {
		for (var i = 0; i < fieldsArray.length; i++){
			//console.log($('#sf_admin_form_field_' + fieldsArray[i]));
			$('.sf_admin_form_field_' + fieldsArray[i]).hide();
		}
	}

	
}

function updateBooleanInlineEdit(element, oldValue, recordId ,url){
	$(element).html('<img src="/net7AdvancedGeneratorPlugin/images/loader.gif"/>');
	$(element).parent().load( url + '&value=' + oldValue + '&id=' + recordId);
	return false;
}


function showAddLodEntityButton(buttonId){
	$("#" + buttonId).show();
}

function hideAddLodEntityButton(buttonId){
	$("#" + buttonId).hide();
}

function openRelationDialog(relationClass){
	// clear the previous input values 
	$('#item_form_' + relationClass +' .fieldList :input[type=text]').val("");
	
	$( "#item_form_" + relationClass).dialog({resizable: false, modal: true,title: "Create new Item"});
}

function openAssociateDialog(relationClass){
	// clear the previous input values 
	$( "#associate_form_" + relationClass).dialog({resizable: false, modal: true,title: "Associate Existing Item"});
}

function deleteRelationItem(itemId, relationClass, relationId, mainField, objId, url){
	if (confirm("Eliminare l'elemento?")){
		$('#relation_list_' + relationClass).load(url + '?itemId=' + itemId + "&relationClass=" + relationClass + "&relation_id=" + relationId + "&mainField=" + mainField + "&objId=" + objId);
	}
}

function deleteNNRelationItem(itemId, outboundRelationClass, outboundRelationClassAlias, outboundRelationId, relationClass, relationId, mainField, objId, url){
	if (confirm("Eliminare l'elemento?")){
		$('#relation_list_' + relationClass).load(url + '?itemId=' + itemId + "&outboundRelationId=" + outboundRelationId + "&outboundRelationClassAlias=" + outboundRelationClassAlias + "&outboundRelationClass=" + outboundRelationClass +  "&relationClass=" + relationClass + "&relation_id=" + relationId + "&mainField=" + mainField + "&objId=" + objId);
	}
}

function saveAssociatedItems(relationClass, url){
	var params = $('#to_associate_' + relationClass +' :input[value]').serialize();

	$('#relation_list_' + relationClass).load(url + '?' + params, function() { $("#associate_form_" + relationClass).dialog("close"); });
}

function saveRelation(descriptor, url){
	var params = $('#item_form_' + descriptor +' .fieldList :input[value]').serialize();
	//ajax: url: url + '?' + params
	
	$("#item_form_" + descriptor).dialog("close");

	$('#relation_list_' + descriptor).load(url + '?' + params);
}
