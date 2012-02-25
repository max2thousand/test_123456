<?php

class AdminGeneratorMenu {

    private $filters;
    private $modelName;
    private $multipleSelectKeys;
    static $moduleName = '';

    /**
     * Inlcudes all helpers
     * @return unknown_type
     */
    private static function assets() {
        // Helper
        // jquery cookie plugin
        use_javascript('/net7AdvancedGeneratorPlugin/js/jq/jquery-1.5.1.min.js');

        use_javascript('/net7AdvancedGeneratorPlugin/js/jquery.cookie.js');


        // Stylesheets
        use_stylesheet('/net7AdvancedGeneratorPlugin/css/net7AdvancedGenerator.css');

        // Javascripts
        use_javascript('/net7AdvancedGeneratorPlugin/js/nf_admin_generator_menu.js');
        use_javascript('/net7AdvancedGeneratorPlugin/js/advancedGenerator.js');
    }

    private static function initAutocomplete() {
        use_javascript('/net7AdvancedGeneratorPlugin/js/jq/plugins/ui/jquery-ui-1.8.13.min.js');
        use_javascript('/net7AdvancedGeneratorPlugin/js/jq/plugins/ui/jquery.ui.widget.js');
        use_javascript('/net7AdvancedGeneratorPlugin/js/jq/plugins/ui/jquery.ui.core.js');

        use_javascript('/net7AdvancedGeneratorPlugin/js/jq/plugins/ui/jquery.ui.autocomplete.js');
        //use_stylesheet('/net7AdvancedGeneratorPlugin/css/jqueryUI/jquery-ui-1.8.13.css');
        use_stylesheet('/net7AdvancedGeneratorPlugin/css/jqueryUI/jquery.ui.autocomplete.css');
    }
    
	private static function initCkEditor() {
        use_javascript('/net7AdvancedGeneratorPlugin/js/ckeditor/ckeditor.js');
        use_javascript('/net7AdvancedGeneratorPlugin/js/ckeditor/adapters/jquery.js');
    }
    
    

    private static function initEditinline() {
        use_javascript('/net7AdvancedGeneratorPlugin/js/jq/plugins/jquery.editinplace.js');
    }

    public static function initForm() {
        self::assets();
        self::initFormConfiguration();
    }

    public static function initList() {
        self::assets();
        self::initListConfiguration();
    }

    private static function initListConfiguration() {
        $generatorFile = sfYaml::load(self::getModulePath() . '/config/generator.yml');
        self::$moduleName = sfInflector::underscore($generatorFile['generator']['param']['model_class']);

        self::generateFilterBar();
        self::procesBatchConfirm($generatorFile);
        self::processDateFilterFields($generatorFile);
        self::processAdvancedFilters($generatorFile);
        self::closeFilterBar();
        self::processListFields($generatorFile);
        self::processGoToPageWidget($generatorFile);
        self::hideId($generatorFile);
    }

