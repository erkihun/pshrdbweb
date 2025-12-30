const NATIONAL_ID_MAX_DIGITS = 16;

const normalizeNationalIdValue = (value = '') => value.replace(/\D/g, '').slice(0, NATIONAL_ID_MAX_DIGITS);

const formatNationalIdForDisplay = (value = '') => {
    const digits = normalizeNationalIdValue(value);
    return digits.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
};

const initializeNationalIdField = (field) => {
    const ensureDigitsOnly = () => {
        const digits = normalizeNationalIdValue(field.value);

        if (field.value !== digits) {
            const cursor = field.selectionStart ?? digits.length;
            field.value = digits;

            if (typeof cursor === 'number') {
                const position = Math.min(cursor, digits.length);
                field.setSelectionRange(position, position);
            }
        }
    };

    const formatForDisplay = () => {
        field.value = formatNationalIdForDisplay(field.value);
    };

    field.addEventListener('input', ensureDigitsOnly);
    field.addEventListener('focus', () => {
        field.value = normalizeNationalIdValue(field.value);
    });
    field.addEventListener('blur', formatForDisplay);

    formatForDisplay();
};

document.addEventListener('DOMContentLoaded', () => {
    const fields = document.querySelectorAll('.national-id-field');

    fields.forEach((field) => initializeNationalIdField(field));
});
