const autoCompletejs = new autoComplete({
  data: {
    src: async () => {

      const query = document.querySelector("#autoComplete").value.trim();

      if (query) {

        const source = await fetch(`/location/?address=${query}`);
        const data = await source.json();

        const result = data.response.GeoObjectCollection.featureMember.reduce((acc, it) => {
          acc.push(it.GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted);
          return acc;
        }, []);
        return result;
      }
      return null;
    },
    cache: false
  },

  threshold: 1,
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
    document.querySelector("#autoComplete").value = feedback.selection.value;
  }
});
