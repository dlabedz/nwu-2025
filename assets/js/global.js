/*!
 * Immediately Invoked Function Expression Boilerplate
 * (c) 2019 Chris Ferdinandi, MIT License, https://gomakethings.com
 */
;(function () {

	'use strict';

	// Element variables
	const menuToggle = document.querySelector('.menu-toggle');
	const memberPagesToggle = document.querySelector('.member-pages-toggle');
	const navMenu = document.querySelector('.nav-menu[role="navigation"]');
	const navMembers = document.querySelector('.nav-menu-members');

	const elementExists = function(element) {
		if ( typeof(element) != 'undefined' && element != null ) {
			return true;
		}
		return false;
	}

	// Event functions
	const toggleMenu = function(event) {
		if ( !event.target.closest('.menu-toggle') ) return;

		// Close members menu if open
		if ( elementExists(memberPagesToggle) && memberPagesToggle.classList.contains('active') ) {
			memberPagesToggle.classList.remove('active');
			if ( elementExists(navMembers) ) {
				navMembers.classList.remove('active');
			}
		}

		// Toggle primary menu
		if ( elementExists(navMenu) ) {
			navMenu.classList.toggle('active');
		}

		menuToggle.classList.toggle('active');
	}

	const toggleMemberPages = function(event) {
		if ( !event.target.closest('.member-pages-toggle') ) return;

		// Close main menu if open
		if ( elementExists(menuToggle) && menuToggle.classList.contains('active') ) {
			menuToggle.classList.remove('active');
			if ( elementExists(navMenu) ) {
				navMenu.classList.remove('active');
			}
		}

		// Toggle members menu
		if ( elementExists(navMembers) ) {
			navMembers.classList.toggle('active');
		}

		memberPagesToggle.classList.toggle('active');
	}

	const toggleSubMenu = function(event) {
		if ( !event.target.closest('.submenu-expand') ) return;
		event.target.closest('.submenu-expand').classList.toggle('expanded');
		event.preventDefault();
	}

	// Add functions to click event listener
	document.addEventListener('click', function(event) {
		toggleMenu(event);
		if ( elementExists(memberPagesToggle) ) {
			toggleMemberPages(event);
		}
		toggleSubMenu(event);
	});

})();
