/** Again import google libraries */
importScripts("https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.2.6/firebase-analytics.js");
importScripts("https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js");

/** Your web app's Firebase configuration 
 * Copy from Login 
 *      Firebase Console -> Select Projects From Top Naviagation 
 *      -> Left Side bar -> Project Overview -> Project Settings
 *      -> General -> Scroll Down and Choose CDN for all the details
*/
var firebaseConfig = {
        apiKey: "AIzaSyCtUlAdZpvFGNy5243Hzy5EyByOyeG0tXA",
        authDomain: "nadil-e028e.firebaseapp.com",
        databaseURL: "https://nadil-e028e.firebaseio.com",
        projectId: "nadil-e028e",
        storageBucket: "nadil-e028e.appspot.com",
        messagingSenderId: "877247719408",
        appId: "1:877247719408:web:c6c15b4a3cff86ca3c5f35",
        measurementId: "G-HEWT0006L2"
      };
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
// firebase.analytics();

// Retrieve an instance of Firebase Data Messaging so that it can handle background messages.
const messaging = firebase.messaging();

/** THIS IS THE MAIN WHICH LISTENS IN BACKGROUND */
messaging.setBackgroundMessageHandler(function(payload) {
    const notificationTitle = 'BACKGROUND MESSAGE TITLE';
    const notificationOptions = {
        body: 'Data Message body',
        icon: 'https://c.disquscdn.com/uploads/users/34896/2802/avatar92.jpg',
        image: 'https://c.disquscdn.com/uploads/users/34896/2802/avatar92.jpg'
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});