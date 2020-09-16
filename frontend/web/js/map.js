
ymaps.ready(init);

function init() {
  var mapDom = document.getElementById('map');
  if (mapDom) {
    var myMap = new ymaps.Map("map", {
      center: [ mapDom.dataset.longitude, mapDom.dataset.latitude],
      zoom: mapDom.dataset.zoom
    });
  }
}
