<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhishManager</title>
    <link rel="stylesheet" href="{!! url('css/reset.css') !!}">
    <link rel="stylesheet" href="{!! url('css/styles.css') !!}">
    <style type="text/css">
        @font-face {font-family: Roboto-Regular; src: url({!! url('langingpage/Roboto-Regular.ttf') !!});}
        body {
            background-color: {!! $options['bgcolor'] !!};
            color: {!! $options['fgcolor'] !!};
        }
        main {
            max-width: {!! $options['maxwidth'] !!}px;
            margin: auto;
        }
        h1 {font-family: Roboto-Regular;}
    </style>
</head>
<body>
    <main>
        {!! html_entity_decode($content) !!}
    </main>
</body>
</html>
