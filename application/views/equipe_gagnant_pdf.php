<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAGNANT</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        window.onload = function() {
            var element = document.getElementById('element-to-print');
            html2pdf(element, {
                margin: 0.5,
                filename: 'gagnant.pdf',
                image: { type: 'jpeg', quality: 2 },
                html2canvas: { scale: 1 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
            }).then(function() {
                window.location.href = "home";
            });
        };
    </script>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f2f2f2;
            color: #212529;
        }
        .card {
            background-color: #e5e5e5;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0,0,0,0.05);
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 970px;
            height: 600px;
            margin: 20px;
            position: relative;
            border: 10px solid #f2f2f2;
        }
        .card .winner-icon {
            font-size: 100px;
            color: #ffd400;
        }
        .card .points-total {
            font-size: 100px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #ffd400;
            width: 100px;
            height: 100px;
            border-radius: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card .certificate-title {
            font-size: 70px;
            font-weight: bold;
            font-family: Georgia, 'Times New Roman', Times, serif;
            margin-top: 50px;
            text-align: center;
            line-height: 0.8;
        }
        .card .certificate-subtitle {
            font-size: 25;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
        }
        .card img {
            max-width: 100%;
            margin-bottom: 20px;
        }
        .card .rang{
            margin-top: -30px;
            display: flex;
            /* justify-content: center; */
            align-items: center;
            svg{
                width: 150px;
                height: auto;
                margin-top: 50px;
            }

        }

        .card .info{
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 600px;
            color: white;
        }

        .card .descri{
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
            color: black;
            height: 50px;
            display: inline;
            justify-content: center;
            text-align: center;
            line-height: 0.8;

        }

        .card .logo{
            display: flex;
            align-items: center;
            width: 900px;
            justify-content: space-between;
            img{
                width: 100px;
                height: auto;
            }
        }

        .card .pic{
            background-image: url(<?=base_url('assets/images/pic.jpg')?>);
            image-rendering: auto;
            background-size: cover;
            width: 900px;
            height: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 10px;

        }
    </style>
</head>
<body>
<div  id="element-to-print">
    <?php foreach($equipes_gagnant as $data): ?>
        <div class="card">
            <div class="logo">
                <img src="<?=base_url('assets/images/house.png')?>" alt="logo1">
                    <i class="fa fa-trophy winner-icon" aria-hidden="true"></i>
                <img src="<?=base_url('assets/images/logo2.png')?>" alt="logo2">
            </div>

            <div class="rang">
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Flat"> <g id="Color"> <polygon fill="#212529" points="45 17 32 25 19 17 19 3 45 3 45 17"></polygon> <polygon fill="#dd051d" points="40 3 40 20.08 32 25 24 20.08 24 3 40 3"></polygon> <path d="M32,25l6.49-4a21.36,21.36,0,0,0-13,0Z" fill="#a60416"></path> <circle cx="32" cy="41.5" fill="#fccd1d" r="19.5"></circle> <circle cx="32" cy="41.5" fill="#f9a215" r="14.5"></circle> <path d="M34.13,43.63V33H29.88a3.19,3.19,0,0,1-3.19,3.19H25.63v4.25h4.25v3.19a2.13,2.13,0,0,1-2.13,2.12H25.63V50H38.38V45.75H36.25A2.12,2.12,0,0,1,34.13,43.63Z" fill="#fccd1d"></path> </g> </g> </g></svg>
                <p class="certificate-title">RUNNING <br> CHAMPION</p>
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Flat"> <g id="Color"> <polygon fill="#212529" points="45 17 32 25 19 17 19 3 45 3 45 17"></polygon> <polygon fill="#dd051d" points="40 3 40 20.08 32 25 24 20.08 24 3 40 3"></polygon> <path d="M32,25l6.49-4a21.36,21.36,0,0,0-13,0Z" fill="#a60416"></path> <circle cx="32" cy="41.5" fill="#fccd1d" r="19.5"></circle> <circle cx="32" cy="41.5" fill="#f9a215" r="14.5"></circle> <path d="M34.13,43.63V33H29.88a3.19,3.19,0,0,1-3.19,3.19H25.63v4.25h4.25v3.19a2.13,2.13,0,0,1-2.13,2.12H25.63V50H38.38V45.75H36.25A2.12,2.12,0,0,1,34.13,43.63Z" fill="#fccd1d"></path> </g> </g> </g></svg>
            </div>

            <p class="descri">
                TRACK & FIELD CERTIFICATE <br>
                 <small> this is to certity that</small>
            </p>
            <div class="pic">
                <p class="points-total"><?php echo $data->points_totauxpoints_totaux; ?></p>
                <p class="certificate-subtitle">EQUIPE <?php echo $data->nom_equipe; ?> WINNER</p>
                <div class="info">
                    <p>Signature</p>
                    <p>Date</p>
                </div>
            </div>

            
        </div>


    <?php endforeach; ?>

    </div>
   
</body>
</html>


