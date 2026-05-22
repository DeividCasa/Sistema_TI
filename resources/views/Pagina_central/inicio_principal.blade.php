@extends('Plantilla/Plantilla')
@section('contenido')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Jersey Store</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#f4f7fb;
            font-family:'Inter', sans-serif;
            color:#0f172a;
        }

        .main-container{
            max-width:1150px;
            margin:auto;
            padding:40px 20px;
        }

        /* TITULOS */

        .title{
            font-size:32px;
            font-weight:700;
            margin-bottom:6px;
        }

        .subtitle{
            color:#64748b;
            font-size:14px;
            margin-bottom:30px;
        }

        /* FILTROS */

        .filters{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            margin-bottom:40px;
        }

        .filter-btn{
            border:none;
            padding:9px 18px;
            border-radius:50px;
            background:white;
            color:#334155;
            font-size:13px;
            font-weight:500;
            transition:0.3s;
            box-shadow:0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-btn:hover{
            background:#2563eb;
            color:white;
        }

        .filter-btn.active{
            background:#0f172a;
            color:white;
        }

        /* CARDS */

        .hoodie-card{
            width:100%;
            max-width:270px;
            background:white;
            border-radius:22px;
            overflow:hidden;
            transition:0.3s;
            box-shadow:0 4px 18px rgba(37,99,235,0.08);
            border:1px solid #e2e8f0;
        }

        .hoodie-card:hover{
            transform:translateY(-5px);
            box-shadow:0 12px 28px rgba(37,99,235,0.15);
        }

        .hoodie-image{
            height:190px;
            overflow:hidden;
            background:#e2e8f0;
        }

        .hoodie-image img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .card-body{
            padding:18px;
        }

        .tag{
            display:inline-block;
            padding:5px 12px;
            border-radius:30px;
            background:#dbeafe;
            color:#2563eb;
            font-size:11px;
            font-weight:600;
            margin-bottom:14px;
        }

        .hoodie-title{
            font-size:18px;
            font-weight:700;
            margin-bottom:5px;
        }

        .hoodie-desc{
            color:#64748b;
            font-size:13px;
            line-height:1.5;
            margin-bottom:15px;
        }

        .price{
            font-size:18px;
            font-weight:700;
            color:#2563eb;
        }

        .btn-design{
            width:100%;
            border:none;
            background:#2563eb;
            color:white;
            padding:10px;
            border-radius:12px;
            font-size:14px;
            font-weight:600;
            transition:0.3s;
            margin-top:15px;
        }

        .btn-design:hover{
            background:#1d4ed8;
        }

    </style>
</head>
<body>

<div class="main-container">

    <h1 class="title">Sport Jersey Collection</h1>

    <p class="subtitle">
        Explore premium sports jerseys with modern and athletic designs.
    </p>

    <!-- FILTROS -->

    <div class="filters">

        <button class="filter-btn active">Football</button>
        <button class="filter-btn">Basketball</button>
        <button class="filter-btn">Running</button>
        <button class="filter-btn">Training</button>
        <button class="filter-btn">Fitness</button>
        <button class="filter-btn">Men's</button>
        <button class="filter-btn">Women's</button>

    </div>

    <!-- CARDS -->

    <div class="row justify-content-center g-4">

        <!-- CARD 1 -->

        <div class="col-md-4 d-flex justify-content-center">

            <div class="hoodie-card">

                <div class="hoodie-image">
                    <img src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?q=80&w=1200&auto=format&fit=crop">
                </div>

                <div class="card-body">

                    <span class="tag">Football</span>

                    <h3 class="hoodie-title">Blue Elite Jersey</h3>

                    <p class="hoodie-desc">
                        Premium football jersey with breathable fabric.
                    </p>

                    <div class="price">$49</div>

                    <button class="btn-design">
                        Comprar
                    </button>

                </div>

            </div>

        </div>

        <!-- CARD 2 -->

        <div class="col-md-4 d-flex justify-content-center">

            <div class="hoodie-card">

                <div class="hoodie-image">
                    <img src="https://images.unsplash.com/photo-1523398002811-999ca8dec234?q=80&w=1200&auto=format&fit=crop">
                </div>

                <div class="card-body">

                    <span class="tag">Basketball</span>

                    <h3 class="hoodie-title">Urban Sport Jersey</h3>

                    <p class="hoodie-desc">
                        Modern basketball jersey inspired by streetwear.
                    </p>

                    <div class="price">$59</div>

                    <button class="btn-design">
                        Comprar
                    </button>

                </div>

            </div>

        </div>

        <!-- CARD 3 -->

        <div class="col-md-4 d-flex justify-content-center">

            <div class="hoodie-card">

                <div class="hoodie-image">
                    <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?q=80&w=1200&auto=format&fit=crop">
                </div>

                <div class="card-body">

                    <span class="tag">Training</span>

                    <h3 class="hoodie-title">Performance Tee</h3>

                    <p class="hoodie-desc">
                        Lightweight training jersey for maximum comfort.
                    </p>

                    <div class="price">$42</div>

                    <button class="btn-design">
                        Comprar
                    </button>

                </div>

            </div>

        </div>

        <!-- CARD 4 -->

        <div class="col-md-4 d-flex justify-content-center">

            <div class="hoodie-card">

                <div class="hoodie-image">
                    <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1200&auto=format&fit=crop">
                </div>

                <div class="card-body">

                    <span class="tag">Running</span>

                    <h3 class="hoodie-title">Runner Pro</h3>

                    <p class="hoodie-desc">
                        Flexible sports shirt designed for runners.
                    </p>

                    <div class="price">$39</div>

                    <button class="btn-design">
                        Comprar
                    </button>

                </div>

            </div>

        </div>

        <!-- CARD 5 -->

        <div class="col-md-4 d-flex justify-content-center">

            <div class="hoodie-card">

                <div class="hoodie-image">
                    <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=1200&auto=format&fit=crop">
                </div>

                <div class="card-body">

                    <span class="tag">Fitness</span>

                    <h3 class="hoodie-title">Fitness Jersey</h3>

                    <p class="hoodie-desc">
                        Comfortable gym jersey with premium texture.
                    </p>

                    <div class="price">$44</div>

                    <button class="btn-design">
                        Comprar
                    </button>

                </div>

            </div>

        </div>

        <!-- CARD 6 -->

        <div class="col-md-4 d-flex justify-content-center">

            <div class="hoodie-card">

                <div class="hoodie-image">
                    <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?q=80&w=1200&auto=format&fit=crop">
                </div>

                <div class="card-body">

                    <span class="tag">Premium</span>

                    <h3 class="hoodie-title">Champion Edition</h3>

                    <p class="hoodie-desc">
                        Exclusive premium sports jersey with modern style.
                    </p>

                    <div class="price">$79</div>

                    <button class="btn-design">
                        Comprar
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
@endsection