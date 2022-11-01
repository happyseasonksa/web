
var channelPrefix = PrefixFromserver;
var userPrefix = PrefixFromserver + "-User-" + $('#CurrentLoginUserId').val() + "-D";    //"Admin";
var token = "";
var identity = "";
var mychannel = "";
var UserList = [];
var MessageList = [];
var UserName = "";
var UserProfile = "";
var totalchannels = 0;
var PreferChannel = "";
var sender = "";
var src = "";
var currentChannel = "";
var useridentity = "";
var twiliochat = (function () {
    var tc = {};
    var $channelList;
    var $inputText;
    var $usernameInput;
    var $statusRow;
    var $connectPanel;
    var $newChannelInputRow;
    var $newChannelInput;
    var $typingRow;
    var $typingPlaceholder;

    $(document).ready(function () {
        function hideTypingStarted(member) {
            $typingPlaceholder.text('');
        }

        debugger;
        $(".btnSend").unbind();

        $('.btnSend').on('click', function () {
            debugger;
            if ($('#txtMsg').val() != '') {
                debugger;
                return Chat.SendMessage($(this));
            }
        });


        $("#txtMsg").unbind();
        $('#txtMsg').on("keydown", function (e) {
            debugger;
            if (e.keyCode === 13)
            {
                if ($('#txtMsg').val() != '') {
                    return Chat.SendMessage($(this));
                }
            }
            else {
                mychannel.typing();
            }
        });

        $("#mediaImage").unbind();
        $("#mediaImage").change(function () {
            var input = $(this)[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    src = e.target.result;
                    Chat.SendImage();
                };
                reader.readAsDataURL(input.files[0]);
                $('.imgDiv').css('display', 'inline-block');
            }
            else {
                $('.imgDiv').css('display', 'none');
            }
        });

        $(".userLi").prop("onclick", null).off("click");
        $('.userLi').on('click', function () {

            return Chat.CheckInChannels($(this));
        });
        $("#inputString").prop("keyup", null).off("click");

        jQuery("#inputString").keyup(function () {
            var filter = jQuery(this).val();
            jQuery(".userLi h3").each(function () {
                if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
                    jQuery(this).parent().parent().hide();
                } else {
                    jQuery(this).parent().parent().show()
                }
            });
        });

        $('.btnUser').on("click", function () {
            return Chat.User($(this));
        });



        tc.$messageList = $('#message-list');
        $channelList = $('#channel-list');
        $inputText = $('#input-text');
        $usernameInput = $('#username-input');
        $statusRow = $('#status-row');
        $connectPanel = $('#connect-panel');
        $newChannelInputRow = $('#new-channel-input-row');
        $newChannelInput = $('#new-channel-input');
        $typingRow = $('#typing-row');
        $typingPlaceholder = $('#typing-placeholder');
        $usernameInput.focus();
        $usernameInput.on('keypress', handleUsernameInputKeypress);
        $inputText.on('keypress', handleInputTextKeypress);
        $newChannelInput.on('keypress', tc.handleNewChannelInputKeypress);
        $('#connect-image').on('click', connectClientWithUsername);
        $('#add-channel-image').on('click', showAddChannelInput);
        $('#leave-span').on('click', disconnectClient);
        $('#delete-channel-span').on('click', deleteCurrentChannel);
    });

    function handleUsernameInputKeypress(event) {
        if (event.keyCode === 13) {
            connectClientWithUsername();
        }
    }

    function handleInputTextKeypress(event) {
        mychannel.typing();
        if (event.keyCode === 13) {
            tc.currentChannel.sendMessage($(this).val());
            event.preventDefault();
            $(this).val('');
        }
        else {
            notifyTyping();
        }
    }

    var notifyTyping = setTimeout(function () {

        mychannel.typing();
    }, 1000);

    tc.handleNewChannelInputKeypress = function (event) {
        if (event.keyCode === 13) {
            tc.messagingClient.createChannel({
                friendlyName: $newChannelInput.val()
            }).then(hideAddChannelInput);
            $(this).val('');
            event.preventDefault();
        }
    };

    function connectClientWithUsername() {
        var usernameText = $usernameInput.val();
        $usernameInput.val('');
        if (usernameText == '') {
            alert('Username cannot be empty');
            return;
        }
        tc.username = usernameText;
        fetchAccessToken(tc.username, connectMessagingClient);
    }

    function fetchAccessToken(username, handler) {
        $.post('/PrimeManager/Chat/GenrateToken', {
            identity: username,
            device: 'browser'
        }, function (data) {
            debugger;
            handler(data);
        }, 'json');
    }

    function connectMessagingClient(tokenResponse) {
        // Initialize the IP messaging client
        tc.accessManager = new Twilio.AccessManager(tokenResponse.token);
        tc.messagingClient = new Twilio.IPMessaging.Client(tc.accessManager);
        updateConnectedUI();
        tc.loadChannelList(tc.joinGeneralChannel);
        tc.messagingClient.on('channelAdded', $.throttle(tc.loadChannelList));
        tc.messagingClient.on('channelRemoved', $.throttle(tc.loadChannelList));
        tc.messagingClient.on('tokenExpired', refreshToken);
    }

    function refreshToken() {
        fetchAccessToken(tc.username, setNewToken);
    }

    function setNewToken(tokenResponse) {
        tc.accessManager.updateToken(tokenResponse.token);
    }

    function updateConnectedUI() {
        $('#username-span').text(tc.username);
        $statusRow.addClass('connected').removeClass('disconnected');
        tc.$messageList.addClass('connected').removeClass('disconnected');
        $connectPanel.addClass('connected').removeClass('disconnected');
        $inputText.addClass('with-shadow');
        $typingRow.addClass('connected').removeClass('disconnected');
    }

    tc.loadChannelList = function (handler) {
        if (tc.messagingClient === undefined) {
            console.log('Client is not initialized');
            return;
        }

        tc.messagingClient.getChannels().then(function (channels) {
            tc.channelArray = tc.sortChannelsByName(channels);
            $channelList.text('');
            tc.channelArray.forEach(addChannel);
            if (typeof handler === 'function') {
                handler();
            }
        });
    };

    tc.joinGeneralChannel = function () {
        console.log('Attempting to join "general" chat channel...');
        if (!tc.generalChannel) {
            // If it doesn't exist, let's create it
            tc.messagingClient.createChannel({
                uniqueName: GENERAL_CHANNEL_UNIQUE_NAME,
                friendlyName: GENERAL_CHANNEL_NAME
            }).then(function (channel) {
                console.log('Created general channel');
                tc.generalChannel = channel;
                tc.loadChannelList(tc.joinGeneralChannel);
            });
        }
        else {
            console.log('Found general channel:');
            setupChannel(tc.generalChannel);
        }
    };

    function setupChannel(channel) {
        // Join the channel
        channel.join().then(function (joinedChannel) {
            console.log('Joined channel ' + joinedChannel.friendlyName);
            leaveCurrentChannel();
            updateChannelUI(channel);
            tc.currentChannel = channel;
            tc.loadMessages();
            channel.on('messageAdded', tc.addMessageToList);
            channel.on('typingStarted', showTypingStarted);
            channel.on('typingEnded', hideTypingStarted);
            channel.on('memberJoined', notifyMemberJoined);
            channel.on('memberLeft', notifyMemberLeft);
            $inputText.prop('disabled', false).focus();
            tc.$messageList.text('');
        });
    }

    tc.loadMessages = function () {
        tc.currentChannel.getMessages(MESSAGES_HISTORY_LIMIT).then(function (messages) {
            messages.forEach(tc.addMessageToList);
        });
    };

    function leaveCurrentChannel()
    {
        if (tc.currentChannel) {
            tc.currentChannel.leave().then(function (leftChannel) {
                console.log('left ' + leftChannel.friendlyName);
                leftChannel.removeListener('messageAdded', tc.addMessageToList);
                leftChannel.removeListener('typingStarted', showTypingStarted);
                leftChannel.removeListener('typingEnded', hideTypingStarted);
                leftChannel.removeListener('memberJoined', notifyMemberJoined);
                leftChannel.removeListener('memberLeft', notifyMemberLeft);
            });
        }
    }

    tc.addMessageToList = function (message) {
        var rowDiv = $('<div>').addClass('row no-margin');
        rowDiv.loadTemplate($('#message-template'), {
            username: message.author,
            date: dateFormatter.getTodayDate(message.timestamp),
            body: message.body
        });
        if (message.author === tc.username) {
            rowDiv.addClass('own-message');
        }

        tc.$messageList.append(rowDiv);
        scrollToMessageListBottom();
    };

    function notifyMemberJoined(member) {
        notify(member.identity + ' joined the channel');
    }

    function notifyMemberLeft(member) {
        notify(member.identity + ' left the channel');
    }

    function notify(message) {
        var row = $('<div>').addClass('col-md-12');
        row.loadTemplate('#member-notification-template', {
            status: message
        });
        tc.$messageList.append(row);
        scrollToMessageListBottom();
    }

    function showTypingStarted(member) {
        $typingPlaceholder.text(member.identity + ' is typing...');
    }



    function scrollToMessageListBottom() {
        tc.$messageList.scrollTop(tc.$messageList[0].scrollHeight);
    }

    function updateChannelUI(selectedChannel) {
        var channelElements = $('.channel-element').toArray();
        var channelElement = channelElements.filter(function (element) {
            return $(element).data().sid === selectedChannel.sid;
        });
        channelElement = $(channelElement);
        if (tc.currentChannelContainer === undefined && selectedChannel.uniqueName === GENERAL_CHANNEL_UNIQUE_NAME) {
            tc.currentChannelContainer = channelElement;
        }
        tc.currentChannelContainer.removeClass('selected-channel').addClass('unselected-channel');
        channelElement.removeClass('unselected-channel').addClass('selected-channel');
        tc.currentChannelContainer = channelElement;
    }

    function showAddChannelInput() {
        if (tc.messagingClient) {
            $newChannelInputRow.addClass('showing').removeClass('not-showing');
            $channelList.addClass('showing').removeClass('not-showing');
            $newChannelInput.focus();
        }
    }

    function hideAddChannelInput() {
        $newChannelInputRow.addClass('not-showing').removeClass('showing');
        $channelList.addClass('not-showing').removeClass('showing');
        $newChannelInput.val('');
    }

    function addChannel(channel) {
        if (channel.uniqueName === GENERAL_CHANNEL_UNIQUE_NAME) {
            tc.generalChannel = channel;
        }
        var rowDiv = $('<div>').addClass('row channel-row');
        rowDiv.loadTemplate('#channel-template', {
            channelName: channel.friendlyName
        });

        var channelP = rowDiv.children().children().first();

        rowDiv.on('click', selectChannel);
        channelP.data('sid', channel.sid);
        if (tc.currentChannel && channel.sid === tc.currentChannel.sid) {
            tc.currentChannelContainer = channelP;
            channelP.addClass('selected-channel');
        }
        else {
            channelP.addClass('unselected-channel')
        }

        $channelList.append(rowDiv);
    }

    function deleteCurrentChannel() {
        if (!tc.currentChannel) {
            return;
        }
        if (tc.currentChannel.sid === tc.generalChannel.sid) {
            alert('You cannot delete the general channel');
            return;
        }
        tc.currentChannel.delete().then(function (channel) {
            console.log('channel: ' + channel.friendlyName + ' deleted');
            setupChannel(tc.generalChannel);
        });
    }

    function selectChannel(event) {
        var target = $(event.target);
        var channelSid = target.data().sid;
        var selectedChannel = tc.channelArray.filter(function (channel) {
            return channel.sid === channelSid;
        })[0];
        if (selectedChannel === tc.currentChannel) {
            return;
        }
        setupChannel(selectedChannel);
    };

    function disconnectClient() {
        leaveCurrentChannel();
        $channelList.text('');
        tc.$messageList.text('');
        channels = undefined;
        $statusRow.addClass('disconnected').removeClass('connected');
        tc.$messageList.addClass('disconnected').removeClass('connected');
        $connectPanel.addClass('disconnected').removeClass('connected');
        $inputText.removeClass('with-shadow');
        $typingRow.addClass('disconnected').removeClass('connected');
    }

    tc.sortChannelsByName = function (channels) {
        return channels.sort(function (a, b) {
            if (a.friendlyName === GENERAL_CHANNEL_NAME) {
                return -1;
            }
            if (b.friendlyName === GENERAL_CHANNEL_NAME) {
                return 1;
            }
            return a.friendlyName.localeCompare(b.friendlyName);
        });
    };

    return tc;
})();


