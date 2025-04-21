// Importar Firebase
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.5.0/firebase-app.js";
import { 
    getAuth, 
    createUserWithEmailAndPassword, 
    signInWithEmailAndPassword, 
    signInWithPopup, 
    GoogleAuthProvider, 
    sendPasswordResetEmail,
    onAuthStateChanged,
    signOut
} from "https://www.gstatic.com/firebasejs/11.5.0/firebase-auth.js";

// Configuración de Firebase
const firebaseConfig = {
    apiKey: "AIzaSyAY2Mp8Yocc69VA77IvLMw-claTtgYgBc8",
    authDomain: "cocinas-valcucini-6dfba.firebaseapp.com",
    projectId: "cocinas-valcucini-6dfba",
    storageBucket: "cocinas-valcucini-6dfba.appspot.com", // Corrección del storageBucket
    messagingSenderId: "772833916617",
    appId: "1:772833916617:web:d8c39cfaad101f595ebf18",
    measurementId: "G-7R6HXR6CV2"
};
  


// Inicializar Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();




// Función para registrar usuario
document.getElementById("registroForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const email = document.getElementById("registroEmail").value;
    const password = document.getElementById("registroPassword").value;

    createUserWithEmailAndPassword(auth, email, password)
        .then(() => {
            alert("Registro exitoso. Ahora puedes iniciar sesión.");
            window.location.href = "cliente.html";
        })
        .catch((error) => {
            alert("Error al registrarse: " + error.message);
        });
});


// Función para iniciar sesión
document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const email = document.getElementById("loginEmail").value;
    const password = document.getElementById("loginPassword").value;

    signInWithEmailAndPassword(auth, email, password)
        .then(() => {
            alert("Inicio de sesión exitoso.");
            window.location.href = "cliente.html";
        })
        .catch((error) => {
            alert("Error al iniciar sesión: " + error.message);
        });
});


// Función para iniciar sesión con Google
window.loginWithGoogle = function() {
    signInWithPopup(auth, provider)
        .then(() => {
            alert("Inicio de sesión con Google exitoso.");
            window.location.href = "cliente.html";
        })
        .catch((error) => {
            alert("Error al iniciar sesión con Google: " + error.message);
        });
};


// Función para registrar con Google (es igual a iniciar sesión)
window.registerWithGoogle = function() {
    loginWithGoogle(); // Usa la misma función para registro
};