import './bootstrap';
import { io } from "socket.io-client";
import * as monaco from 'monaco-editor';
import Alpine from 'alpinejs';

window.monaco = monaco;
window.Alpine = Alpine;

Alpine.start();

window.socket = io('http://localhost:3001');

window.socket.on('connect', () => {
    console.log('Connected To Socket Server');
});