$(document).ready(function () {
    setTimeout(function () { $("#MainThrobberImage").show(); }, 1000);
    setTimeout(function () { $("#MainThrobberImage").show(); Chat.InitializeChat(); }, 5000);

});
function SetUsers() {
    if (UserList.length > 0) {


        $.ajax({
            url: baseUrl + '/PrimeManager/Chat/SetUsers',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify({ model: UserList, TimeZone: Intl.DateTimeFormat().resolvedOptions().timeZone }),
            success: function (results, message) {

                if (results.Status == 1) {
                    $('.userListingDiv').html(results.Results[0]);

                    if (UserProfile != null && UserProfile != "") {
                        $('#defaultProfilePic').attr('src', UserProfile);
                    }


                    $('#defaultUserName').text(UserName);

                    if (results.Results[1] != "0") {
                        Chat.SetUpInitialChannel(results.Results[1]);

                    }
                    Bind();
                }
                else {
                    ErrorToast('Zittron', results.Message);
                }
            },
            error: function (results, message) {

                ErrorToast('Zittron', results);
            }
        });
    }
}

function SetMessages() {
    debugger;
    $('#defaultProfilePic').attr('src', UserProfile + "?v3");
    $('#defaultUserName').text(UserName);
    debugger;
    $.ajax({
        url: baseUrl + '/PrimeManager/Chat/SetMessages',
        type: 'POST',

        cache: false,
        processData: false,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify({ model: MessageList, TimeZone: Intl.DateTimeFormat().resolvedOptions().timeZone }),
        success: function (results, message) {
            $('.messageListingDiv').html(results.Results[0]);

            if (UserProfile != null && UserProfile != "") {
                $('#defaultProfilePic').attr('src', UserProfile);
            }

            $('#defaultUserName').text(UserName);
            $('.btnUser').on("click", function () {
                return Chat.User($(this));
            });
            BindM();
            var objDiv = document.getElementsByClassName("message-body");
            objDiv[1].scrollTop = objDiv[1].scrollHeight;
            $("#MainThrobberImage").hide();
        },
        error: function (results, message) {

            ErrorToast('Zittron', results);
        }
    });


}

