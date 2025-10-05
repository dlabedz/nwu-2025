/*!
 * Back to Top Button
 * Shows button after scrolling down 500px
 * Smooth scroll to top on click
 */
;(function () {
	'use strict';

	// Configuration
	const SCROLL_THRESHOLD = 500; // Show button after scrolling this many pixels
	const SCROLL_DURATION = 600; // Duration of scroll animation in milliseconds

	// Get the back to top button
	const backToTopBtn = document.querySelector('.back-to-top');

	if (!backToTopBtn) {
		return; // Exit if button doesn't exist
	}

	// Show/hide button based on scroll position
	const toggleButtonVisibility = function() {
		if (window.pageYOffset > SCROLL_THRESHOLD) {
			backToTopBtn.classList.add('visible');
		} else {
			backToTopBtn.classList.remove('visible');
		}
	};

	// Smooth scroll to top
	const scrollToTop = function(event) {
		event.preventDefault();

		// Use native smooth scroll if supported
		if ('scrollBehavior' in document.documentElement.style) {
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		} else {
			// Fallback for browsers that don't support smooth scroll
			const scrollStep = -window.pageYOffset / (SCROLL_DURATION / 15);
			const scrollInterval = setInterval(function() {
				if (window.pageYOffset !== 0) {
					window.scrollBy(0, scrollStep);
				} else {
					clearInterval(scrollInterval);
				}
			}, 15);
		}

		// Optional: Track analytics event
		if (typeof gtag !== 'undefined') {
			gtag('event', 'click', {
				'event_category': 'Navigation',
				'event_label': 'Back to Top'
			});
		}
	};

	// Event listeners
	window.addEventListener('scroll', toggleButtonVisibility);
	window.addEventListener('load', toggleButtonVisibility);
	backToTopBtn.addEventListener('click', scrollToTop);

	// Keyboard accessibility
	backToTopBtn.addEventListener('keypress', function(event) {
		if (event.key === 'Enter' || event.key === ' ') {
			scrollToTop(event);
		}
	});

})();
