var appendedNumbers = new Array();
$(".grid_1").live("click", function(){
    //check if we have to add or remove the number...

    if($.inArray($(this).html(), appendedNumbers)!=-1)
    {
        //remove number
        $(this).removeClass('selected');
        if($(".number_"+ $(this).html()).length>0)
        {
            $(".number_"+ $(this).html()).remove();
        }
        appendedNumbers = $.removeFromArray($(this).html(),appendedNumbers);
        $(".alert_msg").html("");
    }
    else if(appendedNumbers.length >=20)
    {
        $(".alert_msg").html('Non puoi aggiungere ulteriori numeri');
    }
    else if($.inArray($(this).html(), appendedNumbers)==-1)
    {
        $(".number_choosen").append('<span class="number_'+$(this).html()+'">'+$(this).html()+' </span>');
        appendedNumbers.push($(this).html());
        $(".alert_msg").html("");
        $(this).addClass('selected');
    }
    
});


$.removeFromArray = function(value, arr) {
    arr = jQuery.grep(arr, function(val) {
        return val != value;
    });
    return arr;
};

function checkAndSaveToDb(url_call,token,partial_url,game_id,contest_id)
{
    if(appendedNumbers.length >=6)
    {
        $('#overlay').fadeIn('slow',function(){});
        $.ajax({
            url: url_call,
            type: "POST",
            data: {
                numbersPlayed : appendedNumbers.join(','),
                gameId: game_id,
                contestId: contest_id,
                superstar: 46,
                token: token
            },    
            success: function(data){
                $('#overlay').fadeOut('slow');
                if(data == 'OK')
                {
                    $('.num_table').remove();
                    $('.alert_msg').html('La tua schedina è stata inserita correttamente nel sistema. Puoi adesso <a href="'+partial_url+'">inserirne una nuova</a> oppure tornare alla home');
                }
                else
                {
                    $('.alert_msg').html('Si è verificato un errore nel salvataggio della scheda, controlla che i dati siano corretti!');
                }
            },
            error: function()
            {
                $('#overlay').fadeOut('slow');
                alert('Attacco al sistema,non fare il furbo!');
            }
        });
    }
    else
    {
        $(".alert_msg").html('Bisogna giocare almeno sei numeri!');
    }
}