function RefreshMessages() {
    $('#defaultProfilePic').attr('src', UserProfile + "?v3");
    $('#defaultUserName').text(UserName);

    $.ajax({
        url: baseUrl + '/PrimeManager/Chat/RefreshMessages',
        type: 'POST',

        cache: false,
        processData: false,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify(MessageList),
        success: function (results, message) {
            $('.text-body').html(results.Results[0]);
            $('.text-body').scrollTop = $('.text-body')[0].scrollHeight;
            var objDiv = document.getElementsByClassName("message-body");
            objDiv[1].scrollTop = objDiv[1].scrollHeight;
            if ($($('.text-body')[0].lastElementChild).find("small")[0] != undefined) {
                $('ul a li.active .head .ago')[0].textContent = $($('.text-body')[0].lastElementChild).find("small")[0].textContent;
            }
            //$('#defaultProfilePic').attr('src', UserProfile);
            //$('#defaultUserName').text(UserName);
            //BindM();
        },
        error: function (results, message) {

            ErrorToast('Zittron', results);
        }
    });


}

function SetUpChannelEvents() {
    debugger;
    //mychannel.removeListener('messageAdded');
    if (mychannel._events.messageAdded.length == 2)
    {
        mychannel.on('messageAdded', function (message) {
            var d = new Date();
            var hour = parseInt(d.getHours());
            var ampm = "";
            if (hour > 12) {
                hour = hour - 12;
                ampm = "PM";
            }
            else {
                ampm = "AM";
            }
            var hours = hour > 9 ? hour : "0" + hour;
            var mins = parseInt(d.getMinutes()) > 9 ? d.getMinutes() : "0" + d.getMinutes();
            var text = message.state.body;
            var IsMe = message.state.author.includes(userPrefix);
            var Timestamp = message.state.timestamp;
            var userId = message.channel.uniqueName.replace(channelPrefix + "-CH-" + identity + "-", '');
            var user = message.channel.attributes.User.FullName;
            debugger;

            if (message.state.media != null) {
                message.state.media.getContentUrl().then(function (urls) {
                    if (IsMe) {
                        $('.text-body').append(' <li class="left"> <div class="imgDiv"><a href="' + urls + '" download><img src="' + urls + '" style="width:100%;" /></a></div><small>' + hours + ':' + mins + ' ' + ampm + '</small></li>')
                        $('#Last_Msg_' + userId).text('You sent a photo.');
                    }
                    else {
                        $('.text-body').append(' <li class="right"> <div class="imgDiv"><a href="' + urls + '" download><img src="' + urls + '" style="width:100%;" /></a></div><small>' + hours + ':' + mins + ' ' + ampm + '</small></li>')
                        $('#Last_Msg_' + userId).text(user + ' sent you a photo.');
                    }

                });
            }
            else {
                if (text.length > 50) {
                    $('#Last_Msg_' + userId).text(text.substring(0, 50) + "...");
                }
                else {
                    $('#Last_Msg_' + userId).text(text);
                }
                if (IsMe) {
                    $('.text-body').append(' <li class="right"><p>' + text + '</p><small>' + hours + ':' + mins + ' ' + ampm + '</small></li>')
                }
                else {
                    $('.text-body').append(' <li class="left"><p>' + text + '</p><small>' + hours + ':' + mins + ' ' + ampm + '</small></li>')
                }
            }
            //$('#New_Count_' + userId).removeClass('messageCount');
            //$('#New_Count_' + userId).val('');
            $('#Block_' + userId).parent().prepend($('#Block_' + userId));
            $('#Ago_For_' + userId).text(hours + ':' + mins + ' ' + ampm);
            var objDiv = document.getElementsByClassName("message-body");
            objDiv[1].scrollTop = objDiv[1].scrollHeight;
        });
    }
    mychannel.on('typingStarted', showTypingStarted);
    //mychannel.on('typingEnded', hideTypingStarted);
    mychannel.on('tokenExpired', refreshToken);

}

