export function initializeCategorySearchBoxDropdown() {
    const dropdownButton = document.getElementById("dropdown-button");
    const dropdown = document.getElementById("dropdown");
    const dropdownArrowPath = document.getElementById("dropdown-arrow-path");
    const options = document.querySelectorAll('[data-js="category-option"]');
    const searchInputField = document.getElementById("search-dropdown");
    const categoryInputField = document.getElementById("productCategory");
    const observer = new MutationObserver((mutationsList, observer) => {
        mutationsList.forEach((mutation) => {
            if (
                mutation.type === "attributes" &&
                mutation.attributeName === "class"
            ) {
                if (dropdown.classList.contains("hidden")) {
                    dropdownArrowPath.setAttribute("d", "m1 1 4 4 4-4");
                } else {
                    dropdownArrowPath.setAttribute("d", "m1 5 4-4 4 4");
                }
            }
        });
    });
    observer.observe(dropdown, {
        attributes: true,
    });
    for (const option of options) {
        option.addEventListener("click", () => {
            dropdownButton.childNodes[0].textContent = option.innerText;
            if (option.innerHTML === "All Categories") {
                searchInputField.placeholder =
                    "What kind of products are you looking for?";
                categoryInputField.value = "0";
            } else {
                searchInputField.placeholder =
                    "Search for " + option.innerHTML.toLowerCase() + " products.....";
                categoryInputField.value = option.value; //TODO: change this
            }
            dropdown.classList.toggle("hidden");
        });
    }
}
