const offerButton = document.querySelector('offerButton');
const buyButton = document.querySelector('.buy-Button');

function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.remove('hidden');
        toast.classList.add('opacity-100');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    document.addEventListener("DOMContentLoaded", () => {
    // Simulasi: tampilkan notifikasi saat halaman dibuka
    // Hapus bagian ini kalau mau tampil hanya setelah klik tombol
    // showToast("Barang berhasil ditambahkan ke keranjang!");
    showToast("Barang 'iPhone 13 Pro' berhasil ditambahkan!");
    });

offerButton.addEventListener('click', () => {
    showToast("Barang 'iPhone 13 Pro' berhasil ditambahkan!");
});