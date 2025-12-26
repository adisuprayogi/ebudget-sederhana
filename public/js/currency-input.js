/**
 * Currency Input Formatter
 * Automatically formats currency inputs with thousand separators (Indonesian format)
 *
 * Usage:
 * 1. Add class "currency-input" to text input fields
 * 2. Add data-target="ID_OF_HIDDEN_INPUT" to specify where to store the raw value
 * 3. Include this script in your layout or view
 *
 * Example:
 * <input type="text" class="currency-input" data-target="amount_hidden" placeholder="0">
 * <input type="hidden" name="amount" id="amount_hidden">
 */

(function() {
    'use strict';

    // Format value to Indonesian currency format (1.000.000)
    function formatCurrency(value) {
        let cleanValue = value.replace(/[^0-9]/g, '');
        if (cleanValue === '') return '';
        let number = parseInt(cleanValue, 10);
        return number.toLocaleString('id-ID');
    }

    // Parse formatted value back to number
    function parseCurrency(formattedValue) {
        let cleanValue = formattedValue.replace(/[^0-9]/g, '');
        return cleanValue === '' ? 0 : parseInt(cleanValue, 10);
    }

    // Initialize currency inputs
    function initCurrencyInputs() {
        document.querySelectorAll('.currency-input').forEach(function(input) {
            const targetId = input.getAttribute('data-target');
            const targetInput = targetId ? document.getElementById(targetId) : null;

            // Set initial display value if target has value
            if (targetInput && targetInput.value && targetInput.value !== '0') {
                input.value = formatCurrency(targetInput.value);
            }

            // Handle input event
            input.addEventListener('input', function(e) {
                let value = e.target.value;
                let cursorPos = e.target.selectionStart;

                // Format the value
                let formatted = formatCurrency(value);
                e.target.value = formatted;

                // Update the target hidden input
                if (targetInput) {
                    targetInput.value = parseCurrency(formatted);
                }

                // Restore cursor position
                let lengthDiff = formatted.length - value.length;
                e.target.setSelectionRange(cursorPos + lengthDiff, cursorPos + lengthDiff);

                // Trigger custom event for other scripts
                input.dispatchEvent(new Event('currencyChange', { bubbles: true }));
            });

            // Handle blur event
            input.addEventListener('blur', function(e) {
                if (e.target.value === '') {
                    e.target.value = '';
                    if (targetInput) targetInput.value = 0;
                }
            });

            // Handle focus event - select all text
            input.addEventListener('focus', function(e) {
                e.target.select();
            });
        });
    }

    // Auto-initialize on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCurrencyInputs);
    } else {
        initCurrencyInputs();
    }

    // Expose functions globally for manual use
    window.CurrencyFormatter = {
        format: formatCurrency,
        parse: parseCurrency,
        init: initCurrencyInputs
    };
})();
