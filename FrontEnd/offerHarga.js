// Load file modal
fetch("offerModal.html")
  .then((res) => res.text())
  .then((html) => {
    document.getElementById("modal-container").innerHTML = html;
  });

// Fungsi open/close & submit modal
function openOfferPopup(productName) {
  document.getElementById("product-name").value = productName;
  document.getElementById("offerModal").classList.remove("hidden");
}

function closeOfferPopup() {
  document.getElementById("offerModal").classList.add("hidden");
}

document.addEventListener("DOMContentLoaded", () => {
  // Delay attach event sampai modal ter-load
  setTimeout(() => {
    const form = document.getElementById("offerForm");
    if (form) {
      form.addEventListener("submit", async function (e) {
        e.preventDefault();
        const nama = document.getElementById("user-name").value;
        const email = document.getElementById("user-email").value;
        const hargaTawaran = document.getElementById("user-offer").value;
        const produk = document.getElementById("product-name").value;

        try {
          const res = await fetch("http://localhost:3000/offers", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nama, email, produk, hargaTawaran }),
          });

          const data = await res.json();
          if (res.ok) {
            closeOfferPopup();
            document.getElementById("offerForm").reset();
            if (typeof showToast === "function") {
              showToast("Penawaran berhasil dikirim!");
            }
          } else {
            alert("Gagal: " + data.message);
          }
        } catch (err) {
          alert("Gagal mengirim ke server");
        }
      });
    }
  }, 300); // beri waktu untuk load modal
});
