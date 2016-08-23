$(function(){
    var width = 0;

    $('.votes_buttons a').hover(
        function () {
            width = $(this).attr('id') * 64;
            $('.votes_active').width(width + 'px');
        },
        function () {
            width = $(this).parent().attr('val') * 64;
            $('.votes_active').width(width + 'px');
        }
    );

    $('.votes_buttons a').click(function () {
        var idVal = $(this).parent().attr('id');
        var iCnt = $(this).parent().attr('cnt');
        var voteVal = $(this).attr('id');
        var iSelWidth = voteVal * 64;

        $.post('actions.php', { id: idVal, vote: voteVal, action: 'put_vote' },
            function(data){
                if (data == 1) {
                    width = iSelWidth;
                    $('.votes_active').width(iSelWidth + 'px');
                    iCnt = parseInt(iCnt) + 1;
                    $('.votes_main span b').text(iCnt);
                    $('.votes_buttons').attr('val', voteVal);
                }
            }
        );
    });
});
