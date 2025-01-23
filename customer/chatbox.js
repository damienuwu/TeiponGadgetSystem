function toggleChatbox() {
    const chatboxSection = document.getElementById('chatbox-section');
    if (chatboxSection.style.display === 'none') {
        chatboxSection.style.display = 'block';
        sendWelcomeMessage();
    } else {
        chatboxSection.style.display = 'none';
    }
}

function minimizeChat() {
    const chatboxSection = document.getElementById('chatbox-section');
    chatboxSection.style.display = 'none';
}

function sendWelcomeMessage() {
    fetch('../chatbox/chatbot.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            message: "hello"
        }), // Custom message for welcome intent
    })
        .then(response => response.json())
        .then(data => {
            const messages = document.getElementById('messages');
            messages.innerHTML += `
                <div class="message bot">
                    <div class="message-content"> ${data.reply}</div>
                </div>
            `;
            messages.scrollTop = messages.scrollHeight;
        })
        .catch(() => {
            const messages = document.getElementById('messages');
            messages.innerHTML += `
                <div class="message bot">
                    <div class="message-content"> Sorry, there was an error initializing the chat.</div>
                </div>
            `;
        });
}

function sendMessage() {
    const userInput = document.getElementById('userInput').value.trim();
    if (!userInput) return;

    // Clear the input field  
    document.getElementById('userInput').value = '';

    const messages = document.getElementById('messages');

    // Get the current time  
    const now = new Date();
    const options = { hour: '2-digit', minute: '2-digit' };
    const timeSent = now.toLocaleTimeString('en-US', options);

    // Append the user's message along with the timestamp  
    messages.innerHTML += `  
        <div class="message user">  
            <div class="message-content"><strong>You:</strong> ${userInput}</div>  
            <div class="message-time">${timeSent}</div>
        </div>  
    `;

    // Add loading bubble  
    const loadingBubble = document.createElement('div');
    loadingBubble.className = 'message bot';
    loadingBubble.innerHTML = `  
        <div class="loading-bubble"> . . . </div>  
    `;
    messages.appendChild(loadingBubble);
    messages.scrollTop = messages.scrollHeight;

    setTimeout(() => {
        // Replace loading bubble with bot response  
        loadingBubble.remove();
        fetch('../chatbox/chatbot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: userInput
            }),
        })
            .then(response => response.json())
            .then(data => {
                // Get the current time for the bot response  
                const botTimeSent = new Date().toLocaleTimeString('en-US', options);
                messages.innerHTML += `  
                    <div class="message bot">  
                        <div class="message-content">${data.reply}</div>  
                        <div class="message-time">${botTimeSent}</div>
                    </div>  
                `;
                messages.scrollTop = messages.scrollHeight;
            })
            .catch(() => {
                messages.innerHTML += `  
                    <div class="message bot">  
                        <div class="message-content">Sorry, there was an error processing your message.</div>  
                    </div>  
                `;
            });
    }, 1500); // Delay for loading  
}

window.onload = function () {
    const userInput = document.getElementById('userInput');
    if (userInput) {
        userInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                //              console.log('Key pressed:', event.key);
                sendMessage();
                event.preventDefault(); // Prevent default form submission
            }
        });
    }
};


