var firebaseConfig = {
    apiKey: "AIzaSyCruxKj5LgsBU5zGDf9YD0u5ua0sKS2PHg",
    authDomain: "happy-seasons-1cb0c.firebaseapp.com",
    projectId: "happy-seasons-1cb0c",
    storageBucket: "happy-seasons-1cb0c.appspot.com",
    messagingSenderId: "293539214871",
    appId: "1:293539214871:web:b045b105a189f0038e4e52",
    measurementId: "G-SK0QN85RFK"

};



//     firebaseConfig = {
//     apiKey: "AIzaSyB-VJTDC8ep8KLJRpboQ9e5yEr0KKELvgI",
//     authDomain: "happy-seasons.firebaseapp.com",
//     projectId: "happy-seasons",
//     storageBucket: "happy-seasons.appspot.com",
//     messagingSenderId: "69584070632",
//     appId: "1:69584070632:web:df49bb7ff2cdb3a975665d",
//     measurementId: "G-08Z1CNPHPD"
// };

//     firebaseConfig = {
//     apiKey: "AIzaSyB-VJTDC8ep8KLJRpboQ9e5yEr0KKELvgI",
//     authDomain: "happy-seasons.firebaseapp.com",
//     projectId: "happy-seasons",
//     storageBucket: "happy-seasons.appspot.com",
//     messagingSenderId: "69584070632",
//     appId: "1:69584070632:web:a0345ac7e22306b475665d",
//     measurementId: "G-0NLPPEZYRY"
// };

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
// firebase.analytics();

/**
 * We can start messaging using messaging() service with firebase object
 */
var messaging = firebase.messaging();

/** Register your service worker here
 *  It starts listening to incoming push notifications from here
 */
navigator.serviceWorker.register(`${baseUrl}/js/firebase-messaging-sw.js`)
.then(function (registration) {
    /** Since we are using our own service worker ie firebase-messaging-sw.js file */
    messaging.useServiceWorker(registration);
    /** Lets request user whether we need to send the notifications or not */
    messaging.requestPermission()
        .then(function () {
            /** Standard function to get the token */
            messaging.getToken()
            .then(function(token) {
                /** Here I am logging to my console. This token I will use for testing with PHP Notification */
                // console.log(token);
                saveFcmToken(token);
                /** SAVE TOKEN::From here you need to store the TOKEN by AJAX request to your server */
            })
            .catch(function(error) {
                /** If some error happens while fetching the token then handle here */
                // updateUIForPushPermissionRequired();
                console.log('Error while fetching the token ' + error);
            });
        })
        .catch(function (error) {
            /** If user denies then handle something here */
            console.log('Permission denied ' + error);
        })
})
.catch(function () {
    console.log('Error in registering service worker');
});

/** What we need to do when the existing token refreshes for a user */
messaging.onTokenRefresh(function() {
    messaging.getToken()
    .then(function(renewedToken) {
        saveFcmToken(renewedToken);
        // console.log(renewedToken);
        /** UPDATE TOKEN::From here you need to store the TOKEN by AJAX request to your server */
    })
    .catch(function(error) {
        /** If some error happens while fetching the token then handle here */
        console.log('Error in fetching refreshed token ' + error);
    });
});

// Handle incoming messages
messaging.onMessage(function(payload) {
    const notificationTitle = 'Data Message Title';
    const notificationOptions = {
        body: 'Data Message body',
        icon: 'https://c.disquscdn.com/uploads/users/34896/2802/avatar92.jpg',
        image: 'https://c.disquscdn.com/uploads/users/34896/2802/avatar92.jpg'
    };
    appendMessage(payload);
    getNotificationData();
    // return self.registration.showNotification(notificationTitle, notificationOptions);
});

const saveFcmToken = (token) => {
    return fetch(baseUrl+"/admin/saveWebFcmToken", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({token})
        }).then(function(result) {
            return result.json();
        }).then(function(response) {
            if (response.status) {
                // console.log("response:"+response)
            } else {
                errorResponse(response.message);
            }
            return response;
        });
}

// Add a message to the messages element.
const appendMessage = (payload) => {
    $(document).Toasts('create', {
        title: `${payload.notification.title}`,
        class: `bg-success`,
        autohide: true,
        delay: 3000,
        body: `${payload.notification.body}`
    });
}
