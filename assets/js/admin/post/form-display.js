document.addEventListener("DOMContentLoaded", function () {
    const statusSelect = document.getElementById("post-edit-status");
    const reasonField = document.getElementById("reason-field");
    const editorField = document.getElementById("editor-field");
    const initialStatus = statusSelect.value;

    const toggleFields = () => {
        const currentStatus = statusSelect.value;

        // Toggle Reason Field
        if (currentStatus !== "draft" && currentStatus !== initialStatus) {
            reasonField.style.display = "block";
        } else {
            reasonField.style.display = "none";
        }

        // Toggle Editor Field
        if (currentStatus && currentStatus !== "draft") {
            editorField.style.display = "block";
        } else {
            editorField.style.display = "none";
        }
    };

    statusSelect.addEventListener("change", toggleFields);

    // Initial check on page load
    toggleFields();

    // Thumbnail preview
    const thumbnailInput = document.getElementById('post-edit-thumbnail');
    const thumbnailPreview = document.getElementById('thumbnail-preview');

    thumbnailInput.addEventListener('change', function () {
        thumbnailPreview.innerHTML = '';
        const file = thumbnailInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                thumbnailPreview.innerHTML = `
                        <p>New Thumbnail:</p>
                        <img src="${e.target.result}" alt="New Thumbnail" class="new-image">
                    `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Banner preview
    const bannerInput = document.getElementById('post-edit-banner');
    const bannerPreview = document.getElementById('banner-preview');

    bannerInput.addEventListener('change', function () {
        bannerPreview.innerHTML = '';
        const file = bannerInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                bannerPreview.innerHTML = `
                        <p>New Banner:</p>
                        <img src="${e.target.result}" alt="New Banner" class="new-image">
                    `;
            };
            reader.readAsDataURL(file);
        }
    });
});
