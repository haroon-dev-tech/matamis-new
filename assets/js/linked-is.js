document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('linked-is-form');
    if (!form) return;

    let formula = {};
    try {
        formula = JSON.parse(form.dataset.formula || '{}');
    } catch (e) {
        formula = {};
    }

    function parseVal(el) {
        return parseFloat(String(el.value).replace(/,/g, '')) || 0;
    }

    function formatMoney(n) {
        return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateTotals() {
        const headTotals = {};

        form.querySelectorAll('.linked-is-item-input').forEach(function (input) {
            const headId = input.dataset.headId;
            if (!headTotals[headId]) headTotals[headId] = 0;
            headTotals[headId] += parseVal(input);
        });

        Object.keys(headTotals).forEach(function (headId) {
            const el = form.querySelector('.linked-is-head-total[data-head-id="' + headId + '"]');
            if (el) el.textContent = formatMoney(headTotals[headId]);
        });

        let netProfit = 0;
        Object.keys(headTotals).forEach(function (headId) {
            const op = formula[headId];
            const total = headTotals[headId] || 0;
            if (op === 'add') netProfit += total;
            else if (op === 'subtract') netProfit -= total;
        });

        const netEl = document.getElementById('linked-is-net-profit');
        if (netEl) netEl.textContent = formatMoney(netProfit);
    }

    form.querySelectorAll('.linked-is-item-input').forEach(function (input) {
        input.addEventListener('input', updateTotals);
        input.addEventListener('blur', function () {
            const v = parseVal(this);
            this.value = v === 0 ? '' : formatMoney(v);
        });
        input.addEventListener('focus', function () {
            const v = parseVal(this);
            this.value = v === 0 ? '' : String(v);
        });
    });

    updateTotals();
});
