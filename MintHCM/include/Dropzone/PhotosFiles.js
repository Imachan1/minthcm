if (typeof PhotosFiles !== 'function') {
    class PhotosFiles extends Files{

        init () {
            super.init();
        }

        getAcceptedFiles() {
            return 'image/*';
        }
    }
    window.PhotosFiles = PhotosFiles;
}