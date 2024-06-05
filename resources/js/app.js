import jQuery from 'jquery';

window.$ = jQuery;

import './bootstrap';

import './calendar';
import './accordion';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

import {delegate} from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/animations/shift-toward-subtle.css';

// Default configuration for Tippy with event delegation (https://atomiks.github.io/tippyjs/v6/addons/#event-delegation
delegate('body', {
    interactive: true,
    allowHTML: true,
    animation: 'shift-toward-subtle',
    target: '[data-tippy-content]',
});

window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();
import './sweetAlert2';
import 'flowbite';
import './tour'