    private static function hideId($generatorFile) {
        if (isset($generatorFile['advanced_admin']['list']['options']['hide_id'])) {
            if ($generatorFile['advanced_admin']['list']['options']['hide_id']) {
                echo javascript_tag('$(document).ready(function() {
					$(".sf_admin_list_td_id").hide();
					$(".sf_admin_list_th_id").hide();
				});');
            }
        }
    }

    private static function closeFilterBar() {
        echo '</div>';
    }

    private static function generateFilterBar() {
        echo '<div id="visualizza_filtri"> <input type="button" value="Visualizza Filtri" onclick="$(\'#sf_admin_bar\').toggle()">';
        echo javascript_tag('$(document).ready(function() {
			$("#sf_admin_bar").attr("style", "float:none; display:none;");
		});');
    }

    private static function processAdvancedFilters($generatorFile) {
        if (isset($generatorFile['advanced_admin']['list']['options']['advanced_filters'])) {

            if ($generatorFile['advanced_admin']['list']['options']['advanced_filters'])
            //echo self::addAdvancedFilters(sfContext::getInstance()->getModuleName());
                echo self::addAdvancedFilters(self::$moduleName);
        }
    }

    private static function processGoToPageWidget($generatorFile) {
        if (isset($generatorFile['advanced_admin']['list']['options']['go_to_page'])) {
            if ($generatorFile['advanced_admin']['list']['options']['go_to_page'])
                echo self::addGoToPageWidget(self::$moduleName);
        }
    }

    private static function processDateFilterFields($generatorFile) {
        self::initDatePicker();
        if (isset($generatorFile['advanced_admin']['list']['options']['date_filter_fields'])) {
            foreach ($generatorFile['advanced_admin']['list']['options']['date_filter_fields'] as $filterField) {
                //echo self::bindFilterDatepicker($filterField, sfContext::getInstance()->getModuleName());
                echo self::bindFilterDatepicker($filterField, self::$moduleName);
            }
        }
    }

    private static function procesBatchConfirm($generatorFile) {
        if (isset($generatorFile['advanced_admin']['list']['options']['batch_confirm'])) {
            echo self::addBatchConfirm($generatorFile['advanced_admin']['list']['options']['batch_confirm']);
        }
    }

    private static function initFormConfiguration() {
        $generatorFile = sfYaml::load(self::getModulePath() . '/config/generator.yml');
        self::$moduleName = sfInflector::underscore($generatorFile['generator']['param']['model_class']);

        // initialize the datepicker 
        self::initDatePicker();


        self::processTabs($generatorFile);

        self::processEnlargeFieldLabel($generatorFile);
        self::processSetAllInputSize($generatorFile);
        self::processSetAllSelectWidth($generatorFile);
        self::processSetAllTextareaSize($generatorFile);
        self::processFormFields($generatorFile);
        self::processHideInlineHelp($generatorFile);
    }

    private static function processTabs($generatorFile) {
        if (!empty($generatorFile['advanced_admin']['form']['tabs'])) {
            $menuArray = array();
            $defaultMenuToShow;
            foreach ($generatorFile['advanced_admin']['form']['tabs'] as $key => $field) {
                $menuArray[$key] = $field['label'];
                if (isset($field['is_first_tab_to_show']) && $field['is_first_tab_to_show'] == 'true')
                    $defaultMenuToShow = $key;

                // processing the tab info text 	
                if (isset($field['description']) && $field['description'] != '')
                    self::addTabDescriptionText($field['description'], $key);
            }

            if (!isset($defaultMenuToShow)) {
                $keys = array_keys($menuArray);
                $defaultMenuToShow = $keys[0];
            }

            self::menu($menuArray, $defaultMenuToShow);
        }
    }

    private static function processHideInlineHelp($generatorFile) {
        if (isset($generatorFile['advanced_admin']['form']['options']['toggle_inline_help'])) {
            if ($generatorFile['advanced_admin']['form']['options']['toggle_inline_help']) {
                echo self::hideInlineHelp();
            }
        }
    }

    private static function processEnlargeFieldLabel($generatorFile) {
        if (isset($generatorFile['advanced_admin']['form']['options']['enlarge_field_label'])) {
            echo self::enlargeFieldLabel($generatorFile['advanced_admin']['form']['options']['enlarge_field_label']);
        }
    }

    private static function processSetAllInputSize($generatorFile) {
        if (isset($generatorFile['advanced_admin']['form']['options']['set_all_input_size'])) {
            echo self::setAllInputSize($generatorFile['advanced_admin']['form']['options']['set_all_input_size']);
        }
    }

    private static function processSetAllSelectWidth($generatorFile) {
        if (isset($generatorFile['advanced_admin']['form']['options']['set_all_select_width'])) {
            echo self::setAllSelectWidth($generatorFile['advanced_admin']['form']['options']['set_all_select_width']);
        }
    }

    private static function processSetAllTextareaSize($generatorFile) {
        if (isset($generatorFile['advanced_admin']['form']['options']['set_all_textarea_size'])) {
            echo self::setAllTextareaSize($generatorFile['advanced_admin']['form']['options']['set_all_textarea_size']['cols'], $generatorFile['advanced_admin']['form']['options']['set_all_textarea_size']['rows']);
        }
    }

    private static function processListFields($generatorFile) {
        if (isset($generatorFile['advanced_admin']['list']['fields'])) {
            foreach ($generatorFile['advanced_admin']['list']['fields'] as $field => $value) {
                self::processInlineEdit($generatorFile, $field);
            }
        }
    }

    private static function processFormFields($generatorFile) {
        // to avoid notice
        if (isset($generatorFile['advanced_admin']['form']['fields'])) {
            foreach ($generatorFile['advanced_admin']['form']['fields'] as $field => $value) {
                self::processDateField($generatorFile, $field);
                self::processToggleField($generatorFile, $field);
                self::processFieldWidth($generatorFile, $field);
                self::processSelectWidth($generatorFile, $field);
                self::processDoubleSelectWidth($generatorFile, $field);
                self::processToggleRelatedFields($generatorFile, $field);
                self::processTextareaSize($generatorFile, $field);
                self::processFloatFields($generatorFile, $field);
                self::processAutocomplete($generatorFile, $field);
                self::processLodAutocomplete($generatorFile, $field);
                self::processGmapReference($generatorFile, $field);
                self::processColorViewer($generatorFile, $field);
                self::processRTE($generatorFile, $field);
                self::downloadSmartView($generatorFile, $field);
            }
        }
    }

    public static function processColorViewer($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['color'])) {
            if (is_array($generatorFile['advanced_admin']['form']['fields'][$field]['color']['values'])) {
                self::addColorViewer($field, $generatorFile, $generatorFile['advanced_admin']['form']['fields'][$field]['color']['values']);
            }
        }
    }
    
	public static function processRTE($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['rte'])) {
			self::initCkEditor();        	
         	self::showRTE($field, $generatorFile['advanced_admin']['form']['fields'][$field]['rte']['items_to_show']);
        }
    }

    public static function downloadSmartView($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['download_smart_view'])) {
            if ($generatorFile['advanced_admin']['form']['fields'][$field]['download_smart_view']) {
                self::showDownloadSmartView($field);
            }
        }
    }

    public static function processGmapReference($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['has_google_map_link'])) {
            if ($generatorFile['advanced_admin']['form']['fields'][$field]['has_google_map_link'] == 'true') {
                self::addGmapLink($field, $generatorFile);
            }
            //self::initEditinline();
            //self::addInlineEdit($field, $generatorFile['advanced_admin']['list']['fields'][$field]['inline'], $generatorFile['generator']['param']['model_class']);	
        }
    }

    public static function processInlineEdit($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['list']['fields'][$field]['inline'])) {
            self::initEditinline();
            if (isset($generatorFile['advanced_admin']['list']['fields'][$field]['inline']['type']) &&
                    isset($generatorFile['advanced_admin']['list']['fields'][$field]['inline']['related_model']) &&
                    ($generatorFile['advanced_admin']['list']['fields'][$field]['inline']['type'] == 'text' || $generatorFile['advanced_admin']['list']['fields'][$field]['inline']['type'] == 'boolean')) {
                self::addInlineEdit($field, $generatorFile['advanced_admin']['list']['fields'][$field]['inline']['type'], $generatorFile['generator']['param']['model_class'], $generatorFile['advanced_admin']['list']['fields'][$field]['inline']['related_model']);
            } else {
                self::addInlineEdit($field, $generatorFile['advanced_admin']['list']['fields'][$field]['inline'], $generatorFile['generator']['param']['model_class'], null);
                
            }
            
        }
    }

    public static function process1NWidget($field, $objectId) {
        $generatorFile = sfYaml::load(self::getModulePath() . '/config/generator.yml');
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]) &&
                key_exists('relation', $generatorFile['advanced_admin']['form']['fields'][$field])) {
            $params = $generatorFile['advanced_admin']['form']['fields'][$field]['relation'];
            include_component('advanced_generator_plugin_utilities', 'showRelation', array('params' => $params, 'objId' => $objectId));
            //print_r($params);die;
        }
    }

    public static function processNNWidget($field, $objectId) {
        $generatorFile = sfYaml::load(self::getModulePath() . '/config/generator.yml');
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]) &&
                key_exists('nn_relation', $generatorFile['advanced_admin']['form']['fields'][$field])) {
            $params = $generatorFile['advanced_admin']['form']['fields'][$field]['nn_relation'];
            include_component('advanced_generator_plugin_utilities', 'showNNRelation', array('params' => $params, 'objId' => $objectId));
            //print_r($params);die;
        }
    }
    
    private static function processAutocomplete($generatorFile, $field) {
        if (key_exists('autocomplete', $generatorFile['advanced_admin']['form']['fields'][$field])) {
            $autocomplete = $generatorFile['advanced_admin']['form']['fields'][$field]['autocomplete'];
            //$autocompleteFieldId = $moduleName = sfContext::getInstance()->getModuleName().'_'.$field;
            $autocompleteFieldId = self::$moduleName . '_' . $field;

            self::initAutocomplete();
            $table = (isset($autocomplete['table'])) ? sfInflector::camelize($autocomplete['table']) : $generatorFile['generator']['param']['model_class'];
            $selectField = (isset($autocomplete['select_field'])) ? $autocomplete['select_field'] : $field;

            $defaultValues = (isset($autocomplete['default_values'])) ? $autocomplete['default_values'] : array();
            self::addAutocomplete($table, $selectField, $autocompleteFieldId, $defaultValues);
        }
    }

    private static function processLodAutocomplete($generatorFile, $field) {
        if (key_exists('lod_autocomplete', $generatorFile['advanced_admin']['form']['fields'][$field])) {
            // plugin not enabled
            if (!array_search('net7LodPlugin', sfContext::getInstance()->getConfiguration()->getPlugins()))
                return;
            // module not enabled
            if (!array_search('net7LodPluginConceptAutocomplete', sfConfig::get('sf_enabled_modules')))
                return;

            $autocomplete = $generatorFile['advanced_admin']['form']['fields'][$field]['lod_autocomplete'];
            //$autocompleteFieldId = $moduleName = sfContext::getInstance()->getModuleName().'_'.$field;
            $autocompleteFieldId = self::$moduleName . '_' . $field;
            self::initAutocomplete();
            $filter = $autocomplete['filter'];

            self::addLodAutocomplete($filter, $autocompleteFieldId, $field);
        }
    }

    private static function processToggleRelatedFields($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['toggle_fields'])) {
            //$moduleName = sfContext::getInstance()->getModuleName();
            $moduleName = sfInflector::underscore($generatorFile['generator']['param']['model_class']);
            if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['toggle_fields']['select_values'])) {
                self::toggleSelectField($moduleName . '_' . $field, $generatorFile['advanced_admin']['form']['fields'][$field]['toggle_fields']['fields'], $generatorFile['advanced_admin']['form']['fields'][$field]['toggle_fields']['select_values']);
            } else
                self::toggleBooleanField($moduleName . '_' . $field, $generatorFile['advanced_admin']['form']['fields'][$field]['toggle_fields']['fields']);
        }
    }

    private static function processFloatFields($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['float_field'])) {
            //$moduleName = sfContext::getInstance()->getModuleName();
            $moduleName = sfInflector::underscore($generatorFile['generator']['param']['model_class']);
            $digitsBeforeComma = (isset($generatorFile['advanced_admin']['form']['fields'][$field]['float_field']['digits_before_comma'])) ? $generatorFile['advanced_admin']['form']['fields'][$field]['float_field']['digits_before_comma'] : '';
            $digitsAfterComma = (isset($generatorFile['advanced_admin']['form']['fields'][$field]['float_field']['digits_after_comma'])) ? $generatorFile['advanced_admin']['form']['fields'][$field]['float_field']['digits_after_comma'] : '';
            $rangeBeforeComma = (isset($generatorFile['advanced_admin']['form']['fields'][$field]['float_field']['range_before_comma'])) ? $generatorFile['advanced_admin']['form']['fields'][$field]['float_field']['range_before_comma'] : '';
            $rangeAfterComma = (isset($generatorFile['advanced_admin']['form']['fields'][$field]['float_field']['range_after_comma'])) ? $generatorFile['advanced_admin']['form']['fields'][$field]['float_field']['range_after_comma'] : '';

            self::renderFloatField($moduleName . '_' . $field, $digitsBeforeComma, $digitsAfterComma, $rangeBeforeComma, $rangeAfterComma);
        }
    }

    private static function processTextareaSize($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['textarea_size'])) {
            //$fieldId = sfContext::getInstance()->getModuleName().'_'.$field;
            $fieldId = sfInflector::underscore($generatorFile['generator']['param']['model_class']) . '_' . $field;
            echo self::setTextareaSize($fieldId, $generatorFile['advanced_admin']['form']['fields'][$field]['textarea_size']['rows'], $generatorFile['advanced_admin']['form']['fields'][$field]['textarea_size']['cols']);
        }
    }

    private static function processSelectWidth($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['select_width'])) {
            //$fieldId = sfContext::getInstance()->getModuleName().'_'.$field;
            $fieldId = sfInflector::underscore($generatorFile['generator']['param']['model_class']) . '_' . $field;
            echo self::setSelectWidth($fieldId, $generatorFile['advanced_admin']['form']['fields'][$field]['select_width']);
        }
    }

    private static function processDoubleSelectWidth($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['double_select_width'])) {
            //$fieldId = sfContext::getInstance()->getModuleName().'_'.$field;
            $fieldId = sfInflector::underscore($generatorFile['generator']['param']['model_class']) . '_' . $field;
            echo self::setSelectWidth($fieldId, $generatorFile['advanced_admin']['form']['fields'][$field]['double_select_width']);
            echo self::setSelectWidth('unassociated_' . $fieldId, $generatorFile['advanced_admin']['form']['fields'][$field]['double_select_width']);
        }
    }

    private static function processDateField($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['has_date_picker'])) {
            if ($generatorFile['advanced_admin']['form']['fields'][$field]['has_date_picker'] == 'yes') {
                //echo $generatorFile['advanced_admin']['form']['fields'][$field]['year_range'];die;
                if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['year_range']))
                    echo self::bindDatepicker($field, sfInflector::underscore($generatorFile['generator']['param']['model_class']), $generatorFile['advanced_admin']['form']['fields'][$field]['year_range']);
                else
                    echo self::bindDatepicker($field, sfInflector::underscore($generatorFile['generator']['param']['model_class']));
                //echo self::bindDatepicker($field, sfContext::getInstance()->getModuleName(), $generatorFile['advanced_admin']['form']['fields'][$field]['year_range'] );
                //else echo self::bindDatepicker($field, sfContext::getInstance()->getModuleName());
            }
        }
    }

    private static function processFieldWidth($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['width'])) {
            //$fieldId = sfContext::getInstance()->getModuleName().'_'.$field;
            $fieldId = sfInflector::underscore($generatorFile['generator']['param']['model_class']) . '_' . $field;
            echo self::setInputSize($fieldId, $generatorFile['advanced_admin']['form']['fields'][$field]['width']);
        }
    }

    private static function processToggleField($generatorFile, $field) {
        if (isset($generatorFile['advanced_admin']['form']['fields'][$field]['toggle'])) {
            //$fieldId = sfContext::getInstance()->getModuleName().'_'.$field;
            $fieldId = sfInflector::underscore($generatorFile['generator']['param']['model_class']) . '_' . $field;
            echo self::toggleField($fieldId);
        }
    }

    private static function getModulePath() {
        $moduleName = sfContext::getInstance()->getModuleName();
        $appPath = sfConfig::get('sf_app_dir');

        $generator = $appPath . '/modules/' . $moduleName . '/config/generator.yml';
        if (file_exists($generator))
            return $appPath . '/modules/' . $moduleName;


        $pc = sfProjectConfiguration::getActive();
        $modulePath = '';
        foreach ($pc->getPluginPaths() as $pluginPath)
            if (file_exists(($modulePath = $pluginPath . '/modules/' . $moduleName) . '/config/generator.yml'))
                break;

        return $modulePath;
    }

    private static function menu($items, $first) {
        if (is_array($items) && count($items) > 0) {
            echo javascript_tag('$(document).ready(function(){nf_admin_generator_menu_init("' . $first . '");});');
            echo '<div id="nf_admin_generator_menu"><ul>';
            foreach ($items as $item => $label) {
                echo '<li>';
                $item_id = 'nf_admin_generator_menu_item_' . $item;
                echo jq_link_to_function($label, 'nf_admin_generator_menu_show("' . $item . '")', 'id=' . $item_id . ' class="nf_admin_generator_menu_item"');
                echo "</li>\n";
            }
            echo '</ul></div>';
        }
    }

    private static function addBatchConfirm($confirmText = '') {
        $confirmText = ($confirmText == '') ? "Are you Sure?" : $confirmText;
        echo javascript_tag('$(document).ready(function(){$(\'input[value="ok"]\').click(function(){return confirm("' . $confirmText . '");});})');
        echo javascript_tag('$(document).ready(function(){$(\'input[value="go"]\').click(function(){return confirm("' . $confirmText . '");});})');
    }

    private static function addGoToPageWidget($confirmText = '') {
        echo javascript_tag('$(document).ready(function(){
			$(".sf_admin_pagination").append("<div class=\"advanced-goto-page\"><form method=\"post\" action=\"' . url_for('@net7_advanced_generator_go_to_page?moduleName=' . self::$moduleName) . '\"><span>go to: </span><input name=\"page\" type=\"text\" size=\"5\" /></form></div>")	
		})');
    }

    public static function forceMenu($item) {
        echo javascript_tag('$(document).ready(function(){nf_admin_generator_menu_show("' . $item . '");})');
    }

    private static function generateColorFunction($colorList, $elementId) {
        $returnHtml = "";
        foreach ($colorList as $color) {
            $returnHtml .= "generateColorDiv('$color', '$elementId');";
        }
        return $returnHtml;
    }

    private static function showDownloadSmartView($field) {
        $elementId = self::$moduleName . '_' . $field;
        $rootId = "sf_admin_form_field_$field";

        $downloadId = "download_$field";
        $uploadId = "upload_$field";


        echo javascript_tag('$(document).ready(function(){
				//console.log($(".' . $rootId . ' > div > div > div > a"));
				$(".' . $rootId . ' > div > div > div > input").hide();
				$(".' . $rootId . ' > div > div > div > label").hide();
				$(".' . $rootId . ' > div > div > div > a").html(\'<img id="' . $downloadId . '" title="Download file" src="/net7AdvancedGeneratorPlugin/images/download.png"/>\');
				
				$(".' . $rootId . ' > div > div > div").before(\'<img id="' . $uploadId . '" title="Upload file" src="/net7AdvancedGeneratorPlugin/images/upload.png"/>\');
				
				$("#' . $uploadId . '").click(function(){
					$(".' . $rootId . ' > div > div > div > input").toggle();
					$(".' . $rootId . ' > div > div > div > label").toggle();
				});
				
				$("#' . $downloadId . '").click(function(){
					$(".' . $rootId . ' > div > div > div > a").click();
				});
		});');
    }

    private static function addColorViewer($field, $generatorFile, $colorList) {
        $elementId = self::$moduleName . '_' . $field;

        echo javascript_tag('$(document).ready(function(){
				$("#' . $elementId . '").attr("readonly", "readonly");
				
				elm = $(\'<div class="tool-container"></div>\');
				$(elm).append(\'<img src="/net7AdvancedGeneratorPlugin/images/color_view.gif" title="Show color popup" id="color_popup_' . $field . '" class="color-popup">\');
				$(elm).append(\'<img src="/net7AdvancedGeneratorPlugin/images/reset.png" id="color_reset_' . $field . '" title="Reset color" class="color-reset">\');
				
				$("#' . $elementId . '").parent().append(elm);
				
				//$("#' . $elementId . '").parent().append(\'<img src="/net7AdvancedGeneratorPlugin/images/color_view.gif" title="Show color popup" id="color_popup_' . $field . '" class="color-popup">\');
				//$("#' . $elementId . '").parent().append(\'<img src="/net7AdvancedGeneratorPlugin/images/reset.png" id="color_reset_' . $field . '" title="Reset color" class="color-reset">\');
				
				$("#' . $elementId . '").parent().append(\'<div class="colors-container" id="colors_container_' . $elementId . '"></div>\');

				$("#color_popup_' . $field . '").click(function(){
					showColorPopup("' . $elementId . '", "' . url_for('@net7_advanced_generator_show_color_popup') . '");
					return false;
				});
				$("#color_reset_' . $field . '").click(function(){
					$("#' . $elementId . '").val("");
					return false;
				});
				' . self::generateColorFunction($colorList, $elementId) . '
		});');
    }

    private static function hideInlineHelp() {
        $link = "";
        echo javascript_tag('$(document).ready(function(){
			$(".sf_admin_form_row div label").each(function(){
				if ($(this).parent().find(".help").html()){
					var toggleLink = "<a class=\'help-toggle\' href=\'#\' id=\'help_link_" + $(this).attr("for") + "\' > <img title=\'Toggle Inline help\' src=\'/net7AdvancedGeneratorPlugin/images/help.png\'/> </a>";
					$(this).parent().append(toggleLink);
					var innerFor =  $(this).attr("for");
					
					// hide the current help
					$("#help_link_" + innerFor ).parent().find(".help").hide();
					
					// toggel the help
					$("#help_link_" + innerFor).click(function(){
						//console.log($("#help_link_" + innerFor).parent());
						$("#help_link_" + innerFor ).parent().find(".help").toggle();
						return false;
					});
				}
			});
		});');
    }

    private static function addGmapLink($field, $generatorFile) {
        $moduleName = sfInflector::underscore($generatorFile['generator']['param']['model_class']);
        $item = $moduleName . '_' . $field;
        $link = "<a href='#' id='gmap_link_" . $field . "' target='_blank' class=\'advanced-generator-map\'> <img width=\'24\'src='/net7AdvancedGeneratorPlugin/images/map.png' /> </a>";
        echo javascript_tag('$(document).ready(function(){
				$("#' . $item . '").parent().append("' . $link . '");
				$("#gmap_link_' . $field . '").click(function(){
					var mapLinkString = "http://maps.google.com/?q=" + $("#' . $item . '").val();
					$("#gmap_link_' . $field . '").attr("href", mapLinkString);	
				});
			});');
    }

    private static function addInlineEdit($field, $type, $modelClass, $relatedModel = null) {
        $module = self::$moduleName;

        if ($type == 'text') {
            echo javascript_tag('$(document).ready(function(){
					$(".sf_admin_list_td_' . $field . '").each(function(){
						var recordValue = $(this).parent().find(".sf_admin_list_td_id a").html();
						var htmlToReplace = "<p class=\"inline-editable\" id=\"" + recordValue + "\">" + jQuery.trim($(this).html()) + "</p>";
						$(this).html(htmlToReplace);
						$(".inline-editable").editable("' . url_for('@net7_advanced_generator_update_editinline_field?modelName=' . $modelClass . '&fieldName=' . $field . '&relatedModel=' . $relatedModel) . '", {
		        			 indicator : "Saving...",
					         tooltip   : "Click to edit..."
						});
					});
				});
			');
        } else {
            echo javascript_tag('$(document).ready(function(){
					$(".sf_admin_list_td_' . $field . '").each(function(){ 
						var recordValue = $(this).parent().find(".sf_admin_list_td_id a").html();
						var value = ($.trim($(this).html()) == "&nbsp;" || $.trim($(this).html()) == "") ? 0 : 1;
						var jsFunction = "return updateBooleanInlineEdit(this, " + value + "," + recordValue + " , \'' . url_for('@net7_advanced_generator_update_editinline_boolean_field?modelName=' . $modelClass . '&fieldName=' . $field . '&relatedModel=' . $relatedModel) . '\');"
						if (value == 1)
							var imgToAdd = "<img src=\"/net7AdvancedGeneratorPlugin/images/check.png\" />";
						else var imgToAdd = "<img src=\"/net7AdvancedGeneratorPlugin/images/cross.png\" />";	
						var htmlToReplace = "<a href=\"#\" onclick=\"" + jsFunction + "\" class=\"inline-bool-editable\" >" + imgToAdd + "</a>";
						$(this).html(htmlToReplace);
					});
				});');
        }
    }

    private static function addLodAutocomplete($filter, $autocompleteFieldId, $schemaField) {
        //$module = sfContext::getInstance()->getModuleName();
        $module = self::$moduleName;

        echo javascript_tag('$(document).ready(function(){  
				// elimino la select e formo un input text hidden e un text field su cui fare autocomplete
				
				selParent = $( "#' . $autocompleteFieldId . '" ).parent();
				var conceptId = $( "#' . $autocompleteFieldId . '" ).val();
				$( "#' . $autocompleteFieldId . '" ).remove();	
				selParent.append("<input type=\'hidden\' id=\'' . $autocompleteFieldId . '\' name=\'' . $module . '[' . $schemaField . ']\'/>");
				selParent.append("<input type=\text\' id=\'' . $autocompleteFieldId . '_autocomplete\' name=\'' . $module . '[' . $schemaField . ']\'/>");
				selParent.append("<input type=\'button\' id=\'' . $autocompleteFieldId . '_button\' value=\'Aggiungi Concept\' style=\'display: none;\'/>");
				
				// creo un nuovo lod concept				
				$("#' . $autocompleteFieldId . '_button").click(function(){
					$.ajax({
		    			url: "' . sfContext::getInstance()->getRouting()->generate('net7_lod_concept_create_new') . '",
	                	dataType: "json",
	                	data: {
	                    	name: $("#' . $autocompleteFieldId . '_autocomplete").val(),
	                    	type: "' . $filter . '"
	                	},
	                	type: "post",
	                	success: function(data) {
	                		hideAddLodEntityButton("' . $autocompleteFieldId . '_button");
	                		$("#' . $autocompleteFieldId . '").val(data.id);
	                	}
            		});
				});
				
				// recupero al primo caricamento il nome del lod concept
				$.ajax({
	    			url: "' . sfContext::getInstance()->getRouting()->generate('net7_lod_concept_get_name') . '",
                	dataType: "json",
                	data: {
                    	id: conceptId
                	},
                	type: "post",
                	success: function(data) {
                		if (data) {
                			$("#' . $autocompleteFieldId . '_autocomplete").val(data.name);
                			$("#' . $autocompleteFieldId . '").val(data.id);
                		}
                	}
            	});
				
            	$( "#' . $autocompleteFieldId . '_autocomplete" ).autocomplete({
					select: function(event, ui) { $("#' . $autocompleteFieldId . '").val(labelIdArray[ui.item.value]); },
					source: function(request, response) {
         				   $.ajax({
                				url: "' . sfContext::getInstance()->getRouting()->generate('net7_lod_concept_autocomplete') . '?f=' . $filter . '",
                				dataType: "json",
                				type: "post",
                				data: {
                    				term: request.term
                				},
                				success: function(data) {
                					if (data.results.length == 0) showAddLodEntityButton("' . $autocompleteFieldId . '_button");
                					else hideAddLodEntityButton("' . $autocompleteFieldId . '_button");
			                        var foo = [];
			                       	window.labelIdArray = Array();
			                        for (i in data.results) {
			                        	foo.push({label: data.results[i], value: data.results[i]});
			                        	labelIdArray[data.results[i]] = i;
			                        }
			                        response(foo);
                				}
            				})
        			}					
				});
				$( "#' . $autocompleteFieldId . '_autocomplete" ).attr("name", "");
			 });');
    }

    private static function addAutocomplete($table, $selectField, $autocompleteFieldId, $defaultValues = array()) {
        $encodedParameters = '';
        foreach ($defaultValues as $value)
            $encodedParameters .= $value . '|||';

        echo javascript_tag('$(document).ready(function(){  
				$( "#' . $autocompleteFieldId . '" ).autocomplete({
					source: function(request, response) {
         				   $.ajax({
                				url: "' . sfContext::getInstance()->getRouting()->generate('net7_advanced_generator_autocomplete_ajax_call') . '?t=' . $table . '&f=' . $selectField . '",
                				dataType: "json",
                				type: "post",
                				data: {
                				    defaultValues: "' . $encodedParameters . '",
                    				term: request.term
                				},
                				success: function(data) {
			                        var foo = [];
			                        for (i in data.results) {
			                        	foo.push({label: data.results[i], value: data.results[i]});
			                        }
			                        response(foo);
                				}
            				})
        			}					
				});
			 });');
    }

    //source: "'.sfContext::getInstance()->getRouting()->generate('net7_advanced_generator_autocomplete_ajax_call').'?t='.$table.'&f='.$selectField.'",

    private static function generateDigitSelect($digits, $selectId, $isBeforeDigits = false) {
        $selectWidth = ($digits == 1) ? 40 : $digits * 20;
        $select = "<select id='" . $selectId . "' style='width:" . $selectWidth . "px;'>";
        $select .= "<option value=''></option>";
        //echo $digits;die;
        for ($i = 0; $i < pow(10, $digits); $i++) {
            $value = (!$isBeforeDigits && $i < 10) ? '0' . $i : $i;
            $select .= "<option value='" . $value . "'>" . $value . "</option>";
        }
        return $select . "</select>";
    }

    private static function generateRangeSelect($since, $limit, $selectId, $isBeforeDigits = false) {
        $selectWidth = (strlen($limit) == 1) ? 50 : strlen($limit) * 25;
        $select = "<select id='" . $selectId . "' style='width:" . $selectWidth . "px;'>";
        $select .= "<option value=''></option>";
        $zeroesToAdd = strlen($limit) - strlen($since);
        for ($i = $since; $i <= $limit; $i++) {
            $value = '';
            //$value = (!$isBeforeDigits && $i < 10) ? '0'.$i : $i;
            if (!$isBeforeDigits) {
                if ($limit <= 10)
                    $value = $i;
                else if ($limit >= 10 && $limit <= 99 && $i < 10)
                    $value = '0' . $i;
                else if ($limit >= 10 && $limit <= 99 && $i > 10)
                    $value = $i;
                else if ($limit > 99) {
                    if (($zeroesToAdd = strlen($limit) - strlen($i)) > 0) {
                        for ($j = 0; $j < $zeroesToAdd; $j++)
                            $value .= '0';
                        $value .= $i;
                    } else
                        $value = $i;
                }
            } else
                $value = $i;
            $select .= "<option value='" . $value . "'>" . $value . "</option>";
        }
        return $select . "</select>";
    }

    private static function renderFloatField($item, $digitsBeforeComma = '', $digitsAfterComma = '', $rangeBeforeComma = '', $rangeAfterComma = '') {
        if ($rangeBeforeComma == '') {
            if ($digitsBeforeComma > 2)
                $digitsBeforeComma = 2;
            if ($digitsAfterComma > 2)
                $digitsAfterComma = 2;

            $firstSelect = self::generateDigitSelect($digitsBeforeComma, $item . '_before_digit', true);
            $secondSelect = self::generateDigitSelect($digitsAfterComma, $item . '_after_digit');
        }
        else {
            $firstSelect = self::generateRangeSelect($rangeBeforeComma[0], $rangeBeforeComma[1], $item . '_before_digit', true);
            $secondSelect = self::generateRangeSelect($rangeAfterComma[0], $rangeAfterComma[1], $item . '_after_digit');
        }

        $onchangeBefore = '$("#' . $item . '_before_digit' . '").change(function(){
			$("#' . $item . '").val($(this).val() + "." + $("#' . $item . '_after_digit' . '").val());
		});';

        $onchangeAfter = '$("#' . $item . '_after_digit' . '").change(function(){
			$("#' . $item . '").val($("#' . $item . '_before_digit' . '").val() + "." + $(this).val());
		});';

        echo javascript_tag('$(document).ready(function(){$("#' . $item . '").hide();
							$("#' . $item . '").parent().prepend("' . $firstSelect . '&nbsp;,&nbsp' . $secondSelect . '");
							' . $onchangeAfter . $onchangeBefore . ' })');
        $floatFix = '';
        if ($rangeAfterComma[1] < 10)
            $floatFix = 'if (floatArray[1]) floatArray[1] = floatArray[1].substring(0,1);';

        echo javascript_tag('$(document).ready(function(){var floatArray = $("#' . $item . '").val().split(".");
													$("#' . $item . '_before_digit' . '").val(floatArray[0]);
													' . $floatFix . ' 
													$("#' . $item . '_after_digit' . '").val(floatArray[1]);});');
    }

    private static function setInputSize($id, $size=50) {
        echo javascript_tag('$(document).ready(function(){$("div#sf_admin_container div.sf_admin_form form div.sf_admin_form_row input#' . $id . '").attr("size",' . $size . ');});');
    }

    private static function toggleField($id) {
        $clearLabel = 'var label = $(\'label[for="' . $id . '"]\').html();$(\'label[for="' . $id . '"]\').html("");$(\'label[for="' . $id . '"]\').parent().find(".content").append($("<br>")).append($("<br>"));';
        //$showButton = '$(\'label[for="'.$id.'"]\').append($("<input type=\"button\" value=\"Show/Hide input\"></input>").attr("onclick", "$(\"#'.$id.'\").toggle()"));';
        $showLink = $clearLabel . '$(\'label[for="' . $id . '"]\').append($("<a href=\"#\" onclick=\"$(\'#' . $id . '\').toggle();return false;\"></a>").html(label)' .
                '.attr("title", "Click here to expand field"));'; //.attr("onclick", ""));';

        echo javascript_tag('$(document).ready(function(){' . $showLink . '$("#' . $id . '").hide(); return false;});');
    }

    private static function toggleBooleanField($id, $fieldsToToggle) {
        $initialCondition = '';
        $onclick = '';
        $show = '';
        $hide = '';
        foreach ($fieldsToToggle as $fieldToToggle) {
            $fieldToToggle = 'sf_admin_form_field_' . $fieldToToggle;
            $initialCondition .= 'if ($(\'#' . $id . '\').is(\':checked\') || $(\'#' . $id . '_1\').is(\':checked\')) { $(".' . $fieldToToggle . '").show(); } else { $(".' . $fieldToToggle . '").hide(); }';
            $onclick .= '$(\'.' . $fieldToToggle . '\').toggle();';
            $show .= '$(\'.' . $fieldToToggle . '\').show();';
            $hide .= '$(\'.' . $fieldToToggle . '\').hide();';
        }

        $toggle = '$("#' . $id . '").attr("onclick","' . $onclick . '");';
        $toggle .= '$("#' . $id . '_1").attr("onclick","' . $show . '");';
        $toggle .= '$("#' . $id . '_0").attr("onclick","' . $hide . '");';

        echo javascript_tag('$(document).ready(function(){' . $initialCondition . $toggle . '});');
    }

    private static function toggleSelectField($id, $fieldsToToggle, $values = array()) {
        $initialCondition = '';
        $onclick = '';
        $arrayDeclaration = 'selectValues' . $id . ' = [';
        foreach ($values as $selectValue)
            $arrayDeclaration .= '"' . $selectValue . '",';

        $arrayDeclaration = substr($arrayDeclaration, 0, -1) . '];';
        $fieldsToToggleJsArray = 'optionsValues' . $id . ' = [';

        foreach ($fieldsToToggle as $fieldToToggle) {
            $fieldsToToggleJsArray .= '"' . $fieldToToggle . '",';

            $fieldToToggle = 'sf_admin_form_field_' . $fieldToToggle;
            //$onclick .= 'if () $(\'.'.$fieldToToggle.'\').toggle();';
        }
        $fieldsToToggleJsArray = substr($fieldsToToggleJsArray, 0, -1) . '];';
        $arrayDeclaration .= $fieldsToToggleJsArray;
        $initialCondition = 'toggleFieldsWithSelect(selectValues' . $id . ',optionsValues' . $id . ', $("#' . $id . '"));';

        $toggle = '$("#' . $id . '").attr("onchange","toggleFieldsWithSelect(selectValues' . $id . ',optionsValues' . $id . ', this);");';
        echo javascript_tag('$(document).ready(function(){' . $arrayDeclaration . $initialCondition . $toggle . '});');
    }

    private static function addTabDescriptionText($text, $menuName) {
        echo javascript_tag('$(document).ready(function(){$("#sf_fieldset_' . $menuName . '").prepend("' . $text . '<br/><br/>");})');
    }
    
 	private static function showRTE($field, $itemsToShow = array()) {
        $items = '';
 		foreach ($itemsToShow as $item ) $items .= '"'.$item.'",'; 
 		echo javascript_tag('$(document).ready(function(){
        	var config = {
				toolbar:
				[
				 ['.$items.']
				]
			};
		 	$("#'.self::$moduleName.'_'.$field.'").ckeditor(config);
			 
 		})');
    }

    private static function setAllInputSize($size=50) {
        echo javascript_tag('$(document).ready(function(){$("div#sf_admin_container div.sf_admin_form form div.sf_admin_form_row input").attr("size",' . $size . ');});');
    }

    private static function setTextareaSize($id, $rows=3, $cols=50) {
        echo javascript_tag('$(document).ready(function(){$("div#sf_admin_container div.sf_admin_form form div.sf_admin_form_row textarea#' . $id . '").attr("rows",' . $rows . ').attr("cols",' . $cols . ');});');
    }

    private static function setAllTextareaSize($rows=3, $cols=50) {
        echo javascript_tag('$(document).ready(function(){$("div#sf_admin_container div.sf_admin_form form div.sf_admin_form_row textarea").attr("rows",' . $rows . ').attr("cols",' . $cols . ');});');
    }

    private static function setAllSelectWidth($size=200) {
        echo javascript_tag('$(document).ready(function(){$("div#sf_admin_container div.sf_admin_form form select").width(' . $size . ');});');
    }

    private static function setSelectWidth($id, $size=200) {
        echo javascript_tag('$(document).ready(function(){$("#' . $id . '").width(' . $size . ');});');
    }

    private static function initDatePicker() {
        use_helper('jQuery');
        use_stylesheet('/net7AdvancedGeneratorPlugin/css/jqueryUI/jquery-ui.css');
        use_javascript('/net7AdvancedGeneratorPlugin/js/jq/plugins/ui/ui.datepicker.js');
        use_javascript('/net7AdvancedGeneratorPlugin/js/dateWidget.js');
    }

    private static function bindDatepicker($fieldName, $moduleName, $yearRange = '') {
        $functionParams = '"' . $fieldName . '", "' . $moduleName . '"';
        if ($yearRange != '')
            $functionParams .= ',"' . $yearRange . '"';

        echo javascript_tag('$(document).ready(function(){initializeCalendarWidget(' . $functionParams . ');});');
    }

    private static function bindFilterDatepicker($fieldName, $moduleName) {
        echo javascript_tag('$(document).ready(function(){initializeFilterCalendarWidgets("' . $fieldName . '", "' . $moduleName . '");});');
    }

    /**
     * Permette di allargare lo spazio disponibile per la label di un imput migliorando la visibilit�
     * @param $size
     */
    private static function enlargeFieldLabel($size = '200') {
        echo javascript_tag('$(document).ready(function(){ $("form label").css("width", "' . $size . 'px"); });');
    }

    public function initShowFilters($moduleName = '') {
        //if ($moduleName == '') $moduleName = sfContext::getInstance()->getModuleName();
        if ($moduleName == '')
            $moduleName = self::$moduleName;

        $this->multipleSelectKeys = array();
        use_helper('jQuery');
        //use_stylesheet('advanced_filter_helper');
        $this->filters = (isset($_SESSION['symfony/user/sfUser/attributes']['admin_module'][$moduleName . '.filters'])) ?
                $_SESSION['symfony/user/sfUser/attributes']['admin_module'][$moduleName . '.filters'] : array();
    }

    private function printMultipleSelect($value = array(), $key) {
        if (is_array($value)) {
            if (!empty($value)) {
                foreach ($value as $filterType => $elem) {
                    if ($elem) {
                        echo '<li class="filterList"><span class="filterName" id="l_list_' . $key . '__' . $elem . '">' . $key . "</span> : <span id='f_list_" . $key . '__' . $elem . "'>" . $elem . '</span></li>';
                        $this->multipleSelectKeys[] = $key . '__' . $elem;
                    }
                }
            }
        }
    }

    private function getValueForMultipleSelect() {
        $prefix = $this->modelName . '_filters_';
        $str = '';

        if (!empty($this->multipleSelectKeys)) {
            foreach ($this->multipleSelectKeys as $key) {
                $keyValue = explode('__', $key);
                $str .= '$("#f_list_' . $key . '").html($("#' . $prefix . $keyValue[0] . '>option[value=\'' . $keyValue[1] . '\']").text());';
                // setting the label
                $str .= '$("#l_list_' . $key . '").html($("label[for=\'' . $prefix . $keyValue[0] . '\']").html());';
            }
        }
        if ($str)
            echo javascript_tag('$(document).ready(function(){ ' . $str . ' });');
    }

    private function showActiveFilters($modelName) { //print_r($this->filters);die;
        $this->modelName = $modelName;
        if ($this->countFilters() > 0) {

            echo '<span id="activeFilters" class="activeFilters"><ul>';
            foreach ($this->filters as $key => $value) {

                if (substr($key, -5) == '_list')
                    $this->printMultipleSelect($value, $key);

                else if (is_array($value)) {
                    if (!empty($value)) {
                        foreach ($value as $filterType => $elem) {
                            if ($elem) {
                                $extraLabelText = '';
                                if ($filterType == 'from' || $filterType == 'to') {
                                    // serve per gestire la label delle date
                                    $extraLabelText = ' ( ' . $filterType . ' )';
                                }
                                if ($elem == 'on')
                                    echo '<li class="filterList"><span class="filterName" name="l_' . $key . '">' . $key . "</span> : is empty = true</li>";
                                else
                                    echo '<li class="filterList"><span class="filterName" name="l_' . $key . '">' . $key . '</span><span class="filterName"> ' . $extraLabelText . "</span> : <span id='f_" . $key . "'>" . $elem . '</span></li>';
                            }
                        }
                    }
                }
                else if ($value) {
                    echo '<li class="filterList"><span class="filterName" id="l_' . $key . '">' . $key . "</span> : <span id='f_" . $key . "'>" . $value . '</span></li>';
                }
            }
            echo '</ul></span>';
            if ($this->modelName)
                $this->getValuesFromFilters();
            if (!empty($this->multipleSelectKeys))
                $this->getValueForMultipleSelect();
        }
    }

    private function getValuesFromFilters() {
        $prefix = $this->modelName . '_filters_';
        $str = '';

        foreach ($this->filters as $key => $value) {
            if (is_array($value)) {
                if (!empty($value)) {
                    foreach ($value as $elem) {
                        if ($elem) {
                            $str .= '$("#f_' . $key . '").html($("#' . $prefix . $key . '").val());';
                            // setting the label
                            $str .= '$("span[name=\'l_' . $key . '\']").html($("label[for=\'' . $prefix . $key . '\']").html());';
                        }
                    }
                }
            } else if ($value) {
                $str .= '$("#f_' . $key . '").html($("#' . $prefix . $key . ' option:selected").text());';
                // setting the label
                $str .= '$("#l_' . $key . '").html($("label[for=\'' . $prefix . $key . '\']").html());';
            }
        }
        if ($str)
            echo javascript_tag('$(document).ready(function(){ ' . $str . ' });');
    }

    private function countFilters() {
        $counter = 0;
        foreach ($this->filters as $key => $value) {
            if (is_array($value)) {
                if (!empty($value)) {
                    foreach ($value as $elem) {
                        if ($elem)
                            $counter++;
                    }
                }
            }
            else if ($value)
                $counter++;
        }
        return $counter;
    }

    private function duplicateResetButton() {
        if ($this->countFilters() > 0) {
            echo '<form id="resetFilters" method="post" action="" style="display:none;"></form>';
            echo javascript_tag('$(document).ready(function(){ $("#resetFilters").attr("action", $("a:contains(\'Reset\')").attr("href"));});');
            echo jq_button_to_function('Reset filtri', '$("#resetFilters").submit()');
        }
    }

    private static function addAdvancedFilters($modelName = '') {
        use_stylesheet('/net7AdvancedGeneratorPlugin/css/net7AdvancedGenerator.css');
        $filterHelper = new AdminGeneratorMenu();
        $filterHelper->initShowFilters();
        $filterHelper->duplicateResetButton();
        $filterHelper->showActiveFilters($modelName);
    }

}

?>