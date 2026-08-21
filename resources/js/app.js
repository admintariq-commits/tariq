import './bootstrap';
import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

Chart.register(...registerables);
window.Chart = Chart;
window.L = L;
window.Alpine = Alpine;
Alpine.start();
