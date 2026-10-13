import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import { lightbox, beforeAfter, galleryFilter } from './components';

document.documentElement.classList.add('js');

Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.data('lightbox', lightbox);
Alpine.data('beforeAfter', beforeAfter);
Alpine.data('galleryFilter', galleryFilter);

window.Alpine = Alpine;
Alpine.start();

// Reveal elements as they scroll into view. Content must never stay hidden: anything already
// on screen shows immediately, and browsers without IntersectionObserver get everything at once.
const revealables = [...document.querySelectorAll('[data-reveal]')];
const reveal = (el) => el.classList.add('is-visible');

if (! ('IntersectionObserver' in window)) {
    revealables.forEach(reveal);
} else {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                reveal(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -5% 0px' });

    revealables.forEach((el) => {
        el.getBoundingClientRect().top < window.innerHeight ? reveal(el) : observer.observe(el);
    });
}
