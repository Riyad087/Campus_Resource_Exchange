
document.addEventListener("DOMContentLoaded", function () {
    
    var filterForm = document.querySelector(".filter-form");
    if (filterForm) {
        filterForm.style.transition = "background 0.5s";
        filterForm.style.background = "#e3f2fd";
        setTimeout(function () {
            filterForm.style.background = "white";
        }, 800);
    }
});
