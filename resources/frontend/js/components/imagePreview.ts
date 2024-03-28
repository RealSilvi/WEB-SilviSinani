import { Alpine, ThisTypedAlpineComponent } from '../livewire';

interface ImagePreviewOptions {
    defaultUrl?: string;
}

Alpine.data('imagePreview', function (options: ThisTypedAlpineComponent<ImagePreviewOptions> = {}) {
    return {
        imageUrl: options.defaultUrl ?? '',
        init() {},
        previewFile() {
            let file = this.$refs.imageFile.files[0];
            if (!file || file.type.indexOf('image/') === -1) {
                this.imageUrl = options.defaultUrl ?? '';
                return;
            }

            let reader = new FileReader();

            reader.onload = (e) => {
                this.imageUrl = e.target.result;
            };

            reader.readAsDataURL(file);
        },
    };
});
