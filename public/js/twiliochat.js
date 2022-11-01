var twiliochat = (function() {
  var tc = {};

  var GENERAL_CHANNEL_UNIQUE_NAME = 'general';
  var GENERAL_CHANNEL_NAME = 'General Channel';
  var MESSAGES_HISTORY_LIMIT = 50;

  var $channelList;
  var $inputText;
  var $usernameInput;
  var $statusRow;
  var $connectPanel;
  var $newChannelInputRow;
  var $newChannelInput;
  var $typingRow;
  var $typingPlaceholder;

  $(document).ready(function() {
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
    if (event.keyCode === 13){
      connectClientWithUsername();
    }
  }

  function handleInputTextKeypress(event) {
    if (event.keyCode === 13) {
      tc.currentChannel.sendMessage($(this).val());
      event.preventDefault();
      $(this).val('');
    }
    else {
      notifyTyping();
    }
  }

  var notifyTyping = $.throttle(function() {
    tc.currentChannel.typing();
  }, 1000);

  tc.handleNewChannelInputKeypress = function(event) {
    if (event.keyCode === 13) {
      tc.messagingClient.createChannel({
        friendlyName: $newChannelInput.val()
      }).then(hideAddChannelInput);
      $(this).val('');
      event.preventDefault();
    }
  };

  connectAdminWithUsername = async function(admin) {
    tc.username = admin;
    return await fetchAccessToken(tc.username, connectMessagingClient);
  }

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

  async function fetchAccessToken(username, handler) {
    var postToken = postRequestToken(username);
    return await handler(postToken); 
  }

  function postRequestToken(username) {
    var postToken;
    $.ajax({
        type:'POST',
        url:baseUrl+'/admin/twilioChatToken',
        data:{identity: username,_token:$('meta[name="csrf-token"]').attr('content')},
        async:false,
        success:function(data){
          // console.log('data',data);
          postToken = data.token;    
        },
        error: function(data){
          console.log('Failed to fetch the Access Token with error: ' + data); 
        }
    });

    return postToken;
  }

  async function connectMessagingClient(token) {
    // Initialize the Chat messaging client
    tc.accessManager = new Twilio.AccessManager(token);
    var res = await Twilio.Chat.Client.create(token).then(function(client) {
      tc.messagingClient = client;
      updateConnectedUI();

      // hide general channel
      // tc.loadChannelList(tc.joinGeneralChannel);
      // tc.loadChannelList();
      // hide general channel ENDS
      
      tc.messagingClient.on('channelAdded', $.throttle(tc.loadChannelList));
      tc.messagingClient.on('channelRemoved', $.throttle(tc.loadChannelList));
      tc.messagingClient.on('tokenExpired', refreshToken);
    });
    return res;
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
    tc.$messageList.empty();
    $connectPanel.addClass('connected').removeClass('disconnected');
    $inputText.addClass('with-shadow');
    $typingRow.addClass('connected').removeClass('disconnected');
  }

  tc.loadChannelList = function(handler) {
    if (tc.messagingClient === undefined) {
      console.log('Client is not initialized');
      return;
    }

    // tc.messagingClient.getPublicChannelDescriptors().then(function(channels) {
    //   tc.channelArray = tc.sortChannelsByName(channels.items);
    //   $channelList.text('');

    //   // hide general channel
    //   tc.channelArray = tc.channelArray.filter(item => item.friendlyName !== "General Channel")
    //   // hide general channel ENDS

    //   tc.channelArray.forEach(addChannel);
    //   if (typeof handler === 'function') {
    //     handler();
    //   }
    // });

    // get private user channels
    tc.messagingClient.getUserChannelDescriptors().then(function(channels) {
      tc.channelArray = tc.sortChannelsByName(channels.items);
      $channelList.text('');

      // hide general channel
      tc.channelArray = tc.channelArray.filter(item => item.friendlyName !== "General Channel")
      // hide general channel ENDS
      console.log('tc.channelArray',tc.channelArray);
      tc.channelArray.forEach(addChannel);
      if (typeof handler === 'function') {
        handler();
      }
    });
    // get private user channels ENDS
  };

  tc.joinGeneralChannel = function() {
    console.log('Attempting to join "general" chat channel...');
    if (!tc.generalChannel) {
      // If it doesn't exist, let's create it
      tc.messagingClient.createChannel({
        uniqueName: GENERAL_CHANNEL_UNIQUE_NAME,
        friendlyName: GENERAL_CHANNEL_NAME
      }).then(function(channel) {
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

  function initChannel(channel) {
    console.log('Initialized channel ' + channel.friendlyName);
    return tc.messagingClient.getChannelBySid(channel.sid);
  }

  function joinChannel(_channel) {
    if (_channel.channelState.status !== "joined") {
      return _channel.join()
      .then(function(joinedChannel) {
        console.log('Joined channel:' + joinedChannel);
        console.log('Joined channel friendlyName:' + joinedChannel.friendlyName);
        updateConnectedUI();
        updateChannelUI(_channel);
        tc.currentChannel = _channel;
        tc.loadMessages();
        return joinedChannel;
      });  
    }
    updateConnectedUI();
    updateChannelUI(_channel)
    tc.currentChannel = _channel;
    tc.loadMessages();
    return _channel;
  }

  function initChannelEvents() {
    console.log(tc.currentChannel.friendlyName + ' ready.');
    tc.currentChannel.on('messageAdded', tc.addMessageToList);
    tc.currentChannel.on('typingStarted', showTypingStarted);
    tc.currentChannel.on('typingEnded', hideTypingStarted);
    tc.currentChannel.on('memberJoined', notifyMemberJoined);
    tc.currentChannel.on('memberLeft', notifyMemberLeft);
    // $inputText.prop('disabled', false).focus();
    $inputText.prop('disabled', false);
  }

  function setupChannel(channel) {

    return leaveCurrentChannel()
      .then(function() {
        return initChannel(channel);
      })
      .then(function(_channel) {
        return joinChannel(_channel);
      })
      .then(initChannelEvents);

    // return leaveCurrentChannel()
    //   .then(function() {
    //     return initChannel(channel);
    //   })
    //   .then(function(_channel) {
    //     return joinChannel(_channel);
    //   })
    //   .then(initChannelEvents);
  }

  tc.loadMessages = function() {
    tc.currentChannel.getMessages(MESSAGES_HISTORY_LIMIT).then(function (messages) {
      messages.items.forEach(tc.addMessageToList);
    });
  };

  function leaveCurrentChannel() {
    if (tc.currentChannel) {
      // return tc.currentChannel.leave().then(function(leftChannel) {
      //   console.log('left ' + leftChannel.friendlyName);
      //   leftChannel.removeListener('messageAdded', tc.addMessageToList);
      //   leftChannel.removeListener('typingStarted', showTypingStarted);
      //   leftChannel.removeListener('typingEnded', hideTypingStarted);
      //   leftChannel.removeListener('memberJoined', notifyMemberJoined);
      //   leftChannel.removeListener('memberLeft', notifyMemberLeft);
      // });
      return new Promise((resolve, reject) => {
        console.log('left ' + tc.currentChannel.friendlyName);
        tc.currentChannel.removeListener('messageAdded', tc.addMessageToList);
        tc.currentChannel.removeListener('typingStarted', showTypingStarted);
        tc.currentChannel.removeListener('typingEnded', hideTypingStarted);
        tc.currentChannel.removeListener('memberJoined', notifyMemberJoined);
        tc.currentChannel.removeListener('memberLeft', notifyMemberLeft);
        return resolve();
      });
    } else {
      return Promise.resolve();
    }
  }

  tc.markNewMessageToList = function(message) {
    if (message && message.channel && message.channel.sid) {
      // console.log('new message:',message.channel.friendlyName);
      var chSid = message.channel.sid;
      var chTag = $channelList.find(`[data-channel-sid='${chSid}']`);
      if (chTag) {
        if (tc.currentChannel === undefined || (tc.currentChannel && tc.currentChannel.sid !== chSid)) {
          $(chTag).find('span.msg-count').show();
          var chatCount = $(chTag).find('span.msg-count').text();
          $(chTag).find('span.msg-count').text(parseInt(chatCount)+1);
            simpleToastAlert('New Message Alert',`${message.channel.friendlyName} has send new message!`);
        }
      }
    }
  }

  tc.addMessageToList = function(message) {
    // var rowDiv = $('<div>').addClass('row no-margin');
    var rowDiv = $('<div>').addClass('direct-chat-msg');
    rowDiv.loadTemplate($('#message-template'), {
      username: message.author,
      date: dateFormatter.getTodayDate(message.dateCreated),
      body: message.body
    });
    if (message.author === tc.username) {
      rowDiv.addClass('right');
      rowDiv.find('.message-date').addClass('float-right');
    }

    tc.$messageList.append(rowDiv);
    scrollToMessageListBottom();
  };

  function notifyMemberJoined(member) {
    notify(member.identity + ' joined the channel')
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

  function hideTypingStarted(member) {
    $typingPlaceholder.text('');
  }

  function scrollToMessageListBottom() {
    $('#chat-window').scrollTop(tc.$messageList.prop("scrollHeight"));
  }

  function updateChannelUI(selectedChannel) {
    var channelElements = $('.channel-element').toArray();
    var channelElement = channelElements.filter(function(element) {
      return $(element).data().sid === selectedChannel.sid;
    });
    channelElement = $(channelElement);
    if (tc.currentChannelContainer === undefined && selectedChannel.uniqueName === GENERAL_CHANNEL_UNIQUE_NAME) {
      tc.currentChannelContainer = channelElement;
    }
    if (tc.currentChannelContainer) {
      tc.currentChannelContainer.removeClass('selected-channel').addClass('unselected-channel');
    }
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

  function setupNewChannel(channel) {
    return initChannel(channel)
      .then(function(_channel) {
        return _channel.on('messageAdded', tc.markNewMessageToList);
      })
  }

  function addChannel(channel) {
    if (channel.uniqueName === GENERAL_CHANNEL_UNIQUE_NAME) {
      tc.generalChannel = channel;
    }
    setupNewChannel(channel);
    // var rowDiv = $('<div>').addClass('row channel-row');
    var rowDiv = $('<li>').addClass('w-100');
    rowDiv.loadTemplate('#channel-template', {
      channelName: channel.friendlyName
    });

    var channelP = rowDiv.children('a').first();
    rowDiv.on('click', selectChannel);
    channelP.data('sid', channel.sid);
    channelP.attr('data-channel-sid',channel.sid);
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
    tc.currentChannel.delete().then(function(channel) {
      console.log('channel: '+ channel.friendlyName + ' deleted');
      setupChannel(tc.generalChannel);
    });
  }

  function selectChannel(event) {
    var target = $(event.target);
    if (target.parent().closest('a').length > 0) {
      var channelSid = target.parent().closest('a').data().sid;
      if (target.parent().closest('a').find('span.msg-count')) {
        target.parent().closest('a').find('span.msg-count').hide();
        target.parent().closest('a').find('span.msg-count').text('0');
      }
      var selectedChannel = tc.channelArray.filter(function(channel) {
        return channel.sid === channelSid;
      })[0];
      if (tc.currentChannel && selectedChannel && selectedChannel.sid === tc.currentChannel.sid) {
        return;
      }
      setupChannel(selectedChannel);
    }
    return;
  };

  function disconnectClient() {
    leaveCurrentChannel();
    $channelList.text('');
    tc.$messageList.text('');
    channels = undefined;
    $statusRow.addClass('disconnected').removeClass('connected');
    tc.$messageList.addClass('disconnected').removeClass('connected');
    // $connectPanel.addClass('disconnected').removeClass('connected');
    $inputText.removeClass('with-shadow');
    $typingRow.addClass('disconnected').removeClass('connected');
  }

  tc.sortChannelsByName = function(channels) {
    return channels.sort(function(a, b) {
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
