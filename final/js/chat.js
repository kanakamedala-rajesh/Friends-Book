$(function() {
    getMessages = function() {
        $.getJSON('index.php?action=get_last_messages&room='+iRoom, function(data) {
            if (data.messages) {
                $('.chat_messages').html(data.messages);
            }

            // get recent chat messages in loop
            setTimeout(function() {
               getMessages();
            }, 5000);
        });
    }
    getMessages();

    $('.chat_submit_form').submit(function() { 
        $.post('index.php', { message: $('.chat_submit_form input[name=message]').val(), room: $('.chat_submit_form input[name=room]').val() }, 
            function(data){
                if (data.result == 1) {
                    $('.chat_submit_form .success').fadeIn('slow', function () {
                        $(this).delay(1000).fadeOut('slow'); 
                    }); 
                } else if (data.result == 2) {
                    $('.chat_submit_form .protect').fadeIn('slow', function () {
                        $(this).delay(1000).fadeOut('slow'); 
                    }); 
                } else {
                    $('.chat_submit_form .error').fadeIn('slow', function () {
                        $(this).delay(1000).fadeOut('slow'); 
                    }); 
                }
            }
        );
        return false; 
    });

    // Update last navigation time feature
    updateLastNav = function() {
        $.getJSON('index.php?action=update_last_nav', function() {

            // refresh last nav time
            setTimeout(function(){
               updateLastNav();
            }, 180000); // 3 mins
        });
    }
    updateLastNav();
});