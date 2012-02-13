<form action="<?php echo url_for('@net7_send_test_email')?>" method="post">
	<input type="hidden" value="<?php echo $net7_email_template->id?>" name="id"/>
	<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_name" style="background-color: white;">
       <div style="background-color: white;">
      	  <label for="nf_email_name">Indirizzo di test (invio mail)</label>
      	  <div class="content" style="background-color: white;">
	 		<input type="text" name="personal_email" />
      	  </div>
       </div>
    </div>

	<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_name" style="background-color: white;">
       <div>
      	  <label for="nf_email_name"></label>
      	  <div class="content">
			<input type="submit" value="invia mail" onclick="javascript: return confirm('Attenzione. Una mail verr&agrave; inviata all\'indirizzo indicato.Confermare?')">
      	  </div>
       </div>
    </div>
</form>