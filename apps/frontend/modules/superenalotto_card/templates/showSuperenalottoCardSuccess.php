<?php 
use_helper('jQuery');
echo javascript_tag('
    appendNumbers();
    ');?>
<div class="container_15">
        <div class="grid_5 prefix_5 suffix_5"><h1>Inserisci la tua schedina</h1> </div>

    <?php for ($i=1;$i<=90;$i++):?>
    <div class="grid_1"><?php echo $i;?></div>
    <?php endfor?>
        <div class="grid_5 prefix_5 suffix_5">Combinazione giocata: </div>
    <div class="grid_15 number_choosen">
    </div>
         <div class="grid_5 prefix_5 suffix_5"><a href="#" onclick="checkAndSaveToDb('<?php echo url_for('@check_save_card');?>')">Invia schedina</a> </div>
        <div class="grid_15 alert_msg">
        </div>
</div>
