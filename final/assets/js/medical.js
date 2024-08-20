document.addEventListener('DOMContentLoaded', function () {
    const conditionList = document.getElementById('condition-list');
    const medicationList = document.getElementById('medication-list');
    const allergyList = document.getElementById('allergy-list');
    const surgeryList = document.getElementById('surgery-list');

    const addToList = (list, inputField) => {
        const itemName = inputField.value;
        if (itemName) {
            const listItem = document.createElement('li');
            listItem.textContent = itemName;
            list.appendChild(listItem);
            inputField.value = '';
        }
    };

    document.getElementById('add-condition').addEventListener('click', function () {
        addToList(conditionList, document.getElementById('medical-condition'));
    });

    document.getElementById('add-medication').addEventListener('click', function () {
        addToList(medicationList, document.getElementById('medication-name'));
    });

    document.getElementById('add-allergy').addEventListener('click', function () {
        addToList(allergyList, document.getElementById('allergy'));
    });

    document.getElementById('add-surgery').addEventListener('click', function () {
        addToList(surgeryList, document.getElementById('surgery'));
    });

    document.getElementById('medical-history-form').addEventListener('submit', function (event) {
        // Handle form submission, e.g., send data to the server via AJAX
        // Prevent the default form submission
        event.preventDefault();
        // Perform form submission logic here
    });
});
