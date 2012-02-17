<?php 
use_helper('jQuery');
?>
<div class="overlay" id="overlay" style="display:none;"></div>
<div class="container_15">
        <div class="grid_5 prefix_5 suffix_5"><h1>Inserisci la tua schedina</h1> </div>
        <?php if(!isset($warning) || $warning==""):?>
        <div class="container_15 num_table">
    <?php for ($i=1;$i<=90;$i++):?>
    <div class="grid_1"><?php echo $i;?></div>
    <?php endfor?>
        </div>
        <div class="grid_5 prefix_5 suffix_5">Combinazione giocata: </div>
    <div class="grid_15 number_choosen">
    </div>
        <div class="grid_5 prefix_5 suffix_5 num_table" ><a href="#" onclick="checkAndSaveToDb('<?php echo url_for('@check_save_card');?>','<?php echo $token?>','<?php echo url_for('@superenalotto_card?gameId='.$gameId.'&contestId='.$contestId)?>','<?php echo $gameId?>','<?php echo $contestId?>')">Invia schedina</a> </div>
        <div class="grid_15 alert_msg">
        </div>
        <?php else:?>
        <div class="grid_5 prefix_5 suffix_5 num_table" ><?php echo $warning;?></div>
        <?php endif;?>
</div>