function refreshToken() {
    Chat.RegenrateToken();
}

function showTypingStarted(member) {

    if (member.channel.uniqueName == currentChannel) {
        $('#typing').text('typing ...');
    }
}



function SetUpGeneralChannelEvent(mychannels) {
    debugger;
    mychannels.on('messageAdded', function (message) {

        if (mychannels.uniqueName != currentChannel) {
            var d = new Date();
            var hour = parseInt(d.getHours());
            var ampm = "";
            if (hour > 12) {
                hour = hour - 12;
                ampm = "PM";
            }
            else {
                ampm = "AM";
            }
            var hours = hour > 9 ? hour : "0" + hour;
            var mins = parseInt(d.getMinutes()) > 9 ? d.getMinutes() : "0" + d.getMinutes();
            var text = message.state.body;
            var IsMe = message.state.author.includes(userPrefix);
            var Timestamp = message.state.timestamp;
            var userId = message.channel.uniqueName.replace(channelPrefix + "-CH-" + identity + "-", '');
            var user = message.channel.attributes.User.FullName;
            if (message.state.media != null) {
                message.state.media.getContentUrl().then(function (urls) {
                    var newcount = $('#New_Count_' + userId).text();
                    if (newcount == "") {
                        newcount = 1;
                    }
                    else {
                        newcount = parseInt(newcount) + 1;
                    }

                    if (IsMe) {



                        $('#Last_Msg_' + userId).text('You sent a photo.');
                    }
                    else {

                        $('#Last_Msg_' + userId).text(user + ' sent you a photo.');
                    }
                    $('#New_Count_' + userId).addClass('messageCount');
                    $('#New_Count_' + userId).text(newcount);

                })
            }
            else {
                $('#New_Count_' + userId).addClass('messageCount');
                var newcount = $('#New_Count_' + userId).text();
                if (newcount == "") {
                    newcount = 1;
                }
                else {
                    newcount = parseInt(newcount) + 1;
                }

                if (text.length > 50) {
                    $('#Last_Msg_' + userId).text(text.substring(0, 50) + "...");
                }
                else {
                    $('#Last_Msg_' + userId).text(text);
                }
                $('#New_Count_' + userId).text(newcount);

            }
            $('#Block_' + userId).parent().prepend($('#Block_' + userId));
            $('#Ago_For_' + userId).text(hours + ':' + mins + ' ' + ampm);
        }

    });
}

