/**
 * Accordion Block JavaScript
 * Handles expand/collapse functionality
 */
;(function () {
	'use strict';

	/**
	 * Toggle accordion item
	 */
	const toggleAccordion = function(button) {
		const item = button.closest('.accordion-item');
		const answer = item.querySelector('.accordion-answer');
		const isActive = item.classList.contains('active');

		// Toggle active state
		if (isActive) {
			// Close
			item.classList.remove('active');
			button.setAttribute('aria-expanded', 'false');
			answer.style.display = 'none';
		} else {
			// Open
			item.classList.add('active');
			button.setAttribute('aria-expanded', 'true');
			answer.style.display = 'block';
		}
	};

	/**
	 * Handle click events
	 */
	document.addEventListener('click', function(event) {
		const button = event.target.closest('.accordion-question');
		if (!button) return;

		event.preventDefault();
		toggleAccordion(button);
	});

	/**
	 * Handle keyboard events
	 */
	document.addEventListener('keydown', function(event) {
		const button = event.target.closest('.accordion-question');
		if (!button) return;

		// Space or Enter key
		if (event.key === ' ' || event.key === 'Enter') {
			event.preventDefault();
			toggleAccordion(button);
		}
	});

})();
