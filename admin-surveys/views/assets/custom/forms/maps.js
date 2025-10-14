// Initialize and add the map
let map;
let latitude;
let longitude;

document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#frmPqrs")) {
        let frmPqrs = document.querySelector("#frmPqrs");
        frmPqrs.addEventListener('submit', function (e) {
            e.preventDefault();
            let nombre = document.querySelector("#nombrePqr").value;
            let email = document.querySelector("#emailPqr").value;
            let direccion = document.querySelector("#direccionPqr").value;
            let mensaje = document.querySelector("#mensajePqr").value;
            if (nombre == "") {
                swal("", "El nombre es obligatorio", "error");
                return false;
            }
            if (!fntEmailValidate(email)) {
                swal("", "El email no es válido.", "error");
                return false;
            }
            if (direccion == "") {
                swal("", "La dirección es obligatoria", "error");
                return false;
            }
            if (mensaje == "") {
                swal("", "Por favor escribe el mensaje.", "error");
                return false;
            }
            divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Site/pqrs';
            let formData = new FormData(frmPqrs);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState != 4) return;
                if (request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        //swal("", objData.msg , "success");
                        latitude = objData.lat;
                        longitude = objData.lon;
                        initMap(latitude, longitude);
                        //document.querySelector("#frmPqrs").reset();
                    } else {
                        swal("", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }
        }, false);
    }

    initMap();
}, false);

// Initialize and add the map

async function initMap() {
  // The location of Uluru
  if(latitude === undefined || longitude === undefined)
    {
        latitude = 11.2084292;
        longitude = -74.2237886;
    }
  const position = { lat: latitude, lng: longitude }; 
  // Request needed libraries.
  //@ts-ignore
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  // The map, centered at Uluru
  map = new Map(document.getElementById("map"), {
    zoom: 16,
    center: position,
    mapId: "DEMO_MAP_ID",
  });

  // The marker, positioned at Uluru
  const marker = new AdvancedMarkerElement({
    map: map,
    position: position,
    title: "Santa Marta",
  });
}

//initMap();