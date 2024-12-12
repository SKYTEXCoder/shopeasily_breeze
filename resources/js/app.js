import './bootstrap';
import 'preline';
import 'flowbite';
import './components/navbar-category-dropdown';
import { initFlowbite } from 'flowbite';
import { initializeCategorySearchBoxDropdown } from './components/navbar-category-dropdown';

document.addEventListener("livewire:navigated", () => {
    window.HSStaticMethods.autoInit();
    initFlowbite();
    initializeCategorySearchBoxDropdown();
});
