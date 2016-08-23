$(function() {

    // variables
    var aPChatTimers = [];

    // remove private chat tab
    closePchat = function(id) {
        $('.priv_dock_wrap .priv_chat_tab#pcid'+id).remove();
    }

    // initiate private chat
    initiatePrivateChat = function(id, name) {

        var oPChat = $('.priv_dock_wrap .priv_chat_tab#pcid'+id);
        if (! oPChat.length) { // create new chat dialog
            var sPCTemplate = '<div class="priv_chat_tab" id="pcid'+id+'">'+
'    <div class="priv_title">'+name+'<img src="images/close.png" /></div>'+
'    <div class="priv_conv"></div>'+
'    <div class="priv_input">'+
'        <form class="priv_chat_submit_form">'+
'            <input type="hidden" name="recipient" value="'+id+'" />'+
'            <input type="text" name="message" />'+
'        </form>'+
'    </div>'+
'</div>';

            $('.priv_dock_wrap').append(sPCTemplate);

            // bind onclick at close icon to close form
            $('.priv_chat_tab#pcid'+id+' .priv_title img').bind('click', function() {
                clearTimeout(aPChatTimers[id])
                $('.priv_dock_wrap .priv_chat_tab#pcid'+id).remove();
            });

            // bind onsubmit at input form to send message
            $('.priv_chat_tab#pcid'+id+' .priv_chat_submit_form').bind('submit', function() {
                $.post('index.php', { priv_message: $('.priv_chat_tab#pcid'+id+' .priv_chat_submit_form input[name=message]').val(),
                    recipient: $('.priv_chat_tab#pcid'+id+' .priv_chat_submit_form input[name=recipient]').val() }, 
                    function(data){
                        $('.priv_chat_tab#pcid'+id+' .priv_chat_submit_form input[name=message]').val('');
                        if (data.result == 1) {
                            $('.priv_chat_tab#pcid'+id+' .priv_chat_submit_form .success').fadeIn('slow', function () {
                                $(this).delay(1000).fadeOut('slow'); 
                            }); 
                        } else if (data.result == 2) {
                            $('.priv_chat_tab#pcid'+id+' .priv_chat_submit_form .protect').fadeIn('slow', function () {
                                $(this).delay(1000).fadeOut('slow'); 
                            }); 
                        } else {
                            $('.priv_chat_tab#pcid'+id+' .priv_chat_submit_form .error').fadeIn('slow', function () {
                                $(this).delay(1000).fadeOut('slow'); 
                            }); 
                        }
                    }
                );
                return false; 
            });
        }

        // start collecting private messages
        getPrivateMessages(id);
    }

    // create private messages
    getPrivateMessages = function(iRecipient) {
        $.getJSON('index.php?action=get_private_messages&recipient=' + iRecipient, function(data) {
            if (data.messages) {
                $('.priv_chat_tab#pcid'+iRecipient+' .priv_conv').html(data.messages);
            }

            // get recent chat messages in loop
            aPChatTimers[iRecipient] = setTimeout(function() {
               getPrivateMessages(iRecipient);
            }, 5000);
        });
    }

    // initiate private chats by click 'chat' icon
    $('.profiles .pchat').click(function(event) {
        event.stopPropagation();
        event.preventDefault();

        initiatePrivateChat(this.id, this.title);
    });

    initiateNewChatsPeriodically = function() {
        $.getJSON('index.php?action=check_new_messages', function(data) {

            if (data != undefined && data.id) {
                initiatePrivateChat(data.id, data.name);
            }

            // refresh last nav time
            setTimeout(function(){
               initiateNewChatsPeriodically();
            }, 6000); // 1 mins
        });
    }
    initiateNewChatsPeriodically();
});