function Bind() {
    $(".userLi").prop("onclick", null).off("click");
    $('.userLi').on('click', function () {

        return Chat.CheckInChannels($(this));
    });
    $("#inputString").prop("keyup", null).off("click");

    jQuery("#inputString").keyup(function () {

        var filter = jQuery(this).val();
        jQuery(".userLi h3").each(function () {
            if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
                jQuery(this).parent().parent().hide();
            } else {
                jQuery(this).parent().parent().show()
            }
        });
    });
}
function BindM() {
    debugger;
    $(".btnSend").unbind();

    $('.btnSend').on('click', function () {
        debugger;
        if ($('#txtMsg').val() != '') {
            debugger;
            return Chat.SendMessage($(this));
        }
    });
    $("#txtMsg").unbind();
    $('#txtMsg').on("keydown", function (e) {
        debugger;
        if (e.keyCode === 13) {
            if ($('#txtMsg').val() != '') {
                return Chat.SendMessage($(this));
            }
        }
        else {
            mychannel.typing();
        }
    });


    $("#mediaImage").unbind();
    $("#mediaImage").change(function () {
        var input = $(this)[0];
        if (input.files && input.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {

                src = e.target.result;
                Chat.SendImage();

            };
            reader.readAsDataURL(input.files[0]);
            $('.imgDiv').css('display', 'inline-block');
        }
        else {
            $('.imgDiv').css('display', 'none');
        }

    });
}

