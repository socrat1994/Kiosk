function handleFileSelect(evt) {
  let file = evt.target.files[0];
  let reader = new FileReader();
  reader.onload = function(event) {
    let csvData = event.target.result;
    let data = csvData.split("\n");
    let table = document.createElement("table");
    for (let i = 0; i < data.length; i++) {
      let row = document.createElement("tr");
      let rowData = data[i].split(",");
      for (let j = 0; j < rowData.length; j++) {
        let cell = document.createElement(i === 0 ? "th" : "td");
        if (i === 0 || j === 0) {
          cell.innerText = rowData[j];
        } else {
          let input = document.createElement("input");
          input.type = "text";
          input.value = rowData[j];
          cell.appendChild(input);
        }
        row.appendChild(cell);
      }
      table.appendChild(row);
    }
    document.body.appendChild(table);
  };
  reader.readAsText(file);
}
