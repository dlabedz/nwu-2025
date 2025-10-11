/*!
 * Immediately Invoked Function Expression Boilerplate
 * (c) 2019 Chris Ferdinandi, MIT License, https://gomakethings.com
 */
;(function () {

	'use strict';

	// Element variables
	const menuToggle = document.querySelector('.menu-toggle');
	const navMenu = document.querySelector('.nav-menu[role="navigation"]');
	const body = document.body;

	const elementExists = function(element) {
		if ( typeof(element) != 'undefined' && element != null ) {
			return true;
		}
		return false;
	}

	// Event functions
	const toggleMenu = function(event) {
		if ( !event.target.closest('.menu-toggle') ) return;

		if ( elementExists(navMenu) ) {
			navMenu.classList.toggle('active');
		}

		if ( elementExists(menuToggle) ) {
			menuToggle.classList.toggle('active');
		}

		// Toggle body class to prevent scrolling when menu is open
		body.classList.toggle('menu-open');

		// Set aria-expanded attribute for accessibility
		if ( elementExists(menuToggle) ) {
			const isExpanded = menuToggle.classList.contains('active');
			menuToggle.setAttribute('aria-expanded', isExpanded);
		}
	}

	const toggleSubMenu = function(event) {
		if ( !event.target.closest('.submenu-expand') ) return;

		event.preventDefault();

		const button = event.target.closest('.submenu-expand');
		const submenu = button.nextElementSibling;

		button.classList.toggle('expanded');

		// Set aria-expanded for accessibility
		const isExpanded = button.classList.contains('expanded');
		button.setAttribute('aria-expanded', isExpanded);
	}

	// Close menu when clicking outside (mobile only)
	const closeMenuOnClickOutside = function(event) {
		if (!elementExists(navMenu) || !elementExists(menuToggle)) return;

		// Only apply on mobile viewports
		if (window.innerWidth > 768) return;

		const isMenuOpen = navMenu.classList.contains('active');
		const clickedInsideMenu = event.target.closest('.nav-menu') || event.target.closest('.menu-toggle') || event.target.closest('.site-header__toggles');

		if (isMenuOpen && !clickedInsideMenu) {
			navMenu.classList.remove('active');
			menuToggle.classList.remove('active');
			body.classList.remove('menu-open');
			menuToggle.setAttribute('aria-expanded', 'false');
		}
	}

	// Close menu on escape key (mobile only)
	const closeMenuOnEscape = function(event) {
		if (event.key === 'Escape' || event.keyCode === 27) {
			if (window.innerWidth > 768) return;

			if (elementExists(navMenu) && navMenu.classList.contains('active')) {
				navMenu.classList.remove('active');

				if (elementExists(menuToggle)) {
					menuToggle.classList.remove('active');
					menuToggle.setAttribute('aria-expanded', 'false');
					menuToggle.focus(); // Return focus to toggle button
				}

				body.classList.remove('menu-open');
			}
		}
	}

	// Add functions to click event listener
	document.addEventListener('click', function(event) {
		toggleMenu(event);
		toggleSubMenu(event);
		closeMenuOnClickOutside(event);
	});

	// Add keyboard event listener
	document.addEventListener('keydown', closeMenuOnEscape);

	// Set initial ARIA attributes
	if (elementExists(menuToggle)) {
		menuToggle.setAttribute('aria-expanded', 'false');
		menuToggle.setAttribute('aria-label', 'Toggle navigation menu');
	}

	// Set ARIA attributes on submenu toggles
	const submenuToggles = document.querySelectorAll('.submenu-expand');
	submenuToggles.forEach(function(toggle) {
		toggle.setAttribute('aria-expanded', 'false');
		toggle.setAttribute('aria-label', 'Toggle submenu');
	});

	// Archive Filters Auto-Submit
	const archiveFilters = document.getElementById('archive-filters');
	if (archiveFilters) {
		const filterSelects = archiveFilters.querySelectorAll('.auto-submit-filter');

		// Auto-submit when dropdown changes, but remove empty values first
		filterSelects.forEach(select => {
			select.addEventListener('change', function() {
				// Remove empty select values before submitting
				const allSelects = archiveFilters.querySelectorAll('select');
				allSelects.forEach(sel => {
					if (sel.value === '') {
						sel.removeAttribute('name');
					}
				});

				archiveFilters.submit();
			});
		});
	}

})();
