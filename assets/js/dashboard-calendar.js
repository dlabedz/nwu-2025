/**
 * Dashboard Events Calendar - AJAX Navigation
 *
 * @package NWU2025
 */

(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		initCalendarNavigation();
	});

	function initCalendarNavigation() {
		const calendarBlock = document.querySelector('.block-dashboard-events-calendar');

		if (!calendarBlock) {
			return;
		}

		calendarBlock.addEventListener('click', function(e) {
			const navLink = e.target.closest('.calendar-nav__prev, .calendar-nav__next');

			if (!navLink) {
				return;
			}

			e.preventDefault();

			const url = new URL(navLink.href);
			const month = url.searchParams.get('cal_month');
			const year = url.searchParams.get('cal_year');

			loadCalendar(month, year, calendarBlock);
		});
	}

	function loadCalendar(month, year, calendarBlock) {
		if (typeof nwu_calendar === 'undefined') {
			return;
		}

		calendarBlock.classList.add('is-loading');

		const formData = new FormData();
		formData.append('action', 'load_calendar_month');
		formData.append('nonce', nwu_calendar.nonce);
		formData.append('month', month);
		formData.append('year', year);

		fetch(nwu_calendar.ajax_url, {
			method: 'POST',
			credentials: 'same-origin',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if (data.success && data.data.html) {
				const tempDiv = document.createElement('div');
				tempDiv.innerHTML = data.data.html;
				const newCalendar = tempDiv.querySelector('.calendar-wrapper');

				const oldCalendar = calendarBlock.querySelector('.calendar-wrapper');
				if (oldCalendar && newCalendar) {
					oldCalendar.replaceWith(newCalendar);
				}

				const newUrl = new URL(window.location);
				newUrl.searchParams.set('cal_month', month);
				newUrl.searchParams.set('cal_year', year);
				window.history.pushState({}, '', newUrl);
			}

			calendarBlock.classList.remove('is-loading');
		})
		.catch(error => {
			calendarBlock.classList.remove('is-loading');
		});
	}
})();
