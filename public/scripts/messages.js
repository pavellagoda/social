$(function(){
	$('#dialog').modal({show:false});
        $('.send-message a').click(function(){
            console.log($('#dialog'))
            $('#dialog').modal('show');
            $('#dialog .username').html($(this).parent().prev('.username').html())
            $('#dialog').find('input[name="recepient"]').val($(this).attr('user'))
            $('#dialog .modal-footer a').click(function(){
                $('#dialog form').ajaxSubmit(function(data){
                    $('#dialog').modal('hide');
                    alert(data)
                });
                return false;
            })
            return false;
        })
});
