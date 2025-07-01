export function initializeCategorySearchBoxDropdown() {
    const dropdownButton = document.getElementById("dropdown-button");
    const dropdown = document.getElementById("dropdown");
    const dropdownArrowPath = document.getElementById("dropdown-arrow-path");
    const categoryButtonText = document.getElementById("category-button-text");
    const options = document.querySelectorAll('[data-js="category-option"]');
    const searchInputField = document.getElementById("search-dropdown");
    const categoryInputField = document.getElementById("productCategory");

    // Handle dropdown visibility and arrow rotation
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

    // Handle dropdown button click to toggle visibility
    dropdownButton.addEventListener("click", () => {
        dropdown.classList.toggle("hidden");
    });

    // Handle category option selection
    for (const option of options) {
        option.addEventListener("click", () => {
            const categoryName = option.innerText;
            const categoryValue = option.value;

            // Update button text using the specific span element
            categoryButtonText.textContent = categoryName;

            // Update placeholder and hidden input
            if (categoryName === "All Categories") {
                searchInputField.placeholder =
                    "What kind of products are you looking for?";
                categoryInputField.value = "0";
            } else {
                searchInputField.placeholder =
                    "Search for " +
                    categoryName.toLowerCase();
                categoryInputField.value = categoryValue;
            }

            // Hide dropdown
            dropdown.classList.add("hidden");
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", (event) => {
        if (
            !dropdownButton.contains(event.target) &&
            !dropdown.contains(event.target)
        ) {
            dropdown.classList.add("hidden");
        }
    });
}
