function tableToCSV() {
  let data = [];
  let rows = document.querySelectorAll("table tr");
  for (let i = 0; i < rows.length; i++) {
    let row = [], cols = rows[i].querySelectorAll("td, th");
    for (let j = 0; j < cols.length; j++) {
      let col = cols[j];
      if (col.querySelector("input")) {
        row.push(col.querySelector("input").value);
      } else {
        row.push(col.innerText);
      }
    }
    data.push(row.join(","));
  }
  let csvContent = data.join("\n");
  let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  let link = document.createElement("a");
  let url = URL.createObjectURL(blob);
  link.setAttribute("href", url);
  link.setAttribute("download", "table.csv");
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
