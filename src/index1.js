import { Client } from 'whatsapp-web.js';

const client = new Client();
client.on('qr', qr => {
    // Generate and scan this code with your phone
    console.log('QR RECEIVED', qr);
});

client.on('ready', () => {
    console.log('Client is ready!');
});

client.on('message', message => {
    console.log(message.body);
});

client.initialize();

export { client };
