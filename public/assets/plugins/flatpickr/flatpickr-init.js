document.addEventListener("DOMContentLoaded", function () {
    if (typeof flatpickr !== "undefined") {
        flatpickr("#due_date", {
            dateFormat: "Y-m-d",     // الشكل: 2025-10-12
            defaultDate: new Date(), // التاريخ الافتراضي اليوم
            locale: "en"             // الأرقام إنجليزية دائمًا
        });
    } else {
        console.error("Flatpickr not loaded.");
    }
});
