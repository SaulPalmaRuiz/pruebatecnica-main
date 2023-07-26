// Esperar hasta que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
  // Obtener los elementos <select> del formulario por su ID
  let regionSelect = document.getElementById("region");
  let comunaSelect = document.getElementById("comuna");

  // Agregar un evento 'change' al elemento <select> de la región
  regionSelect.addEventListener("change", function () {
    // Obtener el valor seleccionado de la región
    let selectedRegion = regionSelect.value;
    
    // Realizar una llamada a un archivo PHP para obtener las comunas de la región seleccionada
    fetch("Controller/C_funcion.php?region=" + selectedRegion)
      .then((response) => response.text()) // Obtener el resultado de la llamada como texto
      .then((data) => {
        // Eliminar las opciones anteriores de la lista de selección de comunas
        comunaSelect.innerHTML = "";

        // El contenido del archivo PHP estará en la variable 'data', convertirlo a un objeto JavaScript
        data = JSON.parse(data);
        // Iterar sobre los datos recibidos y crear opciones para la lista de selección de comunas
        data.forEach((comuna) => {
          let option = document.createElement("option");
          option.value = comuna.id; // Valor de la opción será el ID de la comuna
          option.textContent = comuna.name; // Texto de la opción será el nombre de la comuna
          comunaSelect.appendChild(option); // Agregar la opción a la lista de selección de comunas
        });
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
});
