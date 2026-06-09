(() => {
	const labels = window.dreShareLabels || {};
	const copyLabel = labels.copy || 'Kopírovať odkaz';
	const copiedLabel = labels.copied || 'Skopírované';

	const closeOtherPanels = (currentPanel) => {
		document.querySelectorAll('.dre-share-panel').forEach((panel) => {
			if (panel !== currentPanel) {
				panel.hidden = true;
				const relatedToggle = panel.parentElement?.querySelector('.dre-share-toggle');
				if (relatedToggle) {
					relatedToggle.setAttribute('aria-expanded', 'false');
				}
			}
		});
	};

	const copyToClipboard = async (text) => {
		if (navigator.clipboard?.writeText) {
			await navigator.clipboard.writeText(text);
			return;
		}

		const textarea = document.createElement('textarea');
		textarea.value = text;
		textarea.style.position = 'fixed';
		textarea.style.left = '-9999px';
		document.body.appendChild(textarea);
		textarea.focus();
		textarea.select();
		document.execCommand('copy');
		document.body.removeChild(textarea);
	};

	document.addEventListener('click', async (event) => {
		const toggle = event.target.closest('.dre-share-toggle');
		const closeButton = event.target.closest('.dre-share-close');
		const copyButton = event.target.closest('.dre-share-copy');
		const clickInside = event.target.closest('.dre-share-wrapper');

		if (toggle) {
			const wrapper = toggle.closest('.dre-share-wrapper');
			const panel = wrapper?.querySelector('.dre-share-panel');

			if (!panel) {
				return;
			}

			const isOpening = panel.hidden;
			closeOtherPanels(panel);
			panel.hidden = !isOpening;
			toggle.setAttribute('aria-expanded', isOpening ? 'true' : 'false');
			return;
		}

		if (closeButton) {
			event.preventDefault();
			const panel = closeButton.closest('.dre-share-panel');
			if (!panel) {
				return;
			}

			panel.hidden = true;
			const relatedToggle = panel.parentElement?.querySelector('.dre-share-toggle');
			if (relatedToggle) {
				relatedToggle.setAttribute('aria-expanded', 'false');
			}
			return;
		}

		if (copyButton) {
			event.preventDefault();
			const url = copyButton.getAttribute('data-copy-url');
			const label = copyButton.querySelector('.dre-share-action__label');
			const defaultLabel = copyButton.getAttribute('data-default-label') || copyLabel;
			if (!url) {
				return;
			}

			try {
				await copyToClipboard(url);
				if (label) {
					label.textContent = copiedLabel;
				}
				window.setTimeout(() => {
					if (label) {
						label.textContent = defaultLabel;
					}
				}, 1800);
			} catch (error) {
				window.prompt('Skopírujte odkaz:', url);
			}

			return;
		}

		if (!clickInside) {
			closeOtherPanels(null);
		}
	});
})();

