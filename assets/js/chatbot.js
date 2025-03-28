/**
 * Script pour le chatbot de MGS Store
 */
document.addEventListener('DOMContentLoaded', function() {
    // Référence aux éléments du DOM
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSendBtn = document.getElementById('chatbot-send');
    
    // Base de connaissances pour les réponses
    const knowledgeBase = {
        "bonjour": "Bonjour! 👋 Comment puis-je vous aider aujourd'hui?",
        "salut": "Salut! 👋 Comment puis-je vous aider aujourd'hui?",
        "hello": "Hello! 👋 Comment puis-je vous aider aujourd'hui?",
        "aide": "Je serais ravi de vous aider! Vous pouvez me poser des questions sur nos produits, tailles, expédition, retours, etc.",
        "taille": "Pour choisir la bonne taille, veuillez consulter notre guide des tailles. En général, si vous mesurez 1m80, une taille L devrait vous convenir pour la plupart de nos vêtements.",
        "expedition": "Nous expédions généralement les commandes sous 24-48h. La livraison est gratuite pour les commandes de plus de 50€. Le délai de livraison varie entre 3-5 jours ouvrables.",
        "livraison": "La livraison est gratuite pour les commandes de plus de 50€. Sinon, les frais sont de 4.99€. Nous livrons en France, Belgique, Suisse et Luxembourg.",
        "retour": "Vous pouvez retourner un article dans les 30 jours suivant la réception. L'article doit être dans son état d'origine avec les étiquettes attachées.",
        "paiement": "Nous acceptons les cartes Visa, Mastercard, les paiements par PayPal, Apple Pay et Google Pay.",
        "promotions": "Abonnez-vous à notre newsletter pour être informé de nos dernières promotions. Nous proposons régulièrement des offres spéciales!",
        "contact": "Vous pouvez nous contacter par email à contact@mgs-store.com ou par téléphone au 01 23 45 67 89 du lundi au vendredi de 9h à 18h.",
        "magasin": "Notre magasin physique est situé au 123 Rue du Sport, 75001 Paris. Ouvert du lundi au samedi de 10h à 19h.",
        "merci": "Je vous en prie! 😊 N'hésitez pas si vous avez d'autres questions.",
        "au revoir": "Au revoir! Passez une excellente journée! N'hésitez pas à revenir si vous avez d'autres questions. 👋",
        "nike": "Nos produits Nike sont de grande qualité. Pour un t-shirt Nike, si vous mesurez 1m80, nous recommandons une taille L. Nos t-shirts Nike sont disponibles en plusieurs coloris: bleu ciel, jaune, rose et rouge.",
        "adidas": "Notre collection Adidas offre confort et style. Pour un t-shirt Adidas, si vous mesurez 1m80, nous recommandons une taille L. Nos t-shirts Adidas sont disponibles en noir, blanc et bleu.",
        "teddy smith": "Teddy Smith propose des vêtements à la fois tendance et confortables. Pour quelqu'un de 1m80, une taille L serait adaptée. Nos articles Teddy Smith sont disponibles en noir, gris et marron."
    };
    
    // Questions suggérées
    const suggestedQuestions = [
        "Quelle taille prendre pour 1m80?",
        "Comment fonctionnent les retours?",
        "Quel est le délai de livraison?",
        "La livraison est-elle gratuite?",
        "Quels modes de paiement acceptez-vous?",
        "Avez-vous des promotions en cours?",
        "Où est votre magasin physique?",
        "Comment vous contacter?"
    ];
    
    // Fonction pour afficher les questions suggérées
    function displaySuggestedQuestions() {
        const suggestedContainer = document.createElement('div');
        suggestedContainer.className = 'suggested-questions';
        
        suggestedQuestions.forEach(question => {
            const questionEl = document.createElement('div');
            questionEl.className = 'suggested-question';
            questionEl.textContent = question;
            questionEl.addEventListener('click', () => {
                chatbotInput.value = question;
                sendMessage();
            });
            suggestedContainer.appendChild(questionEl);
        });
        
        chatbotMessages.appendChild(suggestedContainer);
        scrollToBottom();
    }
    
    // Ouvrir le chatbot
    chatbotToggle.addEventListener('click', function() {
        chatbotContainer.classList.add('active');
        // Si c'est la première ouverture, afficher le message de bienvenue
        if (chatbotMessages.children.length === 0) {
            addMessage("Bonjour! 👋 Je suis l'assistant virtuel de MGS Store. Comment puis-je vous aider aujourd'hui?", 'bot');
            displaySuggestedQuestions();
        }
    });
    
    // Fermer le chatbot
    chatbotClose.addEventListener('click', function() {
        chatbotContainer.classList.remove('active');
    });
    
    // Fonction pour ajouter un message au chat
    function addMessage(text, sender) {
        const message = document.createElement('div');
        message.className = `message ${sender}-message`;
        message.textContent = text;
        chatbotMessages.appendChild(message);
        scrollToBottom();
    }
    
    // Fonction pour faire défiler vers le bas
    function scrollToBottom() {
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    
    // Fonction pour gérer la simulation de frappe du bot
    function showTypingIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'typing-indicator';
        indicator.innerHTML = '<span></span><span></span><span></span>';
        chatbotMessages.appendChild(indicator);
        scrollToBottom();
        return indicator;
    }
    
    // Fonction pour obtenir une réponse du bot
    function getBotResponse(message) {
        // Convertir le message en minuscules
        const lowerMessage = message.toLowerCase();
        
        // Recherche de mots-clés
        for (const [keyword, response] of Object.entries(knowledgeBase)) {
            if (lowerMessage.includes(keyword)) {
                return response;
            }
        }
        
        // Réponse par défaut si aucun mot-clé n'est trouvé
        return "Je ne suis pas sûr de comprendre votre question. Pouvez-vous reformuler ou choisir parmi les questions suggérées ci-dessous?";
    }
    
    // Fonction pour envoyer un message
    function sendMessage() {
        const message = chatbotInput.value.trim();
        
        if (message) {
            // Ajouter le message de l'utilisateur
            addMessage(message, 'user');
            chatbotInput.value = '';
            
            // Afficher l'indicateur de frappe
            const typingIndicator = showTypingIndicator();
            
            // Simuler un délai de réponse
            setTimeout(() => {
                // Supprimer l'indicateur de frappe
                typingIndicator.remove();
                
                // Répondre
                const botResponse = getBotResponse(message);
                addMessage(botResponse, 'bot');
                
                // Afficher à nouveau les questions suggérées
                if (botResponse.includes("reformuler") || message.toLowerCase().includes("aide")) {
                    displaySuggestedQuestions();
                }
            }, 1000 + Math.random() * 1000); // Délai aléatoire entre 1 et 2 secondes
        }
    }
    
    // Envoyer un message en cliquant sur le bouton
    chatbotSendBtn.addEventListener('click', sendMessage);
    
    // Envoyer un message en appuyant sur Entrée
    chatbotInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});
