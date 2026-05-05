<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Majetkový Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Host+Grotesk:wght@400;500;600;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#936746',
                        secondary: '#254b34',
                        paper: '#fafafa',
                        pearl: '#e7e4e4',
                        cream: '#f6f1e8',
                        linen: '#e9e4de',
                        mist: '#ddd8d3',
                        sand: '#d8bf9b',
                        taupe: '#c7b299',
                        pebble: '#b9b0a8',
                        bronze: '#b68557',
                        success: '#59bf52',
                        warning: '#ebb081',
                        danger: '#042444',
                        error: '#C35252',
                        ink: '#4A4541',
                        surface: '#4F4742',
                        answer: '#FCDEC5',
                        peach: '#F0D3BC',
                        caramel: '#CE9B73',
                        walnut: '#AB784F',
                        chestnut: '#6E4525',
                        umber: '#78695C',
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html {
            font-size: 12px;
            width: 794px;
            max-width: 794px;
            overflow-x: hidden;
        }

        body {
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-family: 'Host Grotesk', sans-serif;
            color: #4A4541;
            width: 794px;
            max-width: 794px;
            overflow-x: hidden;
        }

        .font-lora {
            font-family: 'Lora', serif;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/page.php'; ?>
</body>

</html>