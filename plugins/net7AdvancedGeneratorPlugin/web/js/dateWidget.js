function readDateFromSelectInputs(fieldName, moduleName, isFilter){
	var selectPrefix = "";
	if (isFilter == true){
		selectPrefix = moduleName + "_filters_" + fieldName;
	}else selectPrefix =  moduleName + "_" + fieldName;
	
	var day = $("#" + selectPrefix + "_day").val();
	var month = $("#" + selectPrefix + "_month").val();
	var year = $("#" + selectPrefix + "_year").val();
	
	dateString = month + "/" + day + "/" + year;
	$("#" + fieldName).val(dateString);
}

function writeDateToSelectInputs(fieldName, moduleName, dateText, isFilter){
	var selectPrefix = "";
	if (isFilter == true){
		selectPrefix = moduleName + "_filters_" + fieldName;
	}else selectPrefix =  moduleName + "_" + fieldName;
	
	var month = dateText.substring(0, 2);
	if (dateText.substring(0, 1)=='0')
		month = dateText.substring(1, 2);
	
	var day = dateText.substring(3, 5);
	if (dateText.substring(3, 4)=='0')
		day = dateText.substring(4, 5);
	
	//var month = dateText.substring(0, 2);
	//var day = dateText.substring(3, 5);
	var year = dateText.substring(6, 10);

	//SETTING DATE TO SELECT INPUT
	$("#" + selectPrefix + "_day").val(day);
	$("#" + selectPrefix + "_month").val(month);
	//$("#" + selectPrefix + "_year").val(year);
	$("#" + selectPrefix + "_year").html('').append('<option selected="selected" value="' + year + '">' + year + '</option>');
}


function readDateFromTextInput(fieldName, moduleName, isFilter){

	var selectPrefix = "";
	if (isFilter == true){
		selectPrefix = moduleName + "_filters_" + fieldName;
	}else selectPrefix =  moduleName + "_" + fieldName;
	
	var dateArray = $("#" + selectPrefix).val().split('-');
	
	//month/day/year
	dateString = dateArray[1] + "/" + dateArray[2] + "/" + dateArray[0];
	$("#" + fieldName).val(dateString);
	$(document).ready(function(){
		if (dateArray[0])
			$('#span_' + selectPrefix).html(dateArray[0] + "-" + dateArray[1] + "-" + dateArray[2]);
	});
	
}

function writeDateToTextInputs(fieldName, moduleName, dateText, isFilter){
	var selectPrefix = "";
	if (isFilter == true){
		selectPrefix = moduleName + "_filters_" + fieldName;
	}else selectPrefix =  moduleName + "_" + fieldName;
	
	var month = dateText.substring(0, 2);
	if (dateText.substring(0, 1)=='0')
		month = dateText.substring(1, 2);
	
	var day = dateText.substring(3, 5);
	if (dateText.substring(3, 4)=='0')
		day = dateText.substring(4, 5);
	
	var year = dateText.substring(6, 10);

	//SETTING DATE TO INPUT text
	$("#" + selectPrefix).val(year + '-' + month + '-' + day);
	$("#span_" + selectPrefix).html(year + '-' + month + '-' + day);
}




function initializeCalendarWidget(fieldName, moduleName, yearRange){
	var selectPrefix =  moduleName + "_" + fieldName;
	var yearId = selectPrefix + "_year";
	//appending hidden input type // WITH SELECT WIDGET	
	//$("#" + yearId).parent().append("<input type=\"hidden\" id=\"" + fieldName + "\" value=\"\" style=\"display: none;\">");
	
	$("#" + selectPrefix).parent().append("<input type=\"hidden\" id=\"" + fieldName + "\" value=\"\" style=\"display: none;\">");
	
	if(!yearRange) 
		setStandardPicker(fieldName, moduleName);
	else setRangedPicker(fieldName, moduleName, yearRange);

	// disable input select - WITH SELECT WIDGET	
	//disableSelects(selectPrefix);
	disableInput(selectPrefix);
	$("#" + selectPrefix).parent().prepend("<span class='advanced-date-span' id='span_" + selectPrefix + "'></span>")
	/*$(document).submit(function(){
		enableSelects(selectPrefix);
	});*/
	$("input[type=\"submit\"]").click(function(){
		enableSelects(selectPrefix);
	});
}	

