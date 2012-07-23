$(function(){
	$('#dialog').dialog({autoOpen:false});
        $('.send-message a').click(function(){
            $('#dialog').dialog('open');
            $('#dialog .username').html($(this).parent().prev('.username').html())
            $('#dialog').find('input[name="recepient"]').val($(this).attr('user'))
            $('#dialog form button').click(function(){
                $('#dialog form').ajaxSubmit(function(data){
                    $('#dialog').dialog('close');
                    alert(data)
                });
                return false;
            })
            return false;
        })
});
