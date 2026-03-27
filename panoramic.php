<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panorama Viewer (Cylindrical)</title>

  <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>

  <style>
    html, body { height: 100%; margin: 0; }
    #panorama { width: 100%; height: 100vh; }
  </style>
</head>

<body>
  <div id="panorama"></div>

  <script>
    pannellum.viewer('panorama', {
      "type": "cylindrical",
      "panorama": "panorama.jpg",

      "autoLoad": true,
      "showZoomCtrl": true,
      "showFullscreenCtrl": true,

      // WAJIB untuk cylindrical:
      // Coba 180 dulu, kalau terlalu "sempit" naikkan ke 220-280.
      "haov": 220,

      // opsional
      "yaw": 0,
      "pitch": 0
    });
  </script>
</body>
</html>
