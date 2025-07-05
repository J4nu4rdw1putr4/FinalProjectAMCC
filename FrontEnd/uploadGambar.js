document.getElementById("item-image").addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData();

    formData.append("seller", document.getElementById("seller-name").value);
    formData.append("email", document.getElementById("seller-email").value);
    formData.append("name", document.getElementById("item-name").value);
    formData.append("description", document.getElementById("item-desc").value);
    formData.append("price", document.getElementById("item-price").value);
    formData.append("image", document.getElementById("item-image").files[0]);

    try {
        const res = await fetch("http://localhost:3000/products", {
        method: "POST",
        body: formData,
    });

    const result = await res.json();
    if (res.ok) {
        document.getElementById("submit-msg").classList.remove("hidden");
        document.getElementById("sellForm").reset();
    } else {
        alert("Gagal: " + result.message);
    }
    } catch (err) {
        alert("Gagal menghubungi server.");
    }
});
