const autoCompletejs = new autoComplete({
  data: {
    src: async () => {

      const query = document.querySelector("#autoComplete").value.trim();

      if (query) {

        const source = await fetch(`/location/get-autocompletion-list/?address=${query}`);
        const data = await source.json();

        return data;
      }
      return null;
    },
    key: ['address'],
    cache: false
  },

  threshold: 3,
  debounce: 300,
  searchEngine: "loose",
  resultsList: {
    render: true,

    container: source => {
      source.setAttribute("id", "autoComplete_list");
    },
    destination: document.querySelector("#autoComplete"),
    position: "afterend",
    element: "ul"
  },
  onSelection: feedback => {
    document.querySelector("#autoComplete").value = feedback.selection.value.address;

    document.querySelector('#createtaskform-longitude').value = feedback.selection.value.longitude;
    document.querySelector('#createtaskform-latitude').value = feedback.selection.value.latitude;
    document.querySelector('#createtaskform-city_id').value = feedback.selection.value.city_id;

    setTimeout(() => {
      document.querySelector("#autoComplete").focus();
    }, 0);
  }
});
