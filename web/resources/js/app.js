import './bootstrap';
import { createIcons, icons } from 'lucide';
import Alpine from 'alpinejs';
import * as d3 from 'd3';

createIcons({ icons });

window.d3 = d3;
window.Alpine = Alpine;
Alpine.start();
