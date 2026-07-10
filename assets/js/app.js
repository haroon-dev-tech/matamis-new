document.addEventListener('DOMContentLoaded', function () {
    // Theme toggle
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    }

    // Password show/hide toggles
    document.querySelectorAll('input[type="password"]').forEach(function (input) {
        if (input.closest('.password-field')) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'password-field';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'password-toggle';
        button.setAttribute('aria-label', 'Show password');
        button.setAttribute('title', 'Show password');
        button.innerHTML =
            '<svg class="password-toggle-icon icon-show" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">' +
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>' +
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>' +
            '</svg>' +
            '<svg class="password-toggle-icon icon-hide" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">' +
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>' +
            '</svg>';

        button.addEventListener('click', function () {
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            button.classList.toggle('is-visible', !showing);
            button.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
            button.setAttribute('title', showing ? 'Show password' : 'Hide password');
            input.focus();
        });

        wrapper.appendChild(button);
    });

    // Sidebar toggle (mobile)
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    function openSidebar() {
        sidebar?.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
    }
    function closeSidebar() {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
    }

    sidebarToggle?.addEventListener('click', openSidebar);
    overlay?.addEventListener('click', closeSidebar);

    // SOMFP live calculation
    const somfpForm = document.getElementById('somfp-form');
    if (somfpForm) {
        const inputs = somfpForm.querySelectorAll('[data-line-item]');
        const groupMap = JSON.parse(somfpForm.dataset.groupMap || '{}');
        const assetGroups = (somfpForm.dataset.assetGroups || '').split(',');
        const elGroups = (somfpForm.dataset.elGroups || '').split(',');

        function parseVal(el) {
            const v = parseFloat(el.value.replace(/,/g, '')) || 0;
            return v;
        }

        function sumKeys(keys) {
            return keys.reduce((sum, key) => {
                const el = somfpForm.querySelector(`[data-line-item="${key}"]`);
                return sum + (el ? parseVal(el) : 0);
            }, 0);
        }

        function updateTotals() {
            let totalAssets = 0;
            let totalEL = 0;

            assetGroups.forEach(gk => {
                const keys = groupMap[gk] || [];
                const total = sumKeys(keys);
                const el = document.getElementById('group-' + gk);
                if (el) el.textContent = formatMoney(total);
                totalAssets += total;
            });

            elGroups.forEach(gk => {
                const keys = groupMap[gk] || [];
                const total = sumKeys(keys);
                const el = document.getElementById('group-' + gk);
                if (el) el.textContent = formatMoney(total);
                totalEL += total;
            });

            const xEl = document.getElementById('total-assets');
            const yEl = document.getElementById('total-el');
            const errEl = document.getElementById('error-xy');
            const errRow = document.getElementById('error-row');

            if (xEl) xEl.textContent = formatMoney(totalAssets);
            if (yEl) yEl.textContent = formatMoney(totalEL);

            const error = totalAssets - totalEL;
            if (errEl) errEl.textContent = formatMoney(error);
            if (errRow) {
                errRow.classList.toggle('balanced', Math.abs(error) < 0.01);
                errRow.classList.toggle('unbalanced', Math.abs(error) >= 0.01);
            }
        }

        function formatMoney(n) {
            return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        inputs.forEach(input => {
            input.addEventListener('input', updateTotals);
            input.addEventListener('blur', function () {
                const v = parseVal(this);
                if (v !== 0) this.value = formatMoney(v);
            });
            input.addEventListener('focus', function () {
                const v = parseVal(this);
                this.value = v === 0 ? '' : String(v);
            });
        });

        updateTotals();
    }

    // SOMCI live calculation
    const somciForm = document.getElementById('somci-form');
    if (somciForm) {
        const inputs = somciForm.querySelectorAll('[data-line-item]');

        function parseVal(el) {
            return parseFloat(el.value.replace(/,/g, '')) || 0;
        }

        function getVal(key) {
            const el = somciForm.querySelector(`[data-line-item="${key}"]`);
            return el ? parseVal(el) : 0;
        }

        function sumKeys(keys) {
            return keys.reduce((sum, key) => sum + getVal(key), 0);
        }

        function setCalc(key, value) {
            const el = document.getElementById('calc-' + key);
            if (el) el.textContent = formatMoney(value);
        }

        function updateSomciTotals() {
            const totalRevenue = sumKeys(['sales', 'e_commerce_online_sale', 'sales_discounts']);
            const totalDirectExpenses = sumKeys(['cost_of_sales']);
            const totalOperatingAdmin = sumKeys([
                'salary_wages', 'admin_expenses', 'legal_professional_consultancy',
                'office_misc_expenses', 'trade_license_legal_expenses', 'office_rent_expenses',
                'utility_expenses', 'printing_stationery', 'meals_refreshments_general',
                'staff_medical_expenses', 'travel_transportation_expenses', 'employees_visa_expenses',
                'advertisement_marketing_expenses', 'repair_maintenance_expenses', 'delivery_charges_expenses',
            ]);
            const totalOtherExpenses = sumKeys([
                'directors_remuneration', 'bank_charges', 'wps_charges', 'fines_mukhalfa',
            ]);

            const interestOnLoans = getVal('interest_on_loans');
            const depreciation = getVal('depreciation');
            const otherIncome = getVal('other_income');
            const corporateTax = getVal('corporate_tax');

            const grossProfitLoss = totalRevenue - totalDirectExpenses;
            const indirectExpenses = totalOperatingAdmin + totalOtherExpenses;
            const profitBeforeInterest = totalRevenue - totalDirectExpenses - totalOperatingAdmin - totalOtherExpenses;
            const profitAfterInterest = profitBeforeInterest - interestOnLoans;
            const profitAfterDep = profitAfterInterest - depreciation;
            const profitAfterOtherIncome = profitAfterDep - otherIncome;
            const profitLoss = profitAfterOtherIncome + corporateTax;

            setCalc('gross_profit_loss', grossProfitLoss);
            setCalc('indirect_expenses', indirectExpenses);
            setCalc('profit_before_interest', profitBeforeInterest);
            setCalc('profit_after_interest', profitAfterInterest);
            setCalc('profit_after_dep', profitAfterDep);
            setCalc('profit_after_other_income', profitAfterOtherIncome);
            setCalc('profit_loss', profitLoss);
        }

        function formatMoney(n) {
            return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        inputs.forEach(input => {
            input.addEventListener('input', updateSomciTotals);
            input.addEventListener('blur', function () {
                const v = parseVal(this);
                if (v !== 0) this.value = formatMoney(v);
            });
            input.addEventListener('focus', function () {
                const v = parseVal(this);
                this.value = v === 0 ? '' : String(v);
            });
        });

        updateSomciTotals();
    }

    // Dynamic branch rows for company form
    const addBranchBtn = document.getElementById('add-branch');
    const branchContainer = document.getElementById('branch-list');
    if (addBranchBtn && branchContainer) {
        let branchIndex = branchContainer.querySelectorAll('.branch-row').length;
        addBranchBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'branch-row flex flex-wrap items-end gap-3 rounded-lg border border-slate-200 p-4 dark:border-slate-700';
            row.innerHTML = `
                <div class="flex-1 min-w-[200px]">
                    <label class="mb-1 block text-xs font-medium text-slate-500">Branch Name</label>
                    <input type="text" name="branches[new_${branchIndex}][name]" class="input-field" placeholder="e.g. Dubai Branch" required>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="mb-1 block text-xs font-medium text-slate-500">Location</label>
                    <input type="text" name="branches[new_${branchIndex}][location]" class="input-field" placeholder="e.g. Dubai, UAE">
                </div>
                <div class="flex items-center gap-2 pb-2">
                    <input type="checkbox" name="branches[new_${branchIndex}][is_head_office]" value="1" id="ho-${branchIndex}" class="rounded border-slate-300 text-brand-600">
                    <label for="ho-${branchIndex}" class="text-sm">Head Office</label>
                </div>
                <button type="button" class="remove-branch rounded-lg p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-950" title="Remove">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            `;
            branchContainer.appendChild(row);
            branchIndex++;
            row.querySelector('.remove-branch').addEventListener('click', () => row.remove());
        });
        branchContainer.querySelectorAll('.remove-branch').forEach(btn => {
            btn.addEventListener('click', function () {
                const rows = branchContainer.querySelectorAll('.branch-row');
                if (rows.length > 1) this.closest('.branch-row').remove();
            });
        });
    }
});
