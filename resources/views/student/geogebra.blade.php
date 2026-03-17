@extends('layouts.app')

@section('title', 'GeoGebra Interactive Graphing')

@section('sidebar')
@include ('student.sidebar')
@endsection

@push('styles')
<style>
    .geogebra-shell {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr);
        gap: 1rem;
    }

    .solver-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        padding: 1rem;
    }

    .answer-box {
        border-radius: 10px;
        border: 1px solid #dbe3ef;
        background: #f8fafc;
        padding: 0.85rem;
        min-height: 72px;
    }

    .answer-box.success {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    .answer-box.error {
        border-color: #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }

    .quick-example {
        border: 1px dashed #cbd5e1;
        background: #fff;
        color: #334155;
        border-radius: 999px;
        padding: 0.25rem 0.65rem;
        font-size: 0.82rem;
        margin: 0 0.35rem 0.35rem 0;
    }

    @media (max-width: 991.98px) {
        .geogebra-shell {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-chart-line me-2"></i>GeoGebra Interactive Graphing</h4>
        <small class="text-muted">Graph equations and instantly see numeric answers when possible.</small>
    </div>
    <a href="https://www.geogebra.org/graphing" target="_blank" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-external-link-alt me-1"></i>Open Full GeoGebra
    </a>
</div>

<div class="geogebra-shell">
    <div class="card">
        <div class="card-body">
            <div id="geogebra-app" style="width: 100%; height: 620px; border: 1px solid #dbe3ef; border-radius: 8px;"></div>
        </div>
    </div>

    <div class="solver-card">
        <h6 class="fw-semibold mb-2"><i class="fas fa-square-root-variable me-2 text-primary"></i>Equation Assistant</h6>
        <p class="text-muted small mb-3">
            Type an expression or equation. The graph is added, and answer output appears below.
        </p>

        <form id="equationForm">
            <label for="equationInput" class="form-label">Equation / Expression</label>
            <input
                id="equationInput"
                type="text"
                class="form-control"
                placeholder="e.g. 2x + 5 = 15, y = 2x + 1, sqrt(81) + 4"
                autocomplete="off"
                required
            >
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-calculator me-1"></i>Solve / Plot
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="clearGraphBtn">
                    <i class="fas fa-rotate-left me-1"></i>Clear
                </button>
            </div>
        </form>

        <div class="mt-3">
            <small class="text-muted d-block mb-2">Quick Examples</small>
            <button type="button" class="quick-example" data-equation="2x + 5 = 15">2x + 5 = 15</button>
            <button type="button" class="quick-example" data-equation="y = x^2 - 4x + 3">y = x^2 - 4x + 3</button>
            <button type="button" class="quick-example" data-equation="sqrt(81) + 4">sqrt(81) + 4</button>
            <button type="button" class="quick-example" data-equation="sin(45)">sin(45)</button>
        </div>

        <div class="mt-3">
            <small class="text-muted d-block mb-2">Answer Output</small>
            <div id="answerBox" class="answer-box">
                GeoGebra is loading...
            </div>
        </div>

        <div class="mt-3 pt-3 border-top">
            <h6 class="fw-semibold mb-2">Quick Tips</h6>
            <ul class="small text-muted mb-0 ps-3">
                <li>Use `=` for equations like `2x + 5 = 15`.</li>
                <li>Use `y = ...` to graph directly.</li>
                <li>Use expressions like `sqrt(81) + 4` for numeric answers.</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.geogebra.org/apps/deployggb.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const equationForm = document.getElementById('equationForm');
        const equationInput = document.getElementById('equationInput');
        const answerBox = document.getElementById('answerBox');
        const clearGraphBtn = document.getElementById('clearGraphBtn');
        const quickExampleButtons = Array.from(document.querySelectorAll('.quick-example'));
        let ggbApi = null;
        let objectCounter = 0;

        function setAnswer(message, isError = false) {
            answerBox.textContent = message;
            answerBox.classList.remove('success', 'error');
            answerBox.classList.add(isError ? 'error' : 'success');
        }

        function hasVariable(input) {
            return /[a-zA-Z]/.test(input.replace(/sqrt|sin|cos|tan|log|ln/gi, ''));
        }

        function runEquation(rawInput) {
            const input = rawInput.trim();
            if (!input) {
                setAnswer('Please enter an equation or expression first.', true);
                return;
            }

            if (!ggbApi) {
                setAnswer('GeoGebra is still loading. Please try again in a moment.', true);
                return;
            }

            try {
                objectCounter += 1;
                const resultName = `ans${objectCounter}`;

                if (/^\s*y\s*=/.test(input) || /^[a-zA-Z]\w*\(x\)\s*=/.test(input)) {
                    ggbApi.evalCommand(input);
                    setAnswer('Graph added successfully. Equation plotted on the canvas.');
                    return;
                }

                if (input.includes('=') && hasVariable(input)) {
                    ggbApi.evalCommand(`${resultName} = Solve(${input})`);
                    const solution = ggbApi.getValueString(resultName, true);
                    setAnswer(solution && solution !== '?' ? `Answer: ${solution}` : 'Equation plotted. Check the algebra panel for details.');
                    return;
                }

                if (!input.includes('=') && hasVariable(input)) {
                    ggbApi.evalCommand(`${resultName}(x) = ${input}`);
                    setAnswer(`Graph added as ${resultName}(x).`);
                    return;
                }

                ggbApi.evalCommand(`${resultName} = ${input}`);
                const value = ggbApi.getValueString(resultName, true);
                setAnswer(value && value !== '?' ? `Answer: ${value}` : 'Expression plotted. Check algebra panel.');
            } catch (error) {
                setAnswer('Unable to solve this input. Please check the equation format and try again.', true);
            }
        }

        equationForm.addEventListener('submit', function (event) {
            event.preventDefault();
            runEquation(equationInput.value);
        });

        clearGraphBtn.addEventListener('click', function () {
            equationInput.value = '';
            if (ggbApi) {
                ggbApi.reset();
            }
            setAnswer('Graph cleared. Enter a new equation to continue.');
        });

        quickExampleButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const value = button.getAttribute('data-equation') || '';
                equationInput.value = value;
                runEquation(value);
            });
        });

        const parameters = {
            appName: 'graphing',
            width: document.getElementById('geogebra-app').offsetWidth,
            height: 620,
            showToolBar: true,
            showAlgebraInput: true,
            showMenuBar: true,
            enableShiftDragZoom: true,
            enableRightClick: true,
            appletOnLoad: function (api) {
                ggbApi = api;
                setAnswer('GeoGebra ready. Enter an equation to see the answer or graph.');
            }
        };

        const applet = new GGBApplet(parameters, true);
        applet.inject('geogebra-app');
    });
</script>
@endpush
