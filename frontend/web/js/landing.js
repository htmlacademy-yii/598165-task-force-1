'use strict';
const loginForm = document.querySelector(`#loginForm`);
const accountEnter  = document.querySelector(`.header__account-enter`);
const enterFormSection = document.querySelector(`.enter-form`);
const overlay = document.querySelector((`.overlay`));

accountEnter.addEventListener(`click`, (e) => {
  e.preventDefault();
  showForm();

  const modalClose = enterFormSection.querySelector(`.form-modal-close`);
  modalClose.addEventListener(`click`, () => closeForm());

  overlay.addEventListener(`click`, () => closeForm());
});

function showForm() {
  setFormStyle(`block`);
}

function closeForm() {
  setFormStyle(`none`);
  loginForm.reset();
}

function setFormStyle(displayStyle) {
  enterFormSection.style.display = displayStyle;
  overlay.style.display = displayStyle;
}


var $form = $('#loginForm');
$form.on('beforeSubmit', function () {
  var data = $form.serialize();


  $.ajax({
      type: 'POST',
      url: $form.attr('action'),
      data: data
  }).done(function (data) {

    if (data.success) {
      closeForm();
    } else if (data.validation) {
      $form.yiiActiveForm('updateMessages', data.validation, true);
    } else {
      // incorrect server response
    }
  })
    .fail(function () {
      // request failed
    });

  return false;
});

