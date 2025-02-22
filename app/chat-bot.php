<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot de Prácticas Preprofesionales</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../estilosChatbot.css">
</head>
<body>

<div class="chat-container">
    <div class="chat-header">
        <h2 class="chat-title">Asistente Virtual</h2>
        <span class="close-icon" onclick="toggleChat()">
            <i class="fas fa-times"></i>
        </span>
    </div>
    <div id="chat-container" class="chat-content"></div>
    <div class="input-container">
        <input type="text" id="user-input" placeholder="Escribe tu mensaje..." onkeydown="sendMessage(event)">
        <button class="send-button" onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
function sendMessage(event) {
    if (event && event.key !== 'Enter') {
        return;
    }

    var userInput = document.getElementById('user-input').value;
    appendMessage(userInput, true);

    // Hacer una solicitud al archivo PHP con el mensaje del usuario
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var botResponse = xhr.responseText;
            appendMessage(botResponse, false);
        }
    };
    xhr.open("GET", "chatBot.php?message=" + encodeURIComponent(userInput), true);
    xhr.send();

    // Limpiar el cuadro de entrada
    document.getElementById('user-input').value = '';
}

function appendMessage(message, isUserMessage) {
    var chatContainer = document.getElementById('chat-container');
    var newMessageContainer = document.createElement('div');
    newMessageContainer.classList.add('message-container');

    var label = document.createElement('span');
    label.classList.add('message-label');
    label.textContent = isUserMessage ? 'Tú' : 'Bot';
    newMessageContainer.appendChild(label);

    var newMessage = document.createElement('p');
    newMessage.textContent = message;
    newMessage.classList.add(isUserMessage ? 'user-message' : 'bot-message');
    if (!isUserMessage) {
        newMessage.classList.add('fade-in');
    }
    newMessageContainer.appendChild(newMessage);

    chatContainer.appendChild(newMessageContainer);

    // Position the label to the right or left of the message container
    if (isUserMessage) {
        label.style.marginLeft = 'auto';
    } else {
        label.style.marginRight = 'auto';
    }

    // Desplazarse hacia abajo para mostrar el último mensaje
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function toggleChat() {
    var chatContainer = document.querySelector('.chat-container');
    chatContainer.style.display = (chatContainer.style.display === 'none') ? 'block' : 'none';
}

function initChat() {
    // Enviar un saludo al bot al cargar la página
    var greeting = "Hola, ¿en qué puedo ayudarte?";
    appendMessage(greeting, false);
}

// Llama a la función initChat() al cargar la página
document.addEventListener('DOMContentLoaded', initChat);
</script>

</body>
</html>