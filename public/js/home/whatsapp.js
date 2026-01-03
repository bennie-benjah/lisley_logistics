// WhatsApp Widget Functionality
document.addEventListener('DOMContentLoaded', function() {
    const whatsappToggle = document.getElementById('whatsappToggle');
    const whatsappChatBox = document.getElementById('whatsappChatBox');
    const whatsappInput = document.getElementById('whatsappInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const quickReplies = document.getElementById('quickReplies');
    const whatsappBody = document.getElementById('whatsappBody');
    
    // Your WhatsApp number (with country code, without +)
    const whatsappNumber = '254793639938'; // 254 is Kenya's country code
    
    // Toggle chat box visibility
    if (whatsappToggle) {
        whatsappToggle.addEventListener('click', function() {
            whatsappChatBox.classList.toggle('active');
        });
    }
    
    // Function to add a message to the chat
    function addMessage(message, isBot = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `whatsapp-message ${isBot ? 'bot' : 'user'}`;
        
        const now = new Date();
        const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        messageDiv.innerHTML = `
            <div class="message-bubble">
                ${message}
                <div class="message-time">${timeString}</div>
            </div>
        `;
        
        whatsappBody.appendChild(messageDiv);
        whatsappBody.scrollTop = whatsappBody.scrollHeight;
    }
    
    // Function to open WhatsApp with pre-filled message
    function openWhatsApp(message) {
        // Format the message for URL encoding
        const encodedMessage = encodeURIComponent(message);
        
        // Create WhatsApp URL
        const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
        
        // Open WhatsApp in new tab
        window.open(whatsappUrl, '_blank');
        
        // Add user message to chat UI
        addMessage(message, false);
        
        // Add bot response
        setTimeout(() => {
            addMessage("I've opened WhatsApp for you. Please continue the conversation there with our support team. We'll respond shortly! 😊", true);
        }, 1000);
    }
    
    // Handle quick reply clicks
    if (quickReplies) {
        quickReplies.addEventListener('click', function(e) {
            if (e.target.classList.contains('whatsapp-quick-reply')) {
                const message = e.target.getAttribute('data-reply');
                openWhatsApp(message);
                
                // Hide quick replies after selection
                quickReplies.style.display = 'none';
            }
        });
    }
    
    // Handle manual message sending
    if (sendMessageBtn) {
        sendMessageBtn.addEventListener('click', function() {
            const message = whatsappInput.value.trim();
            if (message) {
                openWhatsApp(message);
                whatsappInput.value = '';
            }
        });
    }
    
    // Allow Enter key to send message
    if (whatsappInput) {
        whatsappInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const message = whatsappInput.value.trim();
                if (message) {
                    openWhatsApp(message);
                    whatsappInput.value = '';
                }
            }
        });
    }
    
    // Close chat when clicking outside
    document.addEventListener('click', function(event) {
        if (!whatsappChatBox.contains(event.target) && 
            !whatsappToggle.contains(event.target) && 
            whatsappChatBox.classList.contains('active')) {
            whatsappChatBox.classList.remove('active');
        }
    });
});