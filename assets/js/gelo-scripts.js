document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name^="radio_attribute_"]');

    radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            const attribute = radio.name.replace('radio_', '');
            const value = radio.value;

            const select = document.querySelector('select[name="' + attribute + '"]');
            if (select) {
                select.value = value;
                select.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
});
