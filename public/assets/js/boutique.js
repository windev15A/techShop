let carte = L.map("map").setView([48.852969, 2.349903], 13);
$(document).ready(function(){

    // Initialisation du champ cart
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(carte);
    L.marker([48.852969, 2.349903]).addTo(carte)
})

// Initialisation des variable

let inputSearch = document.getElementById("search");
let inputId = document.getElementById("id");
let champDistance = document.getElementById("champ-distance");
let valeurDistance = document.getElementById("valeur-distance");
let btnRecherche = document.querySelector("#formSearch");

/**
 * Personaliser la fenetre popUp de la carte
 *
 * @param {object} boutique
 * @returns string
 */
function displayPopup(boutique) {
    return `
            <div>
                <h4> ${boutique.nom}  </h4>
                <p>${boutique.adresse}</p>
                <div class="d-flex justify-content-between">
                   
                    <div>
                        <i class="bi bi-star" id="star${1}" data-note="${1}" style="font-size: 2rem; color: gold;" onclick="rating(this,${
        boutique.id
    })"></i>
                        <i class="bi bi-star" id="star${2}" data-note="${2}" style="font-size: 2rem; color: gold;" onclick="rating(this,${
        boutique.id
    })"></i>
                        <i class="bi bi-star" id="star${3}" data-note="${3}" style="font-size: 2rem; color: gold;" onclick="rating(this,${
        boutique.id
    })"></i>
                        <i class="bi bi-star" id="star${4}" data-note="${4}" style="font-size: 2rem; color: gold;" onclick="rating(this,${
        boutique.id
    })"></i>
                        <i class="bi bi-star" id="star${5}" data-note="${5}" style="font-size: 2rem; color: gold;" onclick="rating(this,${
        boutique.id
    })"></i>
                    </div>
                </div>
            <div>
            `;
}


//--------------------------------------------------------------------------------------------

/**
 *
 * Recherche des boutiques de proximité
 *
 * @return response Json
 *
 */
function clickRecherche() {
    if (inputSearch.value !== "") {
        fetch("/serachboutiques", {
            method: "post",
            body: JSON.stringify({
                id: inputId.value,
                distance: champDistance.value,
            }),
            headers: {
                "Content-Type": "application/json",
                
            },
        })
            .then(function (response) {
                response.json().then((data) => {
                    console.log(data.boutiques);
                    // Supprimer tous les marquers existant sur la carte
                    carte.eachLayer(function (layer) {
                        if (layer.options.name != "tiles")
                            carte.removeLayer(layer);
                    });

                    // Ajouter des marqueurs
                    addMarker(data.boutiques, champDistance.value);

                    // Afficher les boutiques de proximité
                    dispalyboutiques(data.boutiques, champDistance.value);

                });
            })
            .catch(function (error) {
                console.log(error);
            });
    }
}

//--------------------------------------------------------------------------------------------

/**
 * Ajouter des marqueurs sur la carte
 *
 *
 * @param {object} data
 * @param {int} distance
 */
function addMarker(data, distance) {
    //On trace le cercle de rayon "distance"
    let circle = L.circle([data[0].latitude, data[0].longitude], {
        color: "#4471C4",
        fillColor: "#4471C4",
        fillOpacity: 0.3,
        radius: distance * 1000,
    }).addTo(carte);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(carte);

    for (let index = 0; index < data.length; index++) {
        const element = data[index];
        let marker = L.marker([element.latitude, element.longitude]).addTo(
            carte
        );
        marker.bindPopup(displayPopup(element), {
            maxWidth: 500,
        });
    }
    // On centre et on zoome sur le cercle
    bounds = circle.getBounds();
    carte.fitBounds(bounds);
  
}

//--------------------------------------------------------------------------------------------

// Changer la distance
champDistance.addEventListener("change", function () {
    // On récupère la distance choisie
    distance = this.value;
    // On écrit cette valeur sur la page
    valeurDistance.innerText = distance + " km";
});

//--------------------------------------------------------------------------------------------

