/*const video = document.getElementById("video");
        const canvas = document.getElementById("canvas");
        const imgHasil = document.getElementById("img-hasil");
        const btnCapture = document.getElementById("btn-capture");

        // Akses kamera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                alert("Kamera tidak dapat diakses: " + err);
            });

        // Ambil gambar saat tombol diklik
        btnCapture.addEventListener("click", () => {
            const context = canvas.getContext("2d");
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageDataURL = canvas.toDataURL("image/png");
            imgHasil.src = imageDataURL;
        });
*/