function GetTotal(paginator) {

    totalchannels = totalchannels + paginator.items.length;
    if (paginator.hasNextPage) {

        paginator.nextPage().then(GetTotal).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    }
}
function ProceedForData(paginator) {

    debugger;

    var chnls = sortChannelsByName(paginator.items);
    debugger;
    chnls.forEach((channel) => {

        channel.getChannel(channel.sid).then(
            function (mychannels) {

                debugger;
                //mychannels.members()
                //    .fetch()
                //    .then(member => console.log(member.sid));


                SetUpGeneralChannelEvent(mychannels);
                MessageList = [];
                mychannels.getMessages().then(function (messages) {
                    debugger;
                    const totalMessages = messages.items.length;

                    var checkdata = channel.descriptor.attributes.user;

                    if (totalMessages > 0) {
                        UserList.push({ Id: channel.uniqueName.replace(channelPrefix + "-CH-" + identity + "-", ""), UserName: channel.attributes.User.FullName, ProfilePic: channel.attributes.User.ProfilePic, LastMsg: messages.items[totalMessages - 1].state.body, UpdatedOn: messages.items[totalMessages - 1].state.timestamp });
                        //UserList.push({ Id: channel.uniqueName.replace(channelPrefix + "-CH-"+  identity + "-", ""), UserName: channel.attributes.User.FullName, ProfilePic: channel.attributes.User.ProfilePic, LastMsg: messages.items[totalMessages-1].state.body, UpdatedOn: messages.items[totalMessages-1].state.timestamp });
                    }
                    else {
                        UserList.push({ Id: channel.uniqueName.replace(channelPrefix + "-CH-" + identity + "-", ""), UserName: channel.attributes.User.FullName, ProfilePic: channel.attributes.User.ProfilePic, LastMsg: mychannels.lastMessage, UpdatedOn: mychannels.dateUpdated });
                    }

                    console.log('Total Messages:' + totalMessages);
                    if (UserList.length == totalchannels) {
                        UserName = UserList[0].UserName;
                        UserProfile = UserList[0].ProfilePic;
                        SetUsers();
                    }
                });
            });
    });


    if (paginator.hasNextPage) {
        paginator.nextPage().then(ProceedForData).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    }
}

