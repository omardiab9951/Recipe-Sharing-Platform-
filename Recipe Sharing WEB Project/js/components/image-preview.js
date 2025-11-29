export function initImagePreview(fileInputSelector = '.image-input', previewSelector = '.image-preview') {
const inputs = document.querySelectorAll(fileInputSelector);
inputs.forEach(inp => {
inp.addEventListener('change', (e) => {
const file = e.target.files && e.target.files[0];
const preview = inp.closest('label') ? inp.closest('label').querySelector(previewSelector) : document.querySelector(previewSelector);
if (!preview) return;
if (!file) { preview.src = ''; preview.classList.add('hidden'); return; }
const reader = new FileReader();
reader.onload = () => { preview.src = reader.result; preview.classList.remove('hidden'); };
reader.readAsDataURL(file);
});
});
}