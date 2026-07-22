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

	// Rate share table — toggle a class once the table's horizontal
	// scroll has reached its end, so the CSS edge-fade (signaling more
	// content to scroll) can hide itself, and wire up click-drag
	// scrolling. Scoped to the rate share Afform's own element so this
	// doesn't run on other CiviCRM SearchKit tables elsewhere on the
	// site. See _civicrm.scss.
	//
	// CiviCRM/Angular renders this table asynchronously after an API
	// call, well after this script runs, so querying for it once at
	// load time finds nothing. Watch for it instead and initialize as
	// soon as it actually exists.
	const initRateShareTable = function(wrapper) {
		if ( wrapper.dataset.rateShareInit ) return;

		const table = wrapper.querySelector('table.table');
		if ( !table ) return;

		wrapper.dataset.rateShareInit = 'true';

		const checkScrollEnd = function() {
			const atEnd = table.scrollLeft + table.clientWidth >= table.scrollWidth - 2;
			wrapper.classList.toggle('is-scrolled-end', atEnd);
		};

		table.addEventListener('scroll', checkScrollEnd);
		window.addEventListener('resize', checkScrollEnd);
		checkScrollEnd();

		// The table's native horizontal scrollbar sits at the bottom of its
		// own box — below every row — so on a long table it's off-screen
		// and effectively unreachable. Let users click-and-drag anywhere in
		// the table to pan it horizontally instead.
		let isDragging = false;
		let dragged = false;
		let startX = 0;
		let startScrollLeft = 0;

		table.addEventListener('mousedown', function(event) {
			// Leave real controls (checkboxes, links, buttons) alone —
			// only hijack mousedown on plain cell content.
			if ( event.target.closest('a, button, input, select, textarea, label') ) return;

			isDragging = true;
			dragged = false;
			startX = event.pageX;
			startScrollLeft = table.scrollLeft;
			table.classList.add('is-dragging');

			// Without this, the browser starts its own native text-selection
			// drag, which swallows the mousemove events below before they
			// ever reach us — the drag never appears to do anything.
			event.preventDefault();
		});

		window.addEventListener('mousemove', function(event) {
			if ( !isDragging ) return;
			const delta = event.pageX - startX;
			if ( Math.abs(delta) > 3 ) dragged = true;
			table.scrollLeft = startScrollLeft - delta;
		});

		window.addEventListener('mouseup', function() {
			if ( !isDragging ) return;
			isDragging = false;
			table.classList.remove('is-dragging');
		});

		// A drag ending on a link/button shouldn't also fire its click.
		table.addEventListener('click', function(event) {
			if ( dragged ) {
				event.preventDefault();
				event.stopPropagation();
				dragged = false;
			}
		}, true);
	};

	document.querySelectorAll('afsearch-rate-share-public-display .crm-search-display-table').forEach(initRateShareTable);

	new MutationObserver(function() {
		document.querySelectorAll('afsearch-rate-share-public-display .crm-search-display-table').forEach(initRateShareTable);
	}).observe(document.body, { childList: true, subtree: true });

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
