<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sitio web para el agendamiento de citas con estilistas en Colombia">
    <title>Cita Perruna</title>
    <link rel="icon" type="image/webp" href="/public/build/img/favicon.webp">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">

    <!-- Hotjar Code -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:3838181,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen">
            
        </div>
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>
    
    <?php
     echo $script ?? ''; 
    ?>
            
</body>
</html>