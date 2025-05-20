// Your Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyA1Y-huhdSlsdYWMbpf9V36OrXpn_E0SCU",
  authDomain: "sntrips-deeee.firebaseapp.com",
  databaseURL: "https://sntrips-deeee-default-rtdb.firebaseio.com",
  projectId: "sntrips-deeee",
  storageBucket: "sntrips-deeee.firebasestorage.app",
  messagingSenderId: "678847982956",
  appId: "1:678847982956:web:391b30b98d8a8cea639749",
  measurementId: "G-8N2JJELKV1"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const database = firebase.database();

// Helper function to sanitize chat IDs for Firebase paths
// Firebase doesn't allow ., #, $, [, ] in keys
function sanitizeFirebasePath(path) {
  return path.replace(/[.#$[\]]/g, '_');
} 