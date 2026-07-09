document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('observation-form');
    const btn = document.getElementById('ai-generate-btn');
    if (!form || !btn) return;

    const headInput = form.querySelector('[name="head"]');
    const detailsInput = form.querySelector('[name="details"]');
    const riskInput = form.querySelector('[name="risk"]');
    const recommendationsInput = form.querySelector('[name="recommendations"]');
    const errorEl = document.getElementById('ai-generate-error');
    const generateUrl = form.dataset.generateUrl;
    const companyName = form.dataset.companyName || '';

    if (!headInput || !detailsInput || !riskInput || !recommendationsInput || !generateUrl) {
        return;
    }

    function showError(message) {
        if (!errorEl) return;
        if (!message) {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
            return;
        }
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }

    function setLoading(loading) {
        btn.disabled = loading;
        btn.classList.toggle('opacity-60', loading);
        btn.classList.toggle('cursor-not-allowed', loading);
        const label = btn.querySelector('[data-label]');
        const spinner = btn.querySelector('[data-spinner]');
        if (label) label.classList.toggle('hidden', loading);
        if (spinner) spinner.classList.toggle('hidden', !loading);
    }

    btn.addEventListener('click', function () {
        showError('');

        const head = headInput.value.trim();
        const details = detailsInput.value.trim();

        if (!head) {
            showError('Please enter the observation head first.');
            headInput.focus();
            return;
        }

        if (!details) {
            showError('Please enter details before generating risk and recommendations.');
            detailsInput.focus();
            return;
        }

        const csrfInput = form.querySelector('input[name="csrf_token"]');
        if (!csrfInput) {
            showError('Security token missing. Please refresh the page.');
            return;
        }

        setLoading(true);

        const body = new FormData();
        body.append('csrf_token', csrfInput.value);
        body.append('head', head);
        body.append('details', details);
        body.append('company_name', companyName);

        fetch(generateUrl, {
            method: 'POST',
            body: body,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(function (res) {
                return res.json().then(function (data) {
                    return { ok: res.ok, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok || !result.data.success) {
                    throw new Error(result.data.error || 'AI generation failed.');
                }

                riskInput.value = result.data.risk || '';
                recommendationsInput.value = result.data.recommendations || '';
                riskInput.dispatchEvent(new Event('input', { bubbles: true }));
                recommendationsInput.dispatchEvent(new Event('input', { bubbles: true }));
            })
            .catch(function (err) {
                showError(err.message || 'AI generation failed. Please try again.');
            })
            .finally(function () {
                setLoading(false);
            });
    });
});
