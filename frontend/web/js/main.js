var openModalLinks = document.getElementsByClassName("open-modal");
var closeModalLinks = document.getElementsByClassName("form-modal-close");
var overlay = document.getElementsByClassName("overlay")[0];

for (var i = 0; i < openModalLinks.length; i++) {
  var modalLink = openModalLinks[i];

  modalLink.addEventListener("click", function (event) {
    var modalId = event.currentTarget.getAttribute("data-for");

    var modal = document.getElementById(modalId);
    modal.setAttribute("style", "display: block");
    overlay.setAttribute("style", "display: block");

  });
}

function closeModal(event) {
  var modal = event.currentTarget.parentElement;

  modal.removeAttribute("style");
  overlay.removeAttribute("style");
}

for (var j = 0; j < closeModalLinks.length; j++) {
  var closeModalLink = closeModalLinks[j];

  closeModalLink.addEventListener("click", closeModal)
}

if (document.getElementById('close-modal')) {
  document.getElementById('close-modal').addEventListener("click", closeModal);
}

var starRating = document.getElementsByClassName("completion-form-star");

if (starRating.length) {
  starRating = starRating[0];

  starRating.addEventListener("click", function (event) {
    var stars = event.currentTarget.childNodes;
    var rating = 0;

    for (var i = 0; i < stars.length; i++) {
      var element = stars[i];

      if (element.nodeName === "SPAN") {
        element.className = "";
        rating++;
      }

      if (element === event.target) {
        break;
      }
    }

    var inputField = document.getElementById("rating");
    inputField.value = rating;
  });
}

var $finishForm = $('#finishForm');
$finishForm.on('beforeSubmit', function () {
  var data = $finishForm.serialize();
  $('.form-error').remove();

  $.ajax({
    type: 'POST',
    url: $finishForm.attr('action'),
    data: data
  }).done(function (data) {

    if (data.success) {
      closeModal();
    } else if (data.validationErrors) {
      $finishForm.before('<p class="form-error">Выберите выполнено задание или нет</p>')
    } else {
      // incorrect server response
    }
  })
    .fail(function () {
      // request failed
    });

  return false;
});

