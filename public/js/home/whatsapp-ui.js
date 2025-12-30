 // Initialize WhatsApp chat functionality
        function initWhatsAppChat() {
            // Toggle chat box visibility
            whatsappToggle.addEventListener('click', () => {
                whatsappChatBox.classList.toggle('active');
                if (whatsappChatBox.classList.contains('active')) {
                    whatsappInput.focus();
                }
            });
            
            // Send message when button is clicked
            sendMessageBtn.addEventListener('click', sendWhatsAppMessage);
            
            // Send message when Enter key is pressed
            whatsappInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendWhatsAppMessage();
                }
            });
            
            // Quick reply buttons
            document.querySelectorAll('.whatsapp-quick-reply').forEach(button => {
                button.addEventListener('click', (e) => {
                    const reply = e.target.getAttribute('data-reply');
                    whatsappInput.value = reply;
                    sendWhatsAppMessage();
                });
            });
            
            // Close chat when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.whatsapp-widget') && whatsappChatBox.classList.contains('active')) {
                    whatsappChatBox.classList.remove('active');
                }
            });
        }
        
        // Send WhatsApp message
        function sendWhatsAppMessage() {
            const message = whatsappInput.value.trim();
            if (!message) return;
            
            // Add user message to chat
            addMessageToChat(message, 'user');
            
            // Clear input
            whatsappInput.value = '';
            
            // Simulate bot response after a delay
            setTimeout(() => {
                let botResponse = whatsappBotResponses.default;
                
                // Check for quick reply matches
                for (const [key, value] of Object.entries(whatsappBotResponses)) {
                    if (message.toLowerCase().includes(key.toLowerCase())) {
                        botResponse = value;
                        break;
                    }
                }
                
                addMessageToChat(botResponse, 'bot');
                
                // If user asked about tracking, suggest visiting track page
                if (message.toLowerCase().includes('track') || message.toLowerCase().includes('shipment')) {
                    setTimeout(() => {
                        addMessageToChat("Would you like me to take you to our Track Shipment page?", 'bot');
                        
                        // Add a special quick action
                        const actionDiv = document.createElement('div');
                        actionDiv.className = 'whatsapp-quick-replies';
                        actionDiv.innerHTML = `
                            <div class="whatsapp-quick-reply" id="goToTrackPage">Go to Track Shipment Page</div>
                        `;
                        whatsappBody.appendChild(actionDiv);
                        
                        document.getElementById('goToTrackPage').addEventListener('click', () => {
                            showPage('track');
                            whatsappChatBox.classList.remove('active');
                        });
                    }, 1000);
                }
                
                // If user asked about refunds, suggest refund policy page
                if (message.toLowerCase().includes('refund') || message.toLowerCase().includes('return')) {
                    setTimeout(() => {
                        addMessageToChat("You can find detailed information about our return and refund policies on our Refund Policy page.", 'bot');
                        
                        // Add a special quick action
                        const actionDiv = document.createElement('div');
                        actionDiv.className = 'whatsapp-quick-replies';
                        actionDiv.innerHTML = `
                            <div class="whatsapp-quick-reply" id="goToRefundPage">Go to Refund Policy</div>
                        `;
                        whatsappBody.appendChild(actionDiv);
                        
                        document.getElementById('goToRefundPage').addEventListener('click', () => {
                            showPage('refund');
                            whatsappChatBox.classList.remove('active');
                        });
                    }, 1000);
                }
            }, 1000);
        }
        
        // Add message to WhatsApp chat
        function addMessageToChat(message, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `whatsapp-message ${sender}`;
            
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            messageDiv.innerHTML = `
                <div class="message-bubble">
                    ${message}
                    <div class="message-time">${timeString}</div>
                </div>
            `;
            
            whatsappBody.appendChild(messageDiv);
            
            // Scroll to bottom of chat
            whatsappBody.scrollTop = whatsappBody.scrollHeight;
        }
        