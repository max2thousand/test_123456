var appendedNumbers = new Array();
function appendNumbers()
{
    $(".grid_1").live("click", function(){
    //check if we have to add or remove the number...
    if($.inArray($(this).html(), appendedNumbers)!=-1)
    {
        //remove number
        $(this).removeClass('selected');
        $(".number_"+ $(this).html()).detach();
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
}

$.removeFromArray = function(value, arr) {
    arr = jQuery.grep(arr, function(val) {
  return val != value;
});
return arr;
};

function checkAndSaveToDb(url_call)
{
    if(appendedNumbers.length >=6)
    {
        $.ajax({
            url: url_call,
            type: "POST",
            data: {
                numbersPlayed : appendedNumbers.join(','),
                gameId: 1,
                contestId: 1,
                superstar: 46
            },    
            success: function(data){
                alert(data);
        }
        });
    }
    else
    {
        $(".alert_msg").html('Bisogna giocare almeno sei numeri!');
    }
}