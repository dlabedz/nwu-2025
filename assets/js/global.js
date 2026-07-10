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

	// AJAX archive filtering
	const archiveFiltersForm = document.getElementById('archive-filters');
	const archiveListing = document.querySelector('.archive-listing');

	if ( archiveFiltersForm && archiveListing ) {

		const fetchFilteredResults = function() {
			const params = new URLSearchParams(new FormData(archiveFiltersForm));

			// Strip empty params so WordPress doesn't misinterpret them
			for (const [key, value] of [...params.entries()]) {
				if ( !value ) params.delete(key);
			}

			const url = archiveFiltersForm.action + (params.toString() ? '?' + params.toString() : '');

			archiveListing.setAttribute('aria-busy', 'true');
			archiveListing.style.opacity = '0.5';

			fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
				.then(function(response) { return response.text(); })
				.then(function(html) {
					const parser = new DOMParser();
					const doc = parser.parseFromString(html, 'text/html');
					const newListing = doc.querySelector('.archive-listing');
					if ( newListing ) {
						archiveListing.innerHTML = newListing.innerHTML;
					}
					archiveListing.removeAttribute('aria-busy');
					archiveListing.style.opacity = '';
					history.pushState(null, '', url);
				})
				.catch(function() {
					archiveListing.removeAttribute('aria-busy');
					archiveListing.style.opacity = '';
				});
		};

		const clearFiltersBtn = archiveFiltersForm.querySelector('.clear-filters');

		const updateClearButton = function() {
			if ( !clearFiltersBtn ) return;
			const hasSelection = [...archiveFiltersForm.querySelectorAll('.auto-submit-filter')]
				.some(function(select) { return select.value !== ''; });
			clearFiltersBtn.classList.toggle('clear-filters--hidden', !hasSelection);
		};

		archiveFiltersForm.addEventListener('change', function(event) {
			if ( event.target.classList.contains('auto-submit-filter') ) {
				updateClearButton();
				fetchFilteredResults();
			}
		});

		if ( clearFiltersBtn ) {
			clearFiltersBtn.addEventListener('click', function(event) {
				event.preventDefault();
				archiveFiltersForm.querySelectorAll('.auto-submit-filter').forEach(function(select) {
					select.value = '';
				});
				updateClearButton();
				fetchFilteredResults();
			});
		}
	}

	// Scroll to a URL hash target once its content has finished rendering.
	// Needed for anchors that point into async-rendered content (e.g. a
	// CiviCRM Afform), since the browser's native scroll-to-hash on page
	// load fires before that content exists or before the page settles
	// into its final height.
	if ( window.location.hash ) {
		const targetId = decodeURIComponent( window.location.hash.slice(1) );
		const hashTarget = document.getElementById( targetId );

		if ( hashTarget ) {
			let settleTimer;
			let attempts = 0;
			const maxAttempts = 25; // ~5s of settling at 200ms apart

			const scrollToHashTarget = function() {
				hashTarget.scrollIntoView({ behavior: 'auto', block: 'start' });
			};

			scrollToHashTarget();

			const observer = new MutationObserver(function() {
				clearTimeout( settleTimer );
				settleTimer = setTimeout( function() {
					scrollToHashTarget();
					attempts++;
					if ( attempts >= maxAttempts ) {
						observer.disconnect();
					}
				}, 200 );
			});

			observer.observe( hashTarget, { childList: true, subtree: true } );

			// Stop watching after 6s regardless, so this can't run forever.
			setTimeout( function() {
				observer.disconnect();
			}, 6000 );
		}
	}

})();
