import React, { useState, useRef, useEffect } from 'react';
import { MessageCircle, X, Send, Package, ShoppingCart, HelpCircle, Loader2 } from 'lucide-react';

export default function WooCommerceChatbot() {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState([
    {
      role: 'assistant',
      content: 'Hi! 👋 Welcome to our store. I\'m here to help you with:\n\n• Product recommendations\n• Order tracking\n• Shipping information\n• Returns & refunds\n• General inquiries\n\nWhat can I help you with today?'
    }
  ]);
  const [input, setInput] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const messagesEndRef = useRef(null);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  const quickActions = [
    { icon: Package, label: 'Track Order', prompt: 'I want to track my order' },
    { icon: ShoppingCart, label: 'Product Help', prompt: 'I need help finding a product' },
    { icon: HelpCircle, label: 'Returns', prompt: 'How do I return an item?' }
  ];

  const getAIResponse = async (userMessage) => {
    const lowerMessage = userMessage.toLowerCase();
    
    // Simulate AI processing delay
    await new Promise(resolve => setTimeout(resolve, 800));

    // Order tracking
    if (lowerMessage.includes('track') || lowerMessage.includes('order status') || lowerMessage.includes('where is my order')) {
      return "I'd be happy to help you track your order! 📦\n\nTo look up your order status, I'll need your:\n• Order number (starts with #)\n• Email address used for the purchase\n\nYou can also track your order by:\n1. Logging into your account\n2. Going to 'My Orders'\n3. Clicking on the specific order\n\nWhat's your order number?";
    }

    // Product recommendations
    if (lowerMessage.includes('recommend') || lowerMessage.includes('suggest') || lowerMessage.includes('looking for') || lowerMessage.includes('find a product')) {
      return "I'd love to help you find the perfect product! 🎯\n\nCould you tell me more about:\n• What type of product you're looking for?\n• Your budget range?\n• Any specific features you need?\n• Who it's for?\n\nOur popular categories include:\n• Electronics & Gadgets\n• Home & Living\n• Fashion & Accessories\n• Sports & Outdoors";
    }

    // Returns and refunds
    if (lowerMessage.includes('return') || lowerMessage.includes('refund') || lowerMessage.includes('exchange')) {
      return "I can help you with returns and refunds! 🔄\n\n**Our Return Policy:**\n• 30-day return window\n• Items must be unused and in original packaging\n• Free return shipping on defective items\n\n**How to start a return:**\n1. Log into your account\n2. Go to 'My Orders'\n3. Select the order\n4. Click 'Request Return'\n5. Choose reason and follow instructions\n\nRefunds are processed within 5-7 business days after we receive the item.\n\nDo you have a specific order you'd like to return?";
    }

    // Shipping information
    if (lowerMessage.includes('shipping') || lowerMessage.includes('delivery') || lowerMessage.includes('how long')) {
      return "Here's our shipping information! 🚚\n\n**Shipping Options:**\n• Standard (5-7 business days) - $4.99\n• Express (2-3 business days) - $12.99\n• Overnight (1 business day) - $24.99\n• FREE shipping on orders over $50!\n\n**International Shipping:**\nAvailable to most countries (10-15 business days)\n\nYou'll receive a tracking number via email once your order ships.\n\nIs there a specific shipping question I can help with?";
    }

    // Payment methods
    if (lowerMessage.includes('payment') || lowerMessage.includes('pay') || lowerMessage.includes('credit card')) {
      return "We accept multiple payment methods! 💳\n\n**Accepted Payments:**\n• Credit/Debit Cards (Visa, Mastercard, Amex, Discover)\n• PayPal\n• Apple Pay\n• Google Pay\n• Shop Pay\n\nAll transactions are secured with 256-bit SSL encryption.\n\nIs there a specific payment method you'd like to use?";
    }

    // Discounts and coupons
    if (lowerMessage.includes('discount') || lowerMessage.includes('coupon') || lowerMessage.includes('promo') || lowerMessage.includes('sale')) {
      return "Great question about savings! 🎉\n\n**Current Promotions:**\n• Use code WELCOME10 for 10% off your first order\n• Free shipping on orders over $50\n• Sign up for our newsletter for exclusive deals\n\n**How to apply a coupon:**\n1. Add items to cart\n2. Go to checkout\n3. Enter code in 'Coupon Code' field\n4. Click 'Apply'\n\nWould you like to know about any specific product deals?";
    }

    // Contact support
    if (lowerMessage.includes('contact') || lowerMessage.includes('support') || lowerMessage.includes('phone') || lowerMessage.includes('email')) {
      return "Here's how to reach our support team! 📞\n\n**Contact Options:**\n• Email: support@yourstore.com\n• Phone: 1-800-123-4567 (Mon-Fri, 9am-6pm EST)\n• Live Chat: Available here in the chatbot\n• Help Center: yourstore.com/help\n\n**Response Times:**\n• Chat: Immediate\n• Email: Within 24 hours\n• Phone: Immediate during business hours\n\nHow else can I assist you today?";
    }

    // Account help
    if (lowerMessage.includes('account') || lowerMessage.includes('login') || lowerMessage.includes('password') || lowerMessage.includes('sign up')) {
      return "I can help with account issues! 👤\n\n**Common Account Actions:**\n• Reset Password: Click 'Forgot Password' on login page\n• Create Account: Click 'Register' in top menu\n• Update Info: Go to 'My Account' → 'Account Details'\n\n**Account Benefits:**\n• Faster checkout\n• Order history tracking\n• Save multiple addresses\n• Wishlist feature\n• Exclusive member deals\n\nWhat specific account help do you need?";
    }

    // Default response
    return "I'm here to help! I can assist you with:\n\n• 📦 **Order tracking** - Check your order status\n• 🛍️ **Product recommendations** - Find what you need\n• 🚚 **Shipping info** - Delivery times and costs\n• 🔄 **Returns & refunds** - Easy return process\n• 💳 **Payment options** - Secure checkout methods\n• 🎉 **Current promotions** - Save on your purchase\n\nCould you please tell me more about what you're looking for, or choose one of the quick actions below?";
  };

  const handleSend = async () => {
    if (!input.trim() || isLoading) return;

    const userMessage = input.trim();
    setInput('');
    
    setMessages(prev => [...prev, { role: 'user', content: userMessage }]);
    setIsLoading(true);

    try {
      const response = await getAIResponse(userMessage);
      setMessages(prev => [...prev, { role: 'assistant', content: response }]);
    } catch (error) {
      setMessages(prev => [...prev, { 
        role: 'assistant', 
        content: 'I apologize, but I encountered an error. Please try again or contact our support team for assistance.' 
      }]);
    } finally {
      setIsLoading(false);
    }
  };

  const handleQuickAction = (prompt) => {
    setInput(prompt);
    setTimeout(() => handleSend(), 100);
  };

  return (
    <div className="fixed bottom-6 right-6 z-50">
      {/* Chat Button */}
      {!isOpen && (
        <button
          onClick={() => setIsOpen(true)}
          className="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all hover:scale-110"
        >
          <MessageCircle className="w-6 h-6" />
        </button>
      )}

      {/* Chat Window */}
      {isOpen && (
        <div className="bg-white rounded-lg shadow-2xl w-96 h-[600px] flex flex-col">
          {/* Header */}
          <div className="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-t-lg flex justify-between items-center">
            <div className="flex items-center gap-2">
              <MessageCircle className="w-5 h-5" />
              <div>
                <h3 className="font-semibold">Shopping Assistant</h3>
                <p className="text-xs text-blue-100">Online • Typically replies instantly</p>
              </div>
            </div>
            <button
              onClick={() => setIsOpen(false)}
              className="hover:bg-blue-500 rounded-full p-1 transition"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Messages */}
          <div className="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
            {messages.map((msg, idx) => (
              <div
                key={idx}
                className={`flex ${msg.role === 'user' ? 'justify-end' : 'justify-start'}`}
              >
                <div
                  className={`max-w-[80%] rounded-lg p-3 ${
                    msg.role === 'user'
                      ? 'bg-blue-600 text-white'
                      : 'bg-white text-gray-800 shadow-sm border border-gray-200'
                  }`}
                >
                  <p className="text-sm whitespace-pre-line">{msg.content}</p>
                </div>
              </div>
            ))}
            
            {isLoading && (
              <div className="flex justify-start">
                <div className="bg-white text-gray-800 rounded-lg p-3 shadow-sm border border-gray-200">
                  <Loader2 className="w-5 h-5 animate-spin text-blue-600" />
                </div>
              </div>
            )}
            
            <div ref={messagesEndRef} />
          </div>

          {/* Quick Actions */}
          {messages.length <= 2 && (
            <div className="px-4 py-2 border-t bg-white">
              <p className="text-xs text-gray-500 mb-2">Quick actions:</p>
              <div className="flex gap-2">
                {quickActions.map((action, idx) => (
                  <button
                    key={idx}
                    onClick={() => handleQuickAction(action.prompt)}
                    className="flex-1 flex flex-col items-center gap-1 p-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition text-xs"
                  >
                    <action.icon className="w-4 h-4 text-blue-600" />
                    <span className="text-gray-700">{action.label}</span>
                  </button>
                ))}
              </div>
            </div>
          )}

          {/* Input */}
          <div className="p-4 border-t bg-white rounded-b-lg">
            <div className="flex gap-2">
              <input
                type="text"
                value={input}
                onChange={(e) => setInput(e.target.value)}
                onKeyPress={(e) => e.key === 'Enter' && handleSend()}
                placeholder="Type your message..."
                className="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                disabled={isLoading}
              />
              <button
                onClick={handleSend}
                disabled={!input.trim() || isLoading}
                className="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white rounded-lg px-4 py-2 transition"
              >
                <Send className="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}