// Ajouter l evenement KEYUp au champ de saisie
inputSearch.addEventListener("keyup", function (event) {
    if (event.target.value.length > 0) {
        fetch("/boutique/search", {
            method: "post", 
            credentials: "same-origin",
            body: event.target.value,
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then(function (response) {
                response.json().then((data) => {
                    console.log(data);
                    $("select").attr("style", "display: flex !important");
                    //Afficher les boutiques dans une liste
                    displayListe(data);
                });
            })
            .catch(function (error) {
                console.log(error);
            });
    } else {
        $("select").attr("style", "display: none !important");
    }
});

//--------------------------------------------------------------------------------------------

/***
 *  Afficher les boutiques de proximité
 *
 * @data = les données reçu du serveur (fetch('/boutique/search'))
 *
 * @distance la valeur choisi par l utilisateur
 */
function dispalyboutiques(data, distance) {
    let htmlView = "";
    if (data.length == 0) {
        htmlView += `<p>Aucun boutique(s) dans un rayon de ${distance} km </p>`;
    } else {
        htmlView += `<p class="text-center"><strong>
            ${data.length}
            </strong> boutiques à proximité <strong>

            </strong> <br> dans un rayon de <strong>
            ${distance}
            km</strong>  </p>

            <ul style="overflow: auto;height: 200px;">`;
    }
    for (let i = 0; i < data.length; i++) {
        htmlView += `
            <li>
                <h4>${data[i].nom}</h4>
            </li>`;
    }
    htmlView += `
                    </ul>
                `;

    $("#boutiqueProxi").html(htmlView);
}

//--------------------------------------------------------------------------------------------

/**
 * Afficher les boutiques de la base de données
 *
 * @param {json} data données de la base de données
 */
function displayListe(data) {
    let htmlView = "";
    if (data.length == 0) {
        htmlView += `
                    <option >Aucune resultat trouvé </option>
                `;
    }
    for (let i = 0; i < data.length; i++) {
        htmlView += `<option value="${data[i].id}">${data[i].nom}</option>`;
    }
    $("select").html(htmlView);
}

//--------------------------------------------------------------------------------------------

/**
 * Obtenir les valeur saisie
 *
 * @param {int} id
 * @param {string} value
 */
function getValue(id, value) {
    inputSearch.value = value;
    inputId.value = id.value;
    $("select").attr("style", "display: none");
}

//--------------------------------------------------------------------------------------------

/**
 *
 * getboutiquesProximity Avoir les boutiques a proxmity de la postion de l'utilisateur
 *
 * @param {lonLat} array
 */
function getboutiquesProximity(lonLat) {
    fetch("/searchproximity", {
        method: "POST",
        body: JSON.stringify({
            lonLat: lonLat,
        }),
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": csrfToken,
        },
    })
        .then(function (response) {
            response.json().then((data) => {
                // Supprimer tous les marquers existant sur la carte
                carte.eachLayer(function (layer) {
                    if (layer.options.name != "tiles") carte.removeLayer(layer);
                });

                // Ajouter des marqueurs
                if (data.boutiques.length > 0) {
                    addMarker(data.boutiques, 0.25);
                }

                let costumIcon = {
                    iconUrl: "/images/ici.png",
                    iconSize: [60, 60],
                };
                let myIcon = L.icon(costumIcon);

                let iconOption = {
                    title: "Vous êtes ici",
                    draggable: false,
                    icon: myIcon,
                };
                var marqueur = L.marker(
                    [lonLat[0], lonLat[1]],
                    iconOption
                ).addTo(carte);
                marqueur.bindPopup(`
                        <p> Vous êtes ici </p>
                        <p> ${adresseComplet}</p>
                            `);
                carte.panTo([lonLat[0], lonLat[1]]);
                // Afficher les boutiques de proximité
                dispalyboutiques(data.boutiques, 0.25);
            });
        })
        .catch(function (error) {
            console.log(error);
        });
}

//--------------------------------------------------------------------------------------------

/***
 *
 * Ajouter l evenement click sur la carte
 *
 * @param {Event} e
 */

carte.addEventListener("click", function (e) {
    fetch(
        `http://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}&zoom=18&addressdetails=1`
        )
        .then((response) => {
            response.json().then(function (json) {
                // Ajouter un marqueur a notre carte
                var marqueur = L.marker([json.lat, json.lon]).addTo(carte);
                adresse = json.display_name.split(",");

                if(adresse.length == 8){
                    adresseComplet = `${adresse[0]}${adresse[1]}${adresse[7]}${adresse[2]}`;

                }else{
                    adresseComplet = `${adresse[0]}${adresse[1]}${adresse[7]}${adresse[2]}${adresse[8]}`;
                }
                // Ajouter un popup
                marqueur.bindPopup(`
                <p> Vous êtes ici </p>
                <p> ${adresseComplet}</p>
                    `);
                carte.panTo([json.lat, json.lon]);
                getboutiquesProximity([json.lat, json.lon]);
            });
        })
        .catch((error) => {
            console.log(error);
        });
    //getboutiquesProximity([e.latlng.lat, e.latlng.lng]);
});

