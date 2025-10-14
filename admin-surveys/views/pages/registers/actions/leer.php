<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista previa de PDF</title>
    <style>
        #pdf-preview {
            border: 1px solid #000;
            width: 100%;
            height: 500px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Vista previa de un PDF</h1>
    <input type="file" id="file-input" accept="application/pdf">
    <div id="pdf-preview"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type === "application/pdf") {
                const fileReader = new FileReader();

                fileReader.onload = function() {
                    const typedarray = new Uint8Array(this.result);
                    pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                        pdf.getPage(1).then(function(page) {
                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d');
                            const viewport = page.getViewport({ scale: 0.5 });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            page.render({ canvasContext: context, viewport: viewport }).promise.then(function() {
                                document.getElementById('pdf-preview').innerHTML = ''; // Clear any previous previews
                                document.getElementById('pdf-preview').appendChild(canvas);
                            });
                        });
                    });
                };

                fileReader.readAsArrayBuffer(file);
            } else {
                alert('Por favor selecciona un archivo PDF.');
            }
        });
    </script>
</body>
</html>
