import './bootstrap';

// Toggle individual editing forms
function toggleEdit(field) {
    const display = document.getElementById(`${field}Display`);
    const form = document.getElementById(`${field}Form`);

    display.classList.toggle('d-none');
    form.classList.toggle('d-none');
}

// Preview avatar before uploading
const avatarInput = document.getElementById('avatarInput');

avatarInput.addEventListener('change', function () {
    const file = this.files[0];

    if (!file) return;

    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!allowedTypes.includes(file.type)) {
        alert('Please select a JPG, PNG or WEBP image.');
        this.value = '';
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert('Avatar size must not exceed 2MB.');
        this.value = '';
        return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {
        const imageUrl = event.target.result;

        if (typeof imageUrl !== 'string') {
            alert('Unable to read the selected image.');
            avatarInput.value = '';
            return;
        }

        const nextAvatar = new Image();
        const preview = document.getElementById('avatarPreview');
        const fallback = document.getElementById('avatarFallback');

        nextAvatar.onload = function () {
            preview.src = imageUrl;
            preview.style.display = 'block';
            fallback.style.display = 'none';
            document.getElementById('avatarForm').submit();
        };

        nextAvatar.onerror = function () {
            alert('Unable to load the selected image.');
            avatarInput.value = '';
        };

        nextAvatar.src = imageUrl;
    };

    reader.onerror = function () {
        alert('Unable to read the selected image.');
        avatarInput.value = '';
    };

    reader.readAsDataURL(file);
});
