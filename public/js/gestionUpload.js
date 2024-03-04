//Récupération du bouton au chargement
const btnCharger = document.getElementById("chargeAffiche");
btnCharger.addEventListener("click", lanceParcourir, false);

//Récupération du champ d'upload
const fileUpload = document.getElementById("film_afficheFile");
fileUpload.addEventListener("change", afficheImage, false);

//Récupération champ Img de l'image
const imageAfficher = document.getElementById("afficheAffichee");

function lanceParcourir() {
    fileUpload.click();
}

function afficheImage() {
    const afficheCharger = this.files[0];
    const urlAfficheCharger = URL.createObjectURL(afficheCharger);
    imageAfficher.setAttribute("src", urlAfficheCharger);
}