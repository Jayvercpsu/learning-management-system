@extends('layouts.app')

@section('title', 'General Dashboard')

@section('sidebar')
    @if(auth()->user()->isAdmin())
        @include('admin.sidebar')
    @elseif(auth()->user()->isTeacher())
        @include('teacher.sidebar')
    @else
        @include('student.sidebar')
    @endif
@endsection

@push('styles')
<style>
    .general-hero {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        background: linear-gradient(135deg, #0b5ed7 0%, #0ea5e9 100%);
        color: #fff;
        box-shadow: var(--shadow-soft);
    }

    .general-hero h2 {
        margin: 0 0 0.45rem;
        font-weight: 700;
    }

    .general-hero p {
        margin: 0;
        color: rgba(255, 255, 255, 0.95);
    }

    .general-content-card {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        background: var(--surface);
        color: var(--text-main);
        box-shadow: var(--shadow-soft);
    }

    .general-content-card p {
        color: var(--text-muted);
        line-height: 1.7;
        margin-bottom: 1rem;
    }

    .general-content-card p:last-child {
        margin-bottom: 0;
    }

    .general-content-card ol {
        margin: 0;
        padding-left: 1.1rem;
        color: var(--text-muted);
        line-height: 1.7;
    }

    .general-content-card li + li {
        margin-top: 0.7rem;
    }

    .reference-line {
        overflow-wrap: anywhere;
        word-break: break-word;
    }
</style>
@endpush

@section('content')
    <div class="general-hero p-4 mb-4">
        <h2>General Dashboard</h2>
    </div>

    <div class="general-content-card p-3 p-lg-4 mb-4">
        <h5 class="mb-3"><strong>Abstract and Definitions</strong></h5>
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
    </div>

    <div class="general-content-card p-3 p-lg-4">
        <h5 class="mb-3"><strong>References</strong></h5>
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
    </div>
@endsection
