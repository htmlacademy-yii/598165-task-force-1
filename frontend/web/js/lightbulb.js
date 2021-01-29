(function() {
  const EVENT_STYLE = {
    'START_TASK': 'lightbulb__new-task--executor',
    "FINISH_TASK": 'lightbulb__new-task--close',
    "REJECT_TASK": 'lightbulb__new-task--message',
    'NEW_RESPONSE': 'lightbulb__new-task--message',
    'NEW_MESSAGE': 'lightbulb__new-task--message'
  };

  var lightbulb = document.getElementsByClassName('header__lightbulb')[0];
  const lightbulbPopup = document.querySelector('.lightbulb__pop-up');

  lightbulb.addEventListener('mouseover', function () {
    fetch('/events')
      .then(res => res.json())
      .then(data => updateEventsList(data));
  });

  function updateEventsList(data) {

    if (data.length) {

      const newEvents = data.map( event => {
        return `
        <p class="lightbulb__new-task ${EVENT_STYLE[event.type]}">
          ${event.message}
          <a href="/events/read/${event.id}" class="link-regular">${event.taskTitle}</a>
        </p>
      `;
      }).join(` `);

      lightbulbPopup.innerHTML = '<h3>Новые события</h3>' + newEvents;
    }
  }
})()