function CheckForData(paginator) {
    debugger;
    var chnls = sortChannelsByName(paginator.items);
    debugger;
    chnls.forEach((channel) => {
        if (channel.uniqueName == PreferChannel) {
            currentChannel = PreferChannel;
            channel.getChannel(channel.sid).then(
                function (mychannels) {

                    UserName = channel.attributes.User.FullName;
                    UserProfile = channel.attributes.User.ProfilePic;
                    var userId = channel.uniqueName.replace(channelPrefix + "-CH-" + identity + "-", '');
                    $('#New_Count_' + userId).removeClass('messageCount');
                    $('#New_Count_' + userId).text('');
                    mychannel = mychannels;
                    SetUpChannelEvents();
                    MessageList = [];
                    mychannel.getMessages().then(function (messages) {

                        const totalMessages = messages.items.length;
                        if (totalMessages > 0) {
                            for (i = 0; i < totalMessages; i++) {

                                const message = messages.items[i];
                                if (message.state.media != null) {
                                    message.state.media.getContentUrl().then(function (urls) {
                                        MessageList.push({ media: urls, IsMe: message.state.author.includes(userPrefix), Timestamp: message.state.timestamp });
                                        if (totalMessages == MessageList.length) {
                                            $('.userLi li').removeClass('active');
                                            $(sender).find('li').addClass('active');

                                            SetMessages();
                                        }

                                    });
                                }
                                else {
                                    MessageList.push({ Text: message.state.body, IsMe: message.state.author.includes(userPrefix), Timestamp: message.state.timestamp });
                                    if (totalMessages == MessageList.length) {
                                        $('.userLi li').removeClass('active');
                                        $(sender).find('li').addClass('active');

                                        SetMessages();

                                    }
                                }

                            }
                        }
                        else {
                            $('.userLi li').removeClass('active');
                            $(sender).find('li').addClass('active')
                            SetMessages();
                        }


                        console.log('Total Messages:' + totalMessages);

                    });

                });

        }
    });
    if (paginator.hasNextPage) {
        paginator.nextPage().then(CheckForData).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    }
}

function RefreshForData(paginator) {

    var chnls = sortChannelsByName(paginator.items);

    chnls.forEach((channel) => {
        if (channel.uniqueName == PreferChannel) {
            currentChannel = PreferChannel;
            channel.getChannel(channel.sid).then(
                function (mychannels) {




                    UserName = channel.attributes.User.FullName;
                    UserProfile = channel.attributes.User.ProfilePic;
                    var userId = channel.uniqueName.replace(channelPrefix + "-CH-" + identity + "-", '');
                    $('#New_Count_' + userId).removeClass('messageCount');
                    $('#New_Count_' + userId).text('');
                    mychannel = mychannels;
                    SetUpChannelEvents();
                    MessageList = [];
                    mychannel.getMessages().then(function (messages) {

                        const totalMessages = messages.items.length;
                        if (totalMessages > 0) {
                            for (i = 0; i < totalMessages; i++) {

                                const message = messages.items[i];
                                if (message.state.media != null) {
                                    message.state.media.getContentUrl().then(function (urls) {
                                        MessageList.push({ media: urls, IsMe: message.state.author.includes(userPrefix), Timestamp: message.state.timestamp })
                                        if (totalMessages == MessageList.length) {
                                            $('.userLi li').removeClass('active');
                                            $(sender).find('li').addClass('active')

                                            RefreshMessages();

                                        }

                                    })
                                }
                                else {
                                    MessageList.push({ Text: message.state.body, IsMe: message.state.author.includes(userPrefix), Timestamp: message.state.timestamp });
                                    if (totalMessages == MessageList.length) {
                                        $('.userLi li').removeClass('active');
                                        $(sender).find('li').addClass('active')

                                        RefreshMessages();

                                    }
                                }

                            }
                        }
                        else {
                            $('.userLi li').removeClass('active');
                            $(sender).find('li').addClass('active')
                            RefreshMessages();
                        }


                        console.log('Total Messages:' + totalMessages);

                    });

                });

        }
    });
    if (paginator.hasNextPage) {
        paginator.nextPage().then(RefreshForData).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    }
}
function sortChannelsByName(channels) {
    return channels.sort(function (a, b) {
        return a.attributes.User.FullName.localeCompare(b.attributes.User.FullName);
    });
};


