<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>TreeColor</title>
</head>
<body>
    <div class="container mt-5">

        <div class="row">
            
            <div class="col">

                <div class="card mx-auto" style="width: fit-content;">
                    <img src="data/burkina/burkina_card.png" class="card-img-top" alt="..." style="width: 300px;">
                    <div class="card-body">
                        <h5 class="card-title">Burkina Faso</h5>
                        <form action="visualisation.php" method="GET">
                            <input type="hidden" name="projet" value="burkina">
                            <button class="btn btn-primary">Voir le projet</button>
                        </form>
                    </div>
                </div>

            </div>  

            <div class="col">
            
                <div class="card mx-auto" style="width: fit-content;">
                    <img src="data/panama/panama_card.png" class="card-img-top" alt="..." style="width: 300px;">
                    <div class="card-body">
                        <h5 class="card-title">Panama</h5>
                        <form action="visualisation.php" method="GET">
                            <input type="hidden" name="projet" value="panama">
                            <button class="btn btn-primary">Voir le projet</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>