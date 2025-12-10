(function () {
    'use strict';

    // =============================================================================
    // UTILS
    // =============================================================================
    const select = (el, all = false) => {
        el = el.trim();
        if (all) {
            return [...document.querySelectorAll(el)];
        } else {
            return document.querySelector(el);
        }
    };

    const on = (type, el, listener, all = false) => {
        let selectEl = select(el, all);
        if (selectEl) {
            if (all) {
                selectEl.forEach(e => e.addEventListener(type, listener));
            } else {
                selectEl.addEventListener(type, listener);
            }
        }
    };

    // =============================================================================
    // FEATURED POSTS CAROUSEL
    // =============================================================================
    const carousel = {
        currentSlide: 0,
        slides: [],
        dots: [],
        totalSlides: 0,
        autoplayInterval: null,
        autoplayDelay: 5000,

        init: function () {
            this.slides = select('.carousel-slide', true);
            this.dots = select('.carousel-dot', true);
            this.totalSlides = this.slides.length;

            if (this.totalSlides === 0) return;

            // Initialize ARIA and State
            this.slides.forEach((slide, i) => {
                if (!slide.getAttribute('id')) slide.setAttribute('id', 'slide-' + (i + 1));

                if (!slide.classList.contains('active')) {
                    slide.setAttribute('aria-hidden', 'true');
                    slide.setAttribute('tabindex', '-1');
                } else {
                    this.currentSlide = i;
                    slide.setAttribute('aria-hidden', 'false');
                    slide.setAttribute('tabindex', '0');
                }
            });

            // Initialize Dots
            this.dots.forEach((dot, i) => {
                dot.setAttribute('role', 'tab');
                dot.setAttribute('aria-controls', 'slide-' + (i + 1));
                dot.setAttribute('aria-pressed', i === this.currentSlide ? 'true' : 'false');

                if (i === this.currentSlide) dot.classList.add('active');
                else dot.classList.remove('active');

                dot.addEventListener('click', () => this.goToSlide(i));
            });

            // Navigation Arrows
            on('click', '.carousel-prev', () => this.prevSlide());
            on('click', '.carousel-next', () => this.nextSlide());

            // Keyboard Navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.prevSlide();
                if (e.key === 'ArrowRight') this.nextSlide();
                if (e.key === 'Home') this.goToSlide(0);
                if (e.key === 'End') this.goToSlide(this.totalSlides - 1);
            });

            // Swipe Support
            this.addSwipeSupport();

            // Autoplay
            if (this.totalSlides > 1) this.startAutoplay();

            // Pause on Hover
            const wrapper = select('.carousel-wrapper');
            if (wrapper) {
                wrapper.addEventListener('mouseenter', () => this.stopAutoplay());
                wrapper.addEventListener('mouseleave', () => this.startAutoplay());
            }
        },

        goToSlide: function (index) {
            if (index < 0 || index >= this.totalSlides) return;

            // Deactivate current
            const currentSlideEl = this.slides[this.currentSlide];
            const currentDotEl = this.dots[this.currentSlide];

            currentSlideEl.classList.remove('active');
            currentSlideEl.setAttribute('aria-hidden', 'true');
            currentSlideEl.setAttribute('tabindex', '-1');

            currentDotEl.classList.remove('active');
            currentDotEl.setAttribute('aria-pressed', 'false');

            // Update index
            this.currentSlide = index;

            // Activate new
            const newSlideEl = this.slides[this.currentSlide];
            const newDotEl = this.dots[this.currentSlide];

            newSlideEl.classList.add('active');
            newSlideEl.setAttribute('aria-hidden', 'false');
            newSlideEl.setAttribute('tabindex', '0');

            newDotEl.classList.add('active');
            newDotEl.setAttribute('aria-pressed', 'true');

            this.resetAutoplay();
        },

        nextSlide: function () {
            const nextIndex = (this.currentSlide + 1) % this.totalSlides;
            this.goToSlide(nextIndex);
        },

        prevSlide: function () {
            const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            this.goToSlide(prevIndex);
        },

        startAutoplay: function () {
            this.stopAutoplay();
            this.autoplayInterval = setInterval(() => {
                this.nextSlide();
            }, this.autoplayDelay);
        },

        stopAutoplay: function () {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
                this.autoplayInterval = null;
            }
        },

        resetAutoplay: function () {
            if (this.totalSlides > 1) {
                this.stopAutoplay();
                this.startAutoplay();
            }
        },

        addSwipeSupport: function () {
            let startX = 0;
            let endX = 0;
            const wrapper = select('.carousel-wrapper');

            if (!wrapper) return;

            wrapper.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });

            wrapper.addEventListener('touchmove', (e) => {
                endX = e.touches[0].clientX;
            });

            wrapper.addEventListener('touchend', () => {
                const diff = startX - endX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) this.nextSlide();
                    else this.prevSlide();
                }
            });
        }
    };

    // =============================================================================
    // LIVE SEARCH
    // =============================================================================
    const initLiveSearch = () => {
        const searchToggle = select('.search-toggle');
        const searchModal = select('#search-modal');
        const searchClose = select('.search-close');
        const searchInput = select('#search-input');
        const searchResults = select('#search-results');
        const modalOverlay = select('.search-modal-overlay');

        if (!searchToggle || !searchModal) return;

        let currentFocusIndex = -1;

        const openSearch = () => {
            searchModal.classList.add('active');
            setTimeout(() => searchInput.focus(), 100);
        };

        const closeSearch = () => {
            searchModal.classList.remove('active');
            searchInput.value = '';
            searchResults.innerHTML = '<div class="search-hint"><p>Start typing to search posts...</p></div>';
            currentFocusIndex = -1;
        };

        searchToggle.addEventListener('click', openSearch);
        if (searchClose) searchClose.addEventListener('click', closeSearch);
        if (modalOverlay) modalOverlay.addEventListener('click', closeSearch);

        // Keyboard Navigation for Search Results
        const navigateResults = (direction) => {
            const results = select('.search-result-item', true);
            if (!results || results.length === 0) return;

            // Remove previous focus
            if (currentFocusIndex >= 0 && currentFocusIndex < results.length) {
                results[currentFocusIndex].classList.remove('keyboard-focused');
            }

            // Calculate new index
            if (direction === 'down') {
                currentFocusIndex = (currentFocusIndex + 1) % results.length;
            } else if (direction === 'up') {
                currentFocusIndex = (currentFocusIndex - 1 + results.length) % results.length;
            }

            // Add focus to new item
            results[currentFocusIndex].classList.add('keyboard-focused');
            results[currentFocusIndex].focus();
        };

        // Live Search Logic
        let debounceTimer;
        if (searchInput) {
            // Keyboard navigation for search input
            searchInput.addEventListener('keydown', (e) => {
                const results = select('.search-result-item', true);

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (results && results.length > 0) {
                        navigateResults('down');
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (results && results.length > 0) {
                        navigateResults('up');
                    }
                } else if (e.key === 'Enter' || e.code === 'NumpadEnter') {
                    // If a result is focused, navigate to it
                    if (currentFocusIndex >= 0 && results && results[currentFocusIndex]) {
                        e.preventDefault();
                        results[currentFocusIndex].click();
                    } else {
                        // Otherwise submit the form
                        const form = searchInput.closest('form');
                        if (form) {
                            e.preventDefault();
                            form.submit();
                        }
                    }
                } else if (e.key === 'Escape') {
                    closeSearch();
                }
            });

            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                const query = e.target.value.trim();
                currentFocusIndex = -1; // Reset focus index on new input

                if (query.length < 2) {
                    searchResults.innerHTML = '<div class="search-hint"><p>Start typing to search posts...</p></div>';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    searchResults.innerHTML = '<div class="search-loading">Searching...</div>';

                    fetch(`${newsletter_ajax_obj.ajax_url}?action=live_search&s=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data.length > 0) {
                                const html = data.data.map(post => `
                                    <a href="${post.url}" class="search-result-item" tabindex="0">
                                        <div class="search-result-content">
                                            <span class="search-result-cat">${post.category}</span>
                                            <h4 class="search-result-title">${post.title}</h4>
                                            <span class="search-result-date">${post.date}</span>
                                        </div>
                                    </a>
                                `).join('');
                                searchResults.innerHTML = html;

                                // Add keyboard event listeners to results
                                const resultItems = select('.search-result-item', true);
                                resultItems.forEach((item) => {
                                    item.addEventListener('keydown', (e) => {
                                        if (e.key === 'ArrowDown') {
                                            e.preventDefault();
                                            navigateResults('down');
                                        } else if (e.key === 'ArrowUp') {
                                            e.preventDefault();
                                            navigateResults('up');
                                        } else if (e.key === 'Enter') {
                                            e.preventDefault();
                                            item.click();
                                        } else if (e.key === 'Escape') {
                                            closeSearch();
                                        }
                                    });
                                });
                            } else {
                                searchResults.innerHTML = '<div class="search-no-results">No posts found.</div>';
                            }
                        })
                        .catch(() => {
                            searchResults.innerHTML = '<div class="search-error">Error fetching results.</div>';
                        });
                }, 500);
            });
        }
    };

    // =============================================================================
    // NEWSLETTER SUBSCRIPTION
    // =============================================================================
    const initNewsletter = () => {
        const forms = select('.newsletter-form, .newsletter-form-footer', true);

        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const emailInput = form.querySelector('input[type="email"]');
                const nameInput = form.querySelector('input[name="name"]');
                const messageBox = form.querySelector('.newsletter-message') || form.parentElement.querySelector('.newsletter-message');

                // Check for various nonce field names
                const nonceInput = form.querySelector('input[name="newsletter_nonce"]') ||
                    form.querySelector('input[name="newsletter_nonce_field"]') ||
                    form.querySelector('input[name="footer_newsletter_nonce_field"]') ||
                    form.querySelector('input[name="about_newsletter_nonce_field"]');

                const nonce = nonceInput ? nonceInput.value : '';

                if (!emailInput || !emailInput.value) return;

                const formData = new FormData();
                formData.append('action', 'newsletter_subscribe');
                formData.append('email', emailInput.value);
                formData.append('nonce', nonce);

                // Add name if provided
                if (nameInput && nameInput.value) {
                    formData.append('name', nameInput.value);
                }

                // Disable button
                const btn = form.querySelector('button');
                const originalText = btn.innerText;
                btn.disabled = true;
                btn.innerText = 'Subscribing...';

                fetch(newsletter_ajax_obj.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        messageBox.classList.add('show');
                        if (data.success) {
                            messageBox.classList.remove('error');
                            messageBox.classList.add('success');
                            messageBox.textContent = data.data.message;
                            form.reset();
                        } else {
                            messageBox.classList.remove('success');
                            messageBox.classList.add('error');
                            messageBox.textContent = data.data.message;
                        }
                    })
                    .catch(() => {
                        messageBox.classList.add('show', 'error');
                        messageBox.textContent = 'Something went wrong. Try again.';
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerText = originalText;
                    });
            });
        });
    };

    // =============================================================================
    // MOBILE MENU TOGGLE
    // =============================================================================
    const initMobileMenu = () => {
        const mobileToggle = select('.mobile-menu-toggle');
        const navBox = select('.nav-box');

        if (!mobileToggle || !navBox) return;

        mobileToggle.addEventListener('click', () => {
            mobileToggle.classList.toggle('active');
            navBox.classList.toggle('mobile-active');
        });

        // Close menu when clicking on a nav link
        const navLinks = select('.nav-menu a', true);
        if (navLinks) {
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileToggle.classList.remove('active');
                    navBox.classList.remove('mobile-active');
                });
            });
        }
    };

    // =============================================================================
    // EXIT INTENT POPUP
    // =============================================================================
    const initExitIntentPopup = () => {
        const popup = select('#exit-intent-popup');
        const closeBtn = select('.exit-popup-close');
        const form = select('#exit-popup-newsletter-form');
        const messageBox = select('#exit-popup-message');

        if (!popup) return;

        let hasShown = false;
        let exitIntentTriggered = false;

        // Cookie Helpers
        const setCookie = (name, value, days) => {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
        };

        const getCookie = (name) => {
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                const [key, value] = cookie.trim().split('=');
                if (key === name) return value;
            }
            return null;
        };

        // Check if user has already seen popup
        if (getCookie('exit_popup_shown')) return;

        // Show Popup Function
        const showPopup = () => {
            if (hasShown || exitIntentTriggered) return;
            popup.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
            hasShown = true;
            exitIntentTriggered = true;
            setCookie('exit_popup_shown', 'true', 7); // Don't show again for 7 days
        };

        // Close Popup Function
        const closePopup = () => {
            popup.classList.remove('show');
            document.body.style.overflow = ''; // Restore scrolling
        };

        // Desktop: Exit Intent Detection (Mouse leaving viewport from top)
        const handleMouseOut = (e) => {
            // Only trigger on desktop (width > 768px)
            if (window.innerWidth <= 768) return;

            // Check if mouse is leaving from the top of the viewport
            if (e.clientY <= 0 && !hasShown) {
                setTimeout(showPopup, 300); // Small delay for better UX
                // Remove event listener after first trigger
                document.removeEventListener('mouseout', handleMouseOut);
            }
        };

        document.addEventListener('mouseout', handleMouseOut);

        // Mobile: Time-based Trigger
        // Time Condition: 30 seconds
        setTimeout(() => {
            // Only trigger on mobile (width <= 768px)
            if (window.innerWidth <= 768 && !hasShown) {
                showPopup();
            }
        }, 30000);

        // Close button click
        if (closeBtn) {
            closeBtn.addEventListener('click', closePopup);
        }

        // Close on overlay click
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                closePopup();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && popup.classList.contains('show')) {
                closePopup();
            }
        });

        // Form Submission
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                const emailInput = form.querySelector('#exit-popup-email');
                const nameInput = form.querySelector('#exit-popup-name');
                const nonceInput = form.querySelector('input[name="exit_popup_nonce_field"]');
                const btn = form.querySelector('.exit-popup-button');

                if (!emailInput || !emailInput.value) return;

                const formData = new FormData();
                formData.append('action', 'newsletter_subscribe');
                formData.append('email', emailInput.value);
                formData.append('nonce', nonceInput ? nonceInput.value : '');

                // Add name if provided
                if (nameInput && nameInput.value) {
                    formData.append('name', nameInput.value);
                }

                // Disable button
                const originalText = btn.innerText;
                btn.disabled = true;
                btn.innerText = 'Subscribing...';

                fetch(newsletter_ajax_obj.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        messageBox.classList.add('show');
                        if (data.success) {
                            messageBox.classList.remove('error');
                            messageBox.classList.add('success');
                            messageBox.textContent = data.data.message;
                            form.reset();

                            // Close popup after 2 seconds on success
                            setTimeout(closePopup, 2000);
                        } else {
                            messageBox.classList.remove('success');
                            messageBox.classList.add('error');
                            messageBox.textContent = data.data.message;
                        }
                    })
                    .catch(() => {
                        messageBox.classList.add('show', 'error');
                        messageBox.textContent = 'Something went wrong. Try again.';
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerText = originalText;
                    });
            });
        }
    };

    // =============================================================================
    // INIT
    // =============================================================================
    document.addEventListener('DOMContentLoaded', () => {
        carousel.init();
        initLiveSearch();
        initNewsletter();
        initMobileMenu();
    });

    // Initialize exit intent popup immediately
    initExitIntentPopup();

})();
