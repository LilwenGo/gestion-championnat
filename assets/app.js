import './bootstrap.js';
import 'bootstrap/dist/css/bootstrap.min.css';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

const navLinks = document.querySelectorAll('.nav-link');

for(const link of navLinks) {
    if(link.getAttribute('href') === window.location.pathname) {
        link.classList.add("nav-link-active");
    }
}
