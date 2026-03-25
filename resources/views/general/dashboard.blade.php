<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Dashboard - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-main: #f3f8ff;
            --bg-alt: #e8f3ff;
            --text-main: #0f172a;
            --text-muted: #475569;
            --card-bg: #ffffff;
            --line: #dbe6f4;
            --primary: #0b5ed7;
            --primary-dark: #0a4db2;
            --shadow-soft: 0 14px 26px rgba(2, 6, 23, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--text-main);
            font-family: 'Poppins', sans-serif;
            background:
                radial-gradient(circle at 8% 8%, rgba(14, 165, 233, 0.18), transparent 35%),
                radial-gradient(circle at 95% 5%, rgba(56, 189, 248, 0.2), transparent 30%),
                linear-gradient(180deg, var(--bg-main), var(--bg-alt));
            min-height: 100vh;
        }

        .topbar {
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(6px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }

        .brand {
            font-weight: 800;
            letter-spacing: 0.02em;
            font-size: clamp(1.08rem, 3.2vw, 1.7rem);
            margin: 0;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            font-weight: 700;
            padding: 0.45rem 0.9rem;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
        }

        .hero {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: linear-gradient(135deg, #0b5ed7 0%, #0ea5e9 100%);
            color: #fff;
            box-shadow: var(--shadow-soft);
        }

        .hero h1 {
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 0.6rem;
        }

        .hero p {
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.95);
        }

        .content-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--card-bg);
            box-shadow: var(--shadow-soft);
            overflow: hidden;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .content-card p {
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        .content-card p:last-child {
            margin-bottom: 0;
        }

        .content-card ol {
            margin: 0;
            padding-left: 1.1rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .content-card li + li {
            margin-top: 0.7rem;
        }

        .reference-line {
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        @media (max-width: 576px) {
            .topbar-inner {
                gap: 0.5rem;
            }

            .hero h1 {
                font-size: 1.55rem;
            }

            .content-card {
                border-radius: 14px;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <h1 class="brand mb-0">
                <i class="fas fa-graduation-cap me-2 text-primary"></i>LMS General Dashboard
            </h1>
            <a href="{{ route('login') }}" class="btn btn-login btn-sm">
                <i class="fas fa-right-to-bracket"></i> Login
            </a>
        </div>
    </header>

    <main class="container py-4 py-lg-5">
        <section class="hero p-4 p-lg-5 mb-4">
            <h1>General Dashboard</h1>
            <p>
                This is the first page shown when opening the app. Click the Login button above to proceed to the login page.
            </p>
        </section>

        <section class="content-card p-3 p-lg-4 mb-4">
            <h2 class="section-title"><strong>Abstract and Definitions</strong></h2>
            <p>
                <strong>1. GeoGebra:</strong> According to Muslim, N. E. I., Zakaria, M. I., and Fang, C. Y. (2023),
                GeoGebra is a dynamic mathematics software that serves as a valuable tool for mathematics teachers,
                supporting the teaching of abstract concepts in measurement and geometry, relationships and algebra,
                as well as statistics and probability. Through its interactive features, GeoGebra enables teachers to
                create visual representations, conduct measurements, and explore geometric properties, fostering a
                deeper understanding of geometric concepts.
            </p>
            <p>
                <strong>2. Euclidean Geometry:</strong> Euclidean Geometry is the study of plane and solid figures on the
                basis of axioms and theorems employed by the Greek mathematician Euclid (c. 300 BCE). In its rough
                outline, Euclidean geometry is the plane and solid geometry commonly taught in secondary schools.
                Indeed, until the second half of the 19th century, when non-Euclidean geometries attracted the
                attention of mathematicians, geometry meant Euclidean geometry. It is the most typical expression of
                general mathematical thinking. German mathematician David Hilbert wrote his famous Foundations of
                Geometry (1899). The modern versions of Euclidean geometry is the theory of Euclidean (coordinate)
                spaces of multiple dimensions, where distance is measured by a suitable generalization of the
                Pythagorean theorem.
            </p>
            <p>
                <strong>3. Parallel Postulate and Pythagorean Theorem:</strong> The fifth axiom became known as the
                parallel postulate, since it provided a basis for the uniqueness of parallel lines. It also attracted
                great interest because it seemed less intuitive or self-evident than the others. In the 19th century,
                Carl Friedrich Gauss, Janos Bolyai, and Nikolay Lobachevsky all began to experiment with this postulate,
                eventually arriving at new, non-Euclidean, geometries. All five axioms provided the basis for numerous
                provable statements, or theorems, on which Euclid built his geometry. Pythagorean theorem is the
                well-known geometric theorem that the sum of the squares on the legs of a right triangle is equal to
                the square on the hypotenuse (the side opposite the right angle), or in familiar algebraic notation,
                a2 + b2 = c2. Although the theorem has long been associated with Greek mathematician philosopher
                Pythagoras (c. 570-500/490 BCE), it is actually far older.
            </p>
            <p>
                <strong>Geometry:</strong> Geometry is a branch of mathematics that deals with the shape of objects,
                their spatial relationships, and the properties of the space they occupy. It is one of the oldest
                branches of mathematics, with its origins stemming from practical problems such as surveying and
                construction. The term geometry comes from Greek words that mean Earth measurement. Geometry is not
                limited to the study of flat surfaces (plane geometry) and rigid three-dimensional objects (solid
                geometry). It can also represent abstract thoughts and images in geometric terms. Ancient peoples
                devised mathematical techniques for surveying land, constructing buildings, and measuring containers.
                The Greeks gathered and extended this practical knowledge and generalized the abstract subject now
                known as geometry.
            </p>
        </section>

        <section class="content-card p-3 p-lg-4">
            <h2 class="section-title"><strong>References</strong></h2>
            <ol>
                <li>
                    Muslim, N. E. I., Zakaria, M. I., and Fang, C. Y. (2023). A systematic review of GeoGebra in
                    mathematics education. International Journal of Academic Research in Progressive Education and
                    Development, 12(3), 1192-1203.
                </li>
                <li>
                    Artmann, B. (2025, February 18). Euclidean geometry. Encyclopedia Britannica.
                    <span class="reference-line">https://www.britannica.com/science/Euclidean-geometry</span>
                </li>
                <li>
                    Heilbron, J. (2025, September 12). geometry. Encyclopedia Britannica.
                    <span class="reference-line">https://www.britannica.com/science/geometry</span>
                </li>
            </ol>
        </section>
    </main>
</body>
</html>
