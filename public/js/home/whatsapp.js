// public/js/home/whatsapp.js
window.WhatsApp = (function() {
    // DOM Elements
    const whatsappToggle = document.getElementById('whatsappToggle');
    const whatsappChatBox = document.getElementById('whatsappChatBox');
    const whatsappBody = document.getElementById('whatsappBody');
    const whatsappInput = document.getElementById('whatsappInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');

    if (!whatsappToggle || !whatsappChatBox || !whatsappBody || !whatsappInput || !sendMessageBtn) {
        console.warn("WhatsApp elements missing from DOM.");
        return;
    }

    // Example Bot Responses
    const whatsappBotResponses = {
        "track": "Please provide your tracking number or visit the Track Shipment page.",
        "quote": "Let us know which services you need, and we'll provide a quote.",
        "support": "Describe your issue and our support team will assist you.",
        "default": "Thanks for your message! Our team will reply shortly."
    };

    function sendMessage() {
        const message = whatsappInput.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        whatsappInput.value = '';

        setTimeout(() => {
            let response = whatsappBotResponses.default;
            for (const key in whatsappBotResponses) {
                if (message.toLowerCase().includes(key)) {
                    response = whatsappBotResponses[key];
                    break;
                }
            }
            addMessage(response, 'bot');
        }, 500);
    }

    function addMessage(message, sender) {
        const div = document.createElement('div');
        div.className = `whatsapp-message ${sender}`;
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        div.innerHTML = `<div class="message-bubble">${message}<div class="message-time">${time}</div></div>`;
        whatsappBody.appendChild(div);
        whatsappBody.scrollTop = whatsappBody.scrollHeight;
    }

    function toggleChat() {
        whatsappChatBox.classList.toggle('active');
        if (whatsappChatBox.classList.contains('active')) whatsappInput.focus();
    }

    // Event listeners
    whatsappToggle.addEventListener('click', toggleChat);
    sendMessageBtn.addEventListener('click', sendMessage);
    whatsappInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.whatsapp-widget') && whatsappChatBox.classList.contains('active')) {
            whatsappChatBox.classList.remove('active');
        }
    });

    // Initialize
    addMessage("Hello! How can I assist you today?", 'bot');

    return {
        sendMessage,
        toggleChat
    };
})();
