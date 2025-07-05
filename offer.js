fetch("offerModal.html")
    .then((res) => res.text())
    .then((html) => {
    document.getElementById("modal-container").innerHTML = html;
});
