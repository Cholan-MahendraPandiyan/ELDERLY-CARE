document.addEventListener("DOMContentLoaded", function () {
    const showLoadingButton = document.getElementById("showLoadingButton");
    const loadingPage = document.getElementById("loadingPage");
    const phonePage = document.getElementById("phonePage");
    const phoneNumberSpan = document.getElementById("phoneNumber");
    const copyButton = document.getElementById("copyButton");

    showLoadingButton.addEventListener("click", function () {
        showLoadingPage();
        setTimeout(showPhonePage, 5000);
    });

    function showLoadingPage() {
        const buttonPage = document.getElementById("buttonPage");
        buttonPage.style.display = "none";
        loadingPage.style.display = "block";
    }

    function showPhonePage() {
        loadingPage.style.display = "none";
        phonePage.style.display = "block";
        
        // Generate a random phone number (you can customize the range)
        const randomNumber = Math.floor(Math.random() * 10000000000);
        const formattedPhoneNumber = formatPhoneNumber(randomNumber);
        phoneNumberSpan.textContent = formattedPhoneNumber;
        
        copyButton.addEventListener("click", function () {
            // Copy the formatted phone number to the clipboard
            copyToClipboard(formattedPhoneNumber);
        });
    }

    function formatPhoneNumber(phoneNumber) {
        return phoneNumber.toString().replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    }

    function copyToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        copyButton.textContent = "Copied!";
    }
});
