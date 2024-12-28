import { Client, LocalAuth } from 'whatsapp-web.js';
import express from 'express';
import cors from 'cors';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// Initialize Express server
const app = express();
const PORT = 3000;

// Initialize WhatsApp Client
const client = new Client({
    authStrategy: new LocalAuth({
        dataPath: __dirname + '/.wwebjs_auth'
    }),
    puppeteer: {
        headless: true,
    }
});

// QR code generation event
client.on('qr', (qr) => {
    console.log('QR Code available. Scan using WhatsApp app.');
    console.log(qr);
});

// Client ready event
client.on('ready', () => {
    console.log('✅ WhatsApp Client is ready!');
});

// Message handling event
client.on('message', (message) => {
    console.log(`📩 Message received: ${message.body}`);
    if (message.body === 'Hi') {
        message.reply('Hello! How can I help you?');
    }
});

// Connection status route
app.get('/status', (req, res) => {
    const status = client.info ? 'Connected' : 'Disconnected';
    res.json({ status });
});

// Send message route
app.post('/send-message', (req, res) => {
    const { to, message } = req.body;
    client.sendMessage(to, message);
    res.json({ message: 'Message sent successfully' });
});

// Start Express server
app.listen(PORT, () => {
    console.log(`🚀 Server running at http://localhost:${PORT}`);
});

// Initialize WhatsApp Client
client.initialize();
