/*!
 * Client Logo Marquee by J - v1.0.3
 *
 * The JS never animates anything. Its only jobs are:
 *   1. clone the logo set enough times to cover the viewport,
 *   2. tell the CSS how many copies exist, so the keyframe can shift by exactly
 *      one set width and loop invisibly,
 *   3. pause the animation while the strip is off-screen.
 * No jQuery, no requestAnimationFrame loop, no scroll listeners.
 */
(function () {
	'use strict';

	var SELECTOR = '[data-clmj]';
	var reduce = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;

	function init(root) {
		if (!root) {
			return;
		}

		if (root.__clmj) {
			root.__clmj.destroy();
		}

		var viewport = root.querySelector('.clmj-viewport');
		var track = root.querySelector('.clmj-track');
		var base = track ? track.querySelector('.clmj-set[data-clmj-set]') : null;

		if (!viewport || !track || !base) {
			return;
		}

		var frame = 0;
		var io = null;
		var ro = null;
		var images = [];
		var dead = false;

		function removeClones() {
			var list = track.querySelectorAll('.clmj-set[data-clmj-clone]');

			for (var i = 0; i < list.length; i++) {
				if (list[i].parentNode) {
					list[i].parentNode.removeChild(list[i]);
				}
			}
		}

		function layout() {
			if (dead) {
				return;
			}

			if (reduce && reduce.matches) {
				removeClones();
				root.classList.remove('is-ready');

				return;
			}

			removeClones();

			var setWidth = base.getBoundingClientRect().width;
			var viewWidth = viewport.getBoundingClientRect().width;

			// Logos have not been measured yet (still decoding, or lazy-loaded and
			// not fetched). The observers below will call back when they have size.
			if (setWidth < 1) {
				return;
			}

			// One set to show, plus however many it takes to fill the gap it leaves
			// behind as it travels, plus one in reserve. Two is the floor.
			var copies = Math.max(2, Math.ceil(viewWidth / setWidth) + 1);
			var fragment = document.createDocumentFragment();

			for (var i = 1; i < copies; i++) {
				var copy = base.cloneNode(true);

				copy.removeAttribute('data-clmj-set');
				copy.setAttribute('data-clmj-clone', '');
				copy.setAttribute('aria-hidden', 'true');

				// Duplicated logos must not be reachable by keyboard or read out.
				var links = copy.querySelectorAll('a');

				for (var j = 0; j < links.length; j++) {
					links[j].setAttribute('tabindex', '-1');
				}

				fragment.appendChild(copy);
			}

			track.appendChild(fragment);
			root.style.setProperty('--clmj-sets', String(copies));
			root.classList.add('is-ready');
		}

		function schedule() {
			if (frame) {
				window.cancelAnimationFrame(frame);
			}

			frame = window.requestAnimationFrame(function () {
				frame = 0;
				layout();
			});
		}

		function watchImages() {
			var list = base.querySelectorAll('img');

			for (var i = 0; i < list.length; i++) {
				if (list[i].complete) {
					continue;
				}

				list[i].addEventListener('load', schedule);
				list[i].addEventListener('error', schedule);
				images.push(list[i]);
			}
		}

		function destroy() {
			dead = true;

			if (frame) {
				window.cancelAnimationFrame(frame);
				frame = 0;
			}

			if (io) {
				io.disconnect();
				io = null;
			}

			if (ro) {
				ro.disconnect();
				ro = null;
			}

			for (var i = 0; i < images.length; i++) {
				images[i].removeEventListener('load', schedule);
				images[i].removeEventListener('error', schedule);
			}

			images = [];

			window.removeEventListener('resize', schedule);

			if (reduce) {
				if (reduce.removeEventListener) {
					reduce.removeEventListener('change', schedule);
				} else if (reduce.removeListener) {
					reduce.removeListener(schedule);
				}
			}

			removeClones();
			root.classList.remove('is-ready');
			root.classList.remove('is-paused');
			root.style.removeProperty('--clmj-sets');
			root.__clmj = null;
		}

		// A ResizeObserver on the set covers both cases at once: the container
		// changing width, and the set growing as its logos finish loading.
		if (window.ResizeObserver) {
			ro = new window.ResizeObserver(schedule);
			ro.observe(base);
			ro.observe(viewport);
		} else {
			window.addEventListener('resize', schedule);
			watchImages();
		}

		if ('1' === root.getAttribute('data-pause-offscreen') && window.IntersectionObserver) {
			io = new window.IntersectionObserver(
				function (entries) {
					for (var i = 0; i < entries.length; i++) {
						if (entries[i].isIntersecting) {
							root.classList.remove('is-paused');
						} else {
							root.classList.add('is-paused');
						}
					}
				},
				{ rootMargin: '150px 0px' }
			);
			io.observe(root);
		}

		if (reduce) {
			if (reduce.addEventListener) {
				reduce.addEventListener('change', schedule);
			} else if (reduce.addListener) {
				reduce.addListener(schedule);
			}
		}

		root.__clmj = { destroy: destroy, refresh: schedule };

		layout();
	}

	function initAll(scope) {
		var host = scope && scope.querySelectorAll ? scope : document;
		var roots = host.querySelectorAll(SELECTOR);

		for (var i = 0; i < roots.length; i++) {
			init(roots[i]);
		}

		if (host !== document && host.matches && host.matches(SELECTOR)) {
			init(host);
		}
	}

	function hookElementor() {
		if (!window.elementorFrontend || !window.elementorFrontend.hooks) {
			return false;
		}

		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/client_logo_marquee_by_j.default',
			function (scope) {
				var el = scope && scope[0] ? scope[0] : scope;
				initAll(el);
			}
		);

		return true;
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			initAll(document);
		});
	} else {
		initAll(document);
	}

	if (!hookElementor() && window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', hookElementor);
	}

	// Escape hatch for themes or scripts that inject logos after load.
	window.clmjRefresh = initAll;
}());
