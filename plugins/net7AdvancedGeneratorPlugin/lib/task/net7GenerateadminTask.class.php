<?php

class net7GenerateadminTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addArguments(array(
		new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
		new sfCommandArgument('route_or_model', sfCommandArgument::REQUIRED, 'The route name or the model class'),
		));

		$this->addOptions(array(
		new sfCommandOption('module', null, sfCommandOption::PARAMETER_REQUIRED, 'The module name', null),
		new sfCommandOption('theme', null, sfCommandOption::PARAMETER_REQUIRED, 'The theme name', 'admin'),
		new sfCommandOption('singular', null, sfCommandOption::PARAMETER_REQUIRED, 'The singular name', null),
		new sfCommandOption('plural', null, sfCommandOption::PARAMETER_REQUIRED, 'The plural name', null),
		new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
		new sfCommandOption('actions-base-class', null, sfCommandOption::PARAMETER_REQUIRED, 'The base class for the actions', 'sfActions'),
		));

		$this->namespace        = 'net7';
		$this->name             = 'generate-admin';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [net7:generate-admin|INFO] task automatically generates a doctrine admin module with pre-configured net7AdvancedGenerator features.
Call it with:

  [php symfony net7:generate-admin|INFO]
EOF;
	}

	
	protected function execute($arguments = array(), $options = array())
	{
		unset($arguments['task']);
		unset($options['version']);
		unset($options['quiet']);
		unset($options['trace']);
		unset($options['help']);
		unset($options['color']);
		 

		$task = new sfDoctrineGenerateAdminTask($this->dispatcher, new sfFormatter());
		$task->run($arguments, $options);
		
		$copyBasePath = sfConfig::get('sf_apps_dir').'/'.$arguments['application'].'/modules/';
		
		$copyBasePath .= ($options['module']) ? $options['module'] : sfInflector::underscore($arguments['route_or_model']);
		$copyTemplatePath = $copyBasePath.'/templates/'; 
		
		// copying the confguration templates into the generated module dir
		copy(sfConfig::get('sf_plugins_dir').'/net7AdvancedGeneratorPlugin/data/admin-templates/_form_header.php', $copyTemplatePath.'_form_header.php');
		copy(sfConfig::get('sf_plugins_dir').'/net7AdvancedGeneratorPlugin/data/admin-templates/_list_header.php', $copyTemplatePath.'_list_header.php');
		

		// appending advanced generator descriptions to the generator file 
		$dafile=fopen($copyBasePath.'/config/generator.yml',"a+"); 
		fwrite($dafile, $this->getHelpString());  
		
	}
	
	private function getHelpString(){
		return "\n\n\n\n 
# FORM CONFIGURATION: form
# fields : the field list 
# has_date_picker: if present, if the field is a timestamp/date field the 'html select' if replaced with a dataPicker
# width : increases the width of the input field using the value passed as parameter
# select_width: increases the width of the 'html select' using the value passed as parameter
# double_select_width: increases the width of the 'double select' using the value passed as parameter
# textarea_size: has two parameters: 
#	                  rows: number of textarea rows
#                   cols: number of textarea columns
# toggle: if is set the field label is rendered as a link. Clicking on it you can display/hide the selected field  
# toggle_fields: if set on a boolean field it provide the functionality to hide/show the filelds passed as parameter 
# float_field: if set on a float field, renders the standard input text as a double select.
#              It has two possible configurations:
#              - digits_before_comma, digits_after_comma respectively the number of digits before the comma and after the comma
#              - range_before_comma, range_after_comma ( es range_before_comma: [3,9] or range_after_comma: [90,300] )
#                The 'range' configuration display only the values contained in the range of values passed as parameters                               
#
# autocomplete: by default it offers the autocomplete functionality on the same field you are configuring and on the table the module is generated on
#               It has the following parameters:
#                      -    default_values : list of default values
#                      -    table: name of the table from which you would retrieve the values 
#                      -    select_field: the field name to use a select into dql query       
#                            
# relation: renders a 1:N relation as a sortable list
#           There are 4 required parameters:
#             class: name of the model related to the field
#             relation_id: name of the database field
#             main_field:  primary field to display
#             desc_field:  secondary field to display
#             edit_route:  routing rule associated to the edit link   
# nn_relation:
#          hide_new_button: if set to true hide the 'create new item' button
#          hide_associate_button: if set to true hide the 'Associate existing item button'
#          class: name of the NN relation model
#          outbound_class: class name defined on the NN model table
#          outbound_class_alias: foreign alias defined on the NN model table
#          outbound_relation_id: relation field name related on the model to display 
#          relation_id: relation field name related on the model you are handling
#          main_field: primary field to display
#          edit_route:   routing rule associated to the edit link      
#
#
# has_google_map_link: display a link to the google map page under the field label. The link is built with the content of the fileld that 
#                      has this property. To enable this feature the assigned value sould be true
#                      
# color:  provides a form widget that permits to insert into a input (text) a color code chosen from the values list.
#         The list of color values sould be provided as an array
# 
#         eg.  values: [ FF0000, 00FF00, FF00FF, FFFF00]
#
# download_smart_view: replace the standard download file view with two icons: the first for downloading uploaded files and
#                      the second is used to toggle the download widget
# 
# rte: replace a texarea field with the ckeditor. Using the items_to_show configuration is possible to show a list of tools in the toolbar.
#      eg:  items_to_show: ['Underline','PasteFromWord']. The list of available placeholder is reported here: http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar
#
#                         
# GENERAL OPTIONS : options
# enlarge_field_label : increase the size of all the labels with the value passed as parameter 
# set_all_input_size : increase the size of all the input fields of the form with the value passed as parameter
# set_all_textarea_size : increase the size of all the textarea fields of the form with the value passed as parameter
# set_all_select_width : increase the size of all the select fields of the form with the value passed as parameter

 
# TAB SECTION : tabs
# Provide a simple tab interface 
# The input is represented by the block list defined into generator section [form][display]
# label : label of the selected tab
# description : if set display an html block as description under the tab label  
# is_first_tab_to_show : if set to true, render the tab selected the first time the form is shown   


# LIST SECTION: list
# options:  
#   batch_confirm : if present it allows to spacify a confirm dialog ( associated to the batch confirm) with the text passed as parameter
#   date_filter_fields : all the fields present in the list are rendered using the datepicket in the filter section
#   advanced_filters : if set to true all the active filters are shown 
#
# fields: the field list
# inline: If specified it allows to edit from the list two types of fields boolean and text.
#         The available configuration is:
#              fieldName:
#                inline: text (or boolean)
#         NOTE: is required having the 'id' field in the object list. If not necessary in the view the id column could be hided using the option 'hide_id: true'.
#
#         If the field is in relation with the model field, you can specify the relation name and the type of field to point to the related record
#         This kind of configuration can be:
#              fieldName:
#                inline:
#                  type:           text (or boolean)
#                  related_model:  RelationName
#
#  hide_id: if set to true hide the id column			
#  toggle_inline_help: hide all the inline help provided by the admin generator. A help icon will appear under the field label
#
#  go_to_page: if set to true shows the got to page box inside the pagination div
";
	}
	
}

