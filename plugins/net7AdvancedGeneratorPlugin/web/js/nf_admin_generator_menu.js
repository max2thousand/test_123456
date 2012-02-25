function nf_admin_generator_menu_hide_all() {
	$('div#sf_admin_container div#sf_admin_content form fieldset ').hide();
	$('a.nf_admin_generator_menu_item').removeClass('current');
}

function nf_admin_generator_menu_show(id) { 
    $.cookie('nf_admin_generator_menu_current',id);
    nf_admin_generator_menu_hide_all();
    $("#sf_fieldset_"+id).show();
    $("#nf_admin_generator_menu_item_"+id).addClass('current')
}

function nf_admin_generator_menu_init(first) {
    var current = $.cookie('nf_admin_generator_menu_current');
    if (current != null ) {
	  nf_admin_generator_menu_show(current); 
    } else { 
    nf_admin_generator_menu_show(first);
    }
}

