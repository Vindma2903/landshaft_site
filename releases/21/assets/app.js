import { registerSvelteControllerComponents } from '@symfony/ux-svelte';
import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './css/app.css';
import './css/misc.css';
import 'line-awesome/dist/line-awesome/css/line-awesome.min.css';

import './js/horizontail-scroll-wheel';

// Интерактивные точки на схеме сада (страница ландшафтного проектирования)
document.addEventListener('DOMContentLoaded', () => {
    const area = document.querySelector('[data-hotspot-area="landshaft"]');
    if (!area) {
        return;
    }

    const tooltip = area.querySelector('[data-hotspot-tooltip]');
    const imageEl = tooltip.querySelector('[data-hotspot-image]');
    const captionEl = tooltip.querySelector('[data-hotspot-caption]');
    const closeBtn = tooltip.querySelector('[data-hotspot-close]');

    const openTooltip = (btn) => {
        const img = btn.getAttribute('data-image');
        const caption = btn.getAttribute('data-caption');

        if (img) {
            imageEl.src = img;
        }
        if (caption) {
            captionEl.textContent = caption;
        }

        tooltip.classList.remove('hidden');
    };

    area.querySelectorAll('[data-hotspot-button]').forEach((btn) => {
        btn.addEventListener('click', (event) => {
            event.stopPropagation();
            openTooltip(btn);
        });
    });

    closeBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        tooltip.classList.add('hidden');
    });

    // Клик по фону схемы скрывает подсказку
    area.addEventListener('click', () => {
        tooltip.classList.add('hidden');
    });
});

registerSvelteControllerComponents(require.context('./svelte_components', true, /\.svelte$/));