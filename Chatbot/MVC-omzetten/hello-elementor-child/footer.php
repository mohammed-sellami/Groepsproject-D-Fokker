<?php
/**
 * The template for displaying the footer.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
    if ( hello_elementor_display_header_footer() ) {
        if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
            get_template_part( 'template-parts/dynamic-footer' );
        } else {
            get_template_part( 'template-parts/footer' );
        }
    }
}
?>

<?php wp_footer(); ?>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Chatbot Styles -->
<style>
.chat-messages {
    scrollbar-width: thin;
    scrollbar-color: #CBD5E0 #F7FAFC;
}
.chat-messages::-webkit-scrollbar {
    width: 6px;
}
.chat-messages::-webkit-scrollbar-track {
    background: #F7FAFC;
}
.chat-messages::-webkit-scrollbar-thumb {
    background: #CBD5E0;
    border-radius: 3px;
}
</style>

<!-- Chatbot Container -->
<div id="chatbot-container" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button -->
    <button id="chat-toggle" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chat-window" class="hidden bg-white rounded-lg shadow-2xl w-96 h-[600px] flex flex-col">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-t-lg flex justify-between items-center">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold">Slaap Assistent</h3>
                    <p class="text-xs text-blue-100">Online • Powered by AI</p>
                </div>
            </div>
            <button id="chat-close" class="hover:bg-blue-500 rounded-full p-1 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Messages -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 chat-messages">
            <div class="flex justify-start">
                <div class="max-w-[80%] rounded-lg p-3 bg-white text-gray-800 shadow-sm border border-gray-200">
                    <p class="text-sm">Hallo! 👋 Ik ben je AI slaap assistent.<br><br>Ik help je graag met:<br><br>🛏️ <strong>Matrasadvies</strong> - Welk matras past bij jou?<br>😴 <strong>Slaapadvies</strong> - Tips voor beter slapen<br>📦 <strong>Je bestelling</strong> - Track je order<br>❓ <strong>Vragen</strong> - Over onze Bremafa matrassen<br><br>Waar kan ik je mee helpen?</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div id="quick-actions" class="px-4 py-2 border-t bg-white">
            <p class="text-xs text-gray-500 mb-2">Snelle acties:</p>
            <div class="flex gap-2">
                <button class="quick-action flex-1 flex flex-col items-center gap-1 p-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition text-xs" data-prompt="Welk matras past bij mij?">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-gray-700">Matrasadvies</span>
                </button>
                <button class="quick-action flex-1 flex flex-col items-center gap-1 p-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition text-xs" data-prompt="Ik slaap slecht, kun je helpen?">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <span class="text-gray-700">Slaapadvies</span>
                </button>
                <button class="quick-action flex-1 flex flex-col items-center gap-1 p-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition text-xs" data-prompt="Waar is mijn bestelling?">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="text-gray-700">Mijn Order</span>
                </button>
            </div>
        </div>

        <!-- Input -->
        <div class="p-4 border-t bg-white rounded-b-lg">
            <div class="flex gap-2">
                <input id="chat-input" type="text" placeholder="Stel je vraag..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button id="chat-send" class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white rounded-lg px-4 py-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// CONFIGURATIE
const WORDPRESS_URL = 'https://autorijschoolvleuten.nl';

console.log('🤖 AI CHATBOT GELADEN!');

// Chat elements
const chatToggle = document.getElementById('chat-toggle');
const chatWindow = document.getElementById('chat-window');
const chatClose = document.getElementById('chat-close');
const chatInput = document.getElementById('chat-input');
const chatSend = document.getElementById('chat-send');
const chatMessages = document.getElementById('chat-messages');
const quickActions = document.querySelectorAll('.quick-action');
const quickActionsContainer = document.getElementById('quick-actions');

// Conversation state
let messageCount = 1;
let conversationContext = {
    waitingForOrderNumber: false,
    waitingForEmail: false,
    orderNumber: null,
    lastTopic: null,
    history: [] // Voor AI context
};

// Toggle chat
chatToggle.addEventListener('click', () => {
    chatToggle.classList.add('hidden');
    chatWindow.classList.remove('hidden');
});

chatClose.addEventListener('click', () => {
    chatWindow.classList.add('hidden');
    chatToggle.classList.remove('hidden');
});

// Quick actions
quickActions.forEach(action => {
    action.addEventListener('click', () => {
        const prompt = action.dataset.prompt;
        chatInput.value = prompt;
        sendMessage();
    });
});

// Send message
async function sendMessage() {
    const message = chatInput.value.trim();
    if (!message) return;

    addMessage(message, 'user');
    chatInput.value = '';
    messageCount++;

    if (messageCount > 2) {
        quickActionsContainer.classList.add('hidden');
    }

    // Voeg toe aan geschiedenis
    conversationContext.history.push({
        role: 'user',
        content: message
    });

    showLoading();

    try {
        const response = await getAIResponse(message);
        hideLoading();
        addMessage(response, 'assistant');
        
        // Voeg AI antwoord toe aan geschiedenis
        conversationContext.history.push({
            role: 'assistant',
            content: response
        });
        
        // Beperk geschiedenis tot laatste 10 berichten
        if (conversationContext.history.length > 10) {
            conversationContext.history = conversationContext.history.slice(-10);
        }
    } catch (error) {
        hideLoading();
        console.error('Error:', error);
        addMessage('Sorry, er ging iets mis. Probeer het opnieuw.', 'assistant');
    }
}

chatSend.addEventListener('click', sendMessage);
chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage();
});

// Main AI Response Handler
async function getAIResponse(message) {
    const lowerMessage = message.toLowerCase();

    // ORDER FLOW (bestaand - blijft werken)
    if (conversationContext.waitingForEmail) {
        return handleEmailInput(message);
    }

    if (conversationContext.waitingForOrderNumber) {
        return handleOrderNumberInput(message);
    }

    // Check if order-related question
    if (isOrderQuestion(lowerMessage)) {
        conversationContext.waitingForOrderNumber = true;
        return "Ik help je graag met je bestelling! 📦<br><br>Wat is je <strong>ordernummer</strong>?<br><br><small>Je vindt het in je orderbevestiging email of in je account.</small>";
    }

    // AI RESPONSE (nieuw!)
    return await getGeminiResponse(message);
}

// Check if question is order-related
function isOrderQuestion(message) {
    const orderKeywords = ['order', 'bestelling', 'pakket', 'track', 'volgen', 'status', 'levering', 'verzonden', 'bezorg'];
    return orderKeywords.some(keyword => message.includes(keyword));
}

// Gemini AI Response
async function getGeminiResponse(message) {
    try {
        const response = await fetch(`${WORDPRESS_URL}/wp-json/chatbot/v1/ai-chat`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                message: message,
                history: conversationContext.history
            })
        });

        const data = await response.json();
        
        if (data.success) {
            return data.message;
        } else {
            return data.message || 'Sorry, ik kon geen antwoord genereren. Probeer het opnieuw.';
        }
    } catch (error) {
        console.error('AI Error:', error);
        return 'Sorry, ik kan momenteel geen verbinding maken. Probeer het later opnieuw.';
    }
}

// ORDER HANDLING (bestaande functies - blijven ongewijzigd)
function handleOrderNumberInput(message) {
    const orderMatch = message.match(/#?\d+/);
    
    if (orderMatch) {
        conversationContext.waitingForOrderNumber = false;
        conversationContext.waitingForEmail = true;
        conversationContext.orderNumber = orderMatch[0].replace('#', '');
        
        return `Perfect! Ordernummer <strong>#${conversationContext.orderNumber}</strong> ontvangen.<br><br>Wat is je <strong>e-mailadres</strong> waarmee je besteld hebt?`;
    } else {
        conversationContext.waitingForOrderNumber = false;
        return "Ik zie geen geldig ordernummer. 😕<br><br>Een ordernummer bestaat uit cijfers (bijv. #12345).<br><br>Wil je het opnieuw proberen?";
    }
}

function handleEmailInput(message) {
    const emailRegex = /[\w\.-]+@[\w\.-]+\.\w+/;
    const emailMatch = message.match(emailRegex);
    
    if (emailMatch) {
        conversationContext.waitingForEmail = false;
        const email = emailMatch[0];
        return fetchOrderStatus(conversationContext.orderNumber, email);
    } else {
        return "Dat lijkt geen geldig e-mailadres. 📧<br><br>Probeer het opnieuw (bijv. naam@voorbeeld.nl)";
    }
}

async function fetchOrderStatus(orderNumber, email) {
    try {
        const response = await fetch(`${WORDPRESS_URL}/wp-json/chatbot/v1/order`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                order_number: orderNumber,
                email: email
            })
        });

        const data = await response.json();
        
        if (data.success) {
            const order = data.order;
            let html = `✅ <strong>Order #${order.number} gevonden!</strong><br><br>`;
            html += `<strong>Status:</strong> ${order.status_name}<br>`;
            html += `<strong>Datum:</strong> ${order.date}<br>`;
            html += `<strong>Totaal:</strong> ${order.total}<br><br>`;
            
            html += `<strong>Producten:</strong><br>`;
            order.items.forEach(item => {
                html += `• ${item.quantity}x ${item.name} - ${item.price}<br>`;
            });
            
            if (order.tracking_number) {
                html += `<br><strong>Track & Trace:</strong> ${order.tracking_number}<br>`;
            }
            
            html += `<br><strong>Bezorgadres:</strong><br>${order.shipping.address || order.billing.address}`;
            
            return html;
        } else {
            return `❌ ${data.message}<br><br>Controleer je ordernummer en e-mailadres.`;
        }
    } catch (error) {
        console.error('Order API Error:', error);
        return 'Sorry, ik kon je order niet ophalen. Probeer het later opnieuw.';
    }
}

// UI FUNCTIONS
function addMessage(content, role) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
    
    const bubble = document.createElement('div');
    bubble.className = `max-w-[80%] rounded-lg p-3 ${
        role === 'user' 
            ? 'bg-blue-600 text-white' 
            : 'bg-white text-gray-800 shadow-sm border border-gray-200'
    }`;
    
    const text = document.createElement('p');
    text.className = 'text-sm whitespace-pre-line';
    text.innerHTML = content;
    
    bubble.appendChild(text);
    messageDiv.appendChild(bubble);
    chatMessages.appendChild(messageDiv);
    
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function showLoading() {
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loading-indicator';
    loadingDiv.className = 'flex justify-start';
    loadingDiv.innerHTML = `
        <div class="bg-white text-gray-800 rounded-lg p-3 shadow-sm border border-gray-200">
            <svg class="w-5 h-5 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    `;
    chatMessages.appendChild(loadingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function hideLoading() {
    const loading = document.getElementById('loading-indicator');
    if (loading) loading.remove();
}
</script>

</body>
</html>
