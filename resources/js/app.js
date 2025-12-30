import './bootstrap';
import { configureEcho } from './reverb';
import Alpine from 'alpinejs';
import './admin/sidebar';
import './admin/topbar';
import './national-id';

window.Alpine = Alpine;

configureEcho();

Alpine.start();
