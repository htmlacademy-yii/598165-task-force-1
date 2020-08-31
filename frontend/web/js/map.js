
ymaps.ready(init);

function init() {
  var mapDom = document.getElementById('map');
  var myMap = new ymaps.Map("map", {
    center: [ mapDom.dataset.longitude, mapDom.dataset.latitude],
    zoom: 16
  });
}
