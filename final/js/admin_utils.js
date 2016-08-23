$(function() {

    // block member
    $('#block').click(function(event) {
        var oBtn = $(this);
        $.post('actions.php', { pid: oBtn.attr('pid'), action: 'block' },
            function(data){
                if (data != undefined) {
                    oBtn[0].innerHTML = (data == 2) ? 'Unblock this member' : 'Block this member';
                }
            }
        );
    });
});