function setStandardPicker(fieldName, moduleName){
	$("#" + fieldName).datepicker({
		showOn:          "button",
		buttonImage:     "/net7AdvancedGeneratorPlugin/images/calendar.gif",
		buttonImageOnly: true,
		//beforeShow:      readDateFromSelectInputs(fieldName, moduleName, false),
		//onSelect:        function(dateText, inst) { writeDateToSelectInputs(fieldName, moduleName, dateText, false); } 
		beforeShow:      readDateFromTextInput(fieldName, moduleName, false),
		onSelect:        function(dateText, inst) { writeDateToTextInputs(fieldName, moduleName, dateText, false); } 
	
	});
}

function setRangedPicker(fieldName, moduleName, yearRange){
	var splittedDate = yearRange.split(' ');
	var startYear = parseInt(splittedDate[0]);
	var endYear = parseInt(splittedDate[1]);
	var currentYear = parseInt( (endYear - startYear) / 2);
	
	var da = new Date();
	defaultDate = new Date(startYear, 0, 1);
	if (startYear < da.getFullYear() && endYear > da.getFullYear()){
		defaultDate = new Date();
	}
	
	
	$("#" + fieldName).datepicker({
		showOn:          "button",
		buttonImage:     "/net7AdvancedGeneratorPlugin/images/calendar.gif",
		shortYearCutoff: 50,
		buttonImageOnly: true,
		changeMonth:     true,
		changeYear:      true,
		minDate:         new Date(startYear, 0, 1), 
		maxDate: 		 new Date(endYear, 11, 31),
		defaultDate:     defaultDate,
		//beforeShow:      readDateFromSelectInputs(fieldName, moduleName, false),
		//onSelect:        function(dateText, inst) { writeDateToSelectInputs(fieldName, moduleName, dateText, false); } 
		beforeShow:      readDateFromTextInput(fieldName, moduleName, false),
		onSelect:        function(dateText, inst) { writeDateToTextInputs(fieldName, moduleName, dateText, false); }
	});
}

function initializeFilterCalendarWidgets(fieldName, moduleName){
	initializeFilterCalendarWidget(fieldName, moduleName, "from");
	initializeFilterCalendarWidget(fieldName, moduleName, "to");
}


// option could be {from , to}
function initializeFilterCalendarWidget(fieldName, moduleName, option){
	fieldName = fieldName + "_" + option;
	var yearId = moduleName + "_filters_" + fieldName + "_year";
	var selectPrefix = moduleName + "_filters_" + fieldName;
	
	//appending hidden input type	
	$("#" + yearId).after("<input type=\"hidden\" id=\"" + fieldName + "\" value=\"\" style=\"display: none;\">");
	
	$("#" + fieldName).datepicker({
		showOn:          "button",
		buttonImage:     "/net7AdvancedGeneratorPlugin/images/calendar.gif",
		buttonImageOnly: true,
		beforeShow:      readDateFromSelectInputs(fieldName, moduleName, true),
		onSelect:        function(dateText, inst) { writeDateToSelectInputs(fieldName, moduleName, dateText, true); } 
	});

	// disable input select
	disableSelects(selectPrefix);

	/*$(document).submit(function(){
		enableSelects(selectPrefix);
	});*/
	$("input[type=\"submit\"]").click(function(){
		enableSelects(selectPrefix);
	});
}	

function disableInput(selectPrefix){
	$("#" + selectPrefix ).attr("readonly", "readonly").hide();
}


function disableSelects(selectPrefix){
	$("#" + selectPrefix + "_day").attr("disabled", "disabled");
	$("#" + selectPrefix + "_month").attr("disabled", "disabled");
	$("#" + selectPrefix + "_year").attr("disabled", "disabled");
}

function enableSelects(selectPrefix){
	$("#" + selectPrefix + "_day").attr("disabled", "");
	$("#" + selectPrefix + "_month").attr("disabled", "");
	$("#" + selectPrefix + "_year").attr("disabled", "");
}
