(function (){
  if (document.querySelector('.dropzone')) {
    Dropzone.autoDiscover = false;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var dropzone = new Dropzone(".dropzone", {
      url: window.location.href,
      maxFiles: 6,
      uploadMultiple: true,
      acceptedFiles: 'image/*',
      // previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы"></a>',
      params: {'_csrf-frontend': csrfToken},
      dictDefaultMessage: 'Выбрать фотографии'
    });
  }
})();
