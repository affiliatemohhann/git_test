const initProductHeroGallery = (gallery) => {
	const slides = Array.from(gallery.querySelectorAll('[data-slide]'));
	const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
	const prevButton = gallery.querySelector('[data-gallery-prev]');
	const nextButton = gallery.querySelector('[data-gallery-next]');

	if (slides.length <= 1) {
		return;
	}

	let activeIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
	if (activeIndex < 0) {
		activeIndex = 0;
	}

	const updateGallery = (nextIndex) => {
		const normalizedIndex = (nextIndex + slides.length) % slides.length;
		activeIndex = normalizedIndex;

		slides.forEach((slide, index) => {
			const isActive = index === normalizedIndex;
			slide.classList.toggle('is-active', isActive);
			slide.hidden = !isActive;
		});

		thumbs.forEach((thumb, index) => {
			const isActive = index === normalizedIndex;
			thumb.classList.toggle('is-active', isActive);
			thumb.setAttribute('aria-selected', isActive ? 'true' : 'false');
		});
	};

	prevButton?.addEventListener('click', () => updateGallery(activeIndex - 1));
	nextButton?.addEventListener('click', () => updateGallery(activeIndex + 1));

	thumbs.forEach((thumb) => {
		thumb.addEventListener('click', () => {
			const nextIndex = Number.parseInt(thumb.dataset.slideIndex || '0', 10);
			updateGallery(nextIndex);
		});
	});

	updateGallery(activeIndex);
};

document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll('.wp-block-worthio-product-hero-block[data-gallery], .worthio-product-hero-block [data-gallery]')
		.forEach(initProductHeroGallery);
});