var Chat = {

    InitializeChat: function () {
        Chat.GetAllUsers();
    },

    RegenrateToken: function () {
        $.ajaxExt({
            url: baseUrl + '/PrimeManager/Chat/GenrateToken',
            type: 'POST',
            validate: false,
            showThrobber: false,
            showErrorMessage: true,
            messageControl: $('div.messageAlert'),
            success: function (results, message) {
                debugger;
                token = results[0];
                identity = results[1];

                Twilio.Chat.Client.create(token).then(client => {

                    tc.messagingClient = client;
                    if (firebase && firebase.messaging()) {
                        // requesting permission to use push notifications
                        firebase.messaging().requestPermission().then(() => {

                            firebase.messaging().getToken().then((fcmToken) => {

                                tc.messagingClient.setPushRegistrationId('fcm', fcmToken);

                                // registering event listener on new message from firebase to pass it to the Chat SDK for parsing
                                firebase.messaging().onMessage(payload => {

                                    tc.messagingClient.handlePushNotification(payload);
                                });
                            }).catch((err) => {

                            });
                        }).catch((err) => {

                        });
                    } else {
                        // no Firebase library imported or Firebase library wasn't correctly initialized
                    }
                }).catch(function (e) { console.error('Got an error:', e.code, e.message); });

            }
        });
    },

    GetAllUsers: function (sender) {
        tc.messagingClient.getUserChannelDescriptors(20000).then(function (paginator) {

            var find = 0;
            var count = 1;
            GetTotal(paginator);

            ProceedForData(paginator);
        }).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    },

    CheckInChannels: function (senders) {
        debugger;
        var enduser = $(senders)[0].getAttribute('data-id');
        useridentity = enduser;
        PreferChannel = channelPrefix + "-CH-" + identity + "-" + enduser;
        tc.messagingClient.getUserChannelDescriptors().then(function (paginator) {

            debugger;
            sender = senders;
            CheckForData(paginator);
        }).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    },

    SetUpInitialChannel: function (id) {

        var enduser = id;
        useridentity = enduser;
        PreferChannel = channelPrefix + "-CH-" + identity + "-" + enduser;
        tc.messagingClient.getUserChannelDescriptors().then(function (paginator) {
            sender = $('#Block_' + enduser);

            CheckForData(paginator);



        }).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    },
    RefreshChannel: function () {


        PreferChannel = mychannel.uniqueName;
        tc.messagingClient.getUserChannelDescriptors().then(function (paginator) {


            RefreshForData(paginator);

        }).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    },


    SendMessage: function () {
        var msg = $('#txtMsg').val();
        mychannel.sendMessage(msg).then(function (res) {
            $('#txtMsg').val('');
        }).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    },

    SendImage: function ()
    {
        const formData = new FormData();
        formData.append('file', $('#mediaImage')[0].files[0]);
        mychannel.sendMessage(formData).then(function (res) {
            $('#txtMsg').val('');
        }).catch(function (e) { console.error('Got an error:', e.code, e.message); });
    },




    DeleteMessage: function ()
    {
       // mychannel.DeleteMessage();
    },

    User: function (sender) {

        var obj = new Object();
        obj.id = useridentity;
        $.ajaxExt({
            type: "POST",
            validate: false,
            parentControl: $(sender).parents("form:first"),
            data: $.postifyData(obj),
            messageControl: null,
            showThrobber: false,
            throbberPosition: { my: "left center", at: "right center", of: sender, offset: "5 0" },
            url: baseUrl + '/PrimeManager/Chat/User',
            success: function (results, message) {
                $('#userContent').html(results[0]);
                $('#userModal').modal("toggle");

            }
        });
    }

}
