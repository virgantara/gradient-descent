
<style>
.calc-animation {
    margin-top: 18px;
    padding: 18px;
    border: 1px solid #dbeafe;
    border-radius: 14px;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

.calc-stage {
    min-height: 250px;
    padding: 18px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
}

.calc-step-title {
    margin-bottom: 8px;
    color: #0f172a;
    font-size: 20px;
    font-weight: 800;
}

.calc-step-text {
    color: #475569;
    line-height: 1.65;
}

.calc-equation {
    margin-top: 14px;
    padding: 14px 16px;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    background: #eff6ff;
    color: #1d4ed8;
    font-family: "Courier New", monospace;
    font-size: 15px;
    line-height: 1.75;
    overflow-x: auto;
}

.calc-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-top: 16px;
}

.calc-box {
    min-width: 0;
    padding: 14px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    transition: transform .2s ease, border-color .2s ease, background .2s ease;
}

.calc-box.active {
    transform: translateY(-2px);
    border-color: #93c5fd;
    background: #eff6ff;
}

.calc-box.success {
    border-color: #86efac;
    background: #f0fdf4;
}

.calc-box.warning {
    border-color: #fcd34d;
    background: #fffbeb;
}

.calc-box-title {
    margin-bottom: 6px;
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
}

.calc-box-value {
    color: #0f172a;
    font-size: 18px;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.calc-meter {
    height: 16px;
    margin-top: 14px;
    overflow: hidden;
    border-radius: 999px;
    background: #e2e8f0;
}

.calc-meter-fill {
    height: 100%;
    width: 0;
    border-radius: inherit;
    background: #2563eb;
    transition: width .5s ease;
}

.calc-fade {
    animation: calcFade .35s ease;
}

@keyframes calcFade {
    from {
        opacity: .2;
        transform: translateY(5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 700px) {
    .calc-grid {
        grid-template-columns: 1fr;
    }

    .calc-stage {
        min-height: 220px;
    }
}

.svm-calc-visual-wrap {
    margin-top: 18px;
    padding: 16px;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    background: #f8fafc;
}

.svm-calc-visual-title {
    margin-bottom: 12px;
    color: #334155;
    font-size: 14px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
}

.svm-calc-svg {
    display: block;
    width: 100%;
    height: auto;
}

.svm-calc-line {
    transition: stroke .25s ease, stroke-width .25s ease, opacity .25s ease;
}

.svm-calc-line.hyperplane {
    stroke: #2563eb;
    stroke-width: 4;
}

.svm-calc-line.margin {
    stroke: #16a34a;
    stroke-width: 3;
    stroke-dasharray: 8 6;
}

.svm-calc-line.distance {
    stroke: #f59e0b;
    stroke-width: 4;
    stroke-dasharray: 6 5;
}

.svm-calc-line.weight {
    stroke: #7c3aed;
    stroke-width: 4;
}

.svm-calc-point {
    transition: r .25s ease, opacity .25s ease, filter .25s ease;
}

.svm-calc-point.active {
    filter: drop-shadow(0 0 7px rgba(245, 158, 11, .65));
}

.svm-calc-halo {
    fill: none;
    stroke: #f59e0b;
    stroke-width: 4;
    transition: opacity .25s ease;
}

.svm-calc-label {
    fill: #334155;
    font-size: 12px;
    font-weight: 700;
    text-anchor: middle;
}

.svm-calc-subtext {
    fill: #64748b;
    font-size: 11px;
    text-anchor: middle;
}

.svm-calc-result-box {
    fill: #ffffff;
    stroke: #cbd5e1;
    stroke-width: 2;
    transition: fill .25s ease, stroke .25s ease;
}

.svm-calc-result-box.active {
    fill: #eff6ff;
    stroke: #2563eb;
}

.svm-calc-result-box.success {
    fill: #ecfdf5;
    stroke: #16a34a;
    stroke-width: 3;
}

.svm-calc-bracket {
    stroke: #16a34a;
    stroke-width: 3;
    transition: opacity .25s ease;
}

</style>

<main class="container">
    <div class="page-header">
        <h1>Support Vector Machine (SVM)</h1>
        <p>
            SVM itu bukan cuma cari garis yang bisa misahin dua kelas.
            Dia cari garis yang <strong>pemisahnya paling lega napasnya</strong>,
            alias marginnya paling lebar.
        </p>
    </div>

    <div class="card">
        <h2>Intuisi Casual Banget</h2>
        <div class="cute-note">
            Bayangin ada dua kubu anak nongkrong:
            kubu <strong>Biru</strong> dan kubu <strong>Merah</strong>.
            Kamu mau narik garis pemisah di tengah.
            Nah, SVM nggak asal narik garis.
            Dia cari garis yang jaraknya paling aman dari kedua kubu.
            Jadi kalau ada titik baru yang sedikit geser, model nggak gampang panik.
        </div>
    </div>

    <div class="card">
        <h2>1) Animasi Intuisi sampai Rumus Prediksi</h2>

        <div class="explain-grid">
            <div>
                <div class="stage-box">
                    <svg id="svmStage" viewBox="0 0 760 430">
                        <rect x="60" y="45" width="640" height="310" rx="12" fill="#fff" stroke="#e2e8f0"></rect>
                        <line x1="100" y1="320" x2="670" y2="320" stroke="#94a3b8" stroke-width="1.5"></line>
                        <line x1="100" y1="320" x2="100" y2="80" stroke="#94a3b8" stroke-width="1.5"></line>
                        <text x="390" y="345" class="subtle-text">Fitur x₁</text>
                        <text x="78" y="74" class="subtle-text">Fitur x₂</text>

                        <!-- Candidate lines -->
                        <line id="svmCand1" x1="140" y1="290" x2="620" y2="110" stroke="#94a3b8" stroke-width="2.5" stroke-dasharray="8 6" opacity="0"></line>
                        <line id="svmCand2" x1="140" y1="250" x2="620" y2="70" stroke="#94a3b8" stroke-width="2.5" stroke-dasharray="8 6" opacity="0"></line>
                        <line id="svmCand3" x1="140" y1="330" x2="620" y2="150" stroke="#94a3b8" stroke-width="2.5" stroke-dasharray="8 6" opacity="0"></line>

                        <!-- Best line and margins -->
                        <line id="svmBest" x1="150" y1="300" x2="620" y2="130" stroke="#2563eb" stroke-width="4" opacity="0"></line>
                        <line id="svmMargin1" x1="150" y1="260" x2="620" y2="90" stroke="#16a34a" stroke-width="3" stroke-dasharray="8 6" opacity="0"></line>
                        <line id="svmMargin2" x1="150" y1="340" x2="620" y2="170" stroke="#16a34a" stroke-width="3" stroke-dasharray="8 6" opacity="0"></line>
                        <text id="svmBestLabel" x="520" y="117" class="subtle-text" style="opacity:0;">Hyperplane terbaik</text>
                        <text id="svmMarginLabel1" x="560" y="74" class="subtle-text" style="opacity:0;">g(x) = +1</text>
                        <text id="svmMarginLabel2" x="560" y="185" class="subtle-text" style="opacity:0;">g(x) = -1</text>

                        <!-- support vector halo -->
                        <circle id="svHaloBlue" cx="260" cy="265" r="18" fill="none" stroke="#f59e0b" stroke-width="4" opacity="0"></circle>
                        <circle id="svHaloRed" cx="470" cy="155" r="18" fill="none" stroke="#f59e0b" stroke-width="4" opacity="0"></circle>

                        <g id="svmPoints"></g>

                        <!-- new point -->
                        <circle id="svmNewPoint" cx="0" cy="0" r="10" fill="#111827" opacity="0"></circle>
                        <text id="svmNewText" x="0" y="0" class="subtle-text" text-anchor="middle" opacity="0">baru</text>
                    </svg>
                </div>

                <div class="legend-row">
                    <span><span class="legend-dot" style="background:#2563eb;"></span>Kelas Biru</span>
                    <span><span class="legend-dot" style="background:#ef4444;"></span>Kelas Merah</span>
                    <span><span class="legend-dot" style="background:#f59e0b;"></span>Support Vector</span>
                </div>

                <div class="control-row">
                    <button class="btn-secondary" type="button" onclick="svmPrev()">← Sebelumnya</button>
                    <button class="btn-primary" type="button" onclick="svmNext()">Berikutnya →</button>
                    <button class="btn-primary" type="button" onclick="svmPlay()">▶ Play</button>
                    <button class="btn-secondary" type="button" onclick="svmPause()">⏸ Pause</button>
                    <button class="btn-secondary" type="button" onclick="svmReset()">↺ Reset</button>
                </div>

                <div class="step-chip-grid">
                    <div class="step-chip" data-svm-chip="0">Data</div>
                    <div class="step-chip" data-svm-chip="1">Banyak Garis</div>
                    <div class="step-chip" data-svm-chip="2">Margin</div>
                    <div class="step-chip" data-svm-chip="3">Support Vector</div>
                    <div class="step-chip" data-svm-chip="4">Rumus</div>
                    <div class="step-chip" data-svm-chip="5">Prediksi</div>
                </div>
            </div>

            <div class="explain-panel">
                <div class="explain-card">
                    <strong id="svmTitle"></strong>
                    <div id="svmText" style="margin-top:8px;"></div>
                    <div id="svmFormula" class="mini-formula"></div>
                </div>

                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kpi-label">Hyperplane</div>
                        <div class="kpi-value">x₁ + x₂ - 9 = 0</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Rule</div>
                        <div class="kpi-value">g(x)=0.5x₁+0.5x₂-4.5</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Support Vector</div>
                        <div class="kpi-value">(3,4) & (5,6)</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Contoh Baru</div>
                        <div class="kpi-value">(6,4)</div>
                    </div>
                </div>

                <div class="cute-note">
                    Bahasa manusia:
                    SVM cari garis yang <strong>paling aman jaraknya</strong> dari dua kubu.
                    Jadi bukan sekadar “asal bisa misah”.
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>2) Hitungan Ringan</h2>
        <p>
            Untuk contoh sederhana ini, kita pakai:
        </p>

        <div class="mini-formula">
            Hyperplane yang sama secara geometri:
            <strong>x₁ + x₂ - 9 = 0</strong>
            <br>
            Untuk bentuk hard-margin yang rapi, kita skalakan menjadi:
            <strong>g(x) = 0.5x₁ + 0.5x₂ - 4.5</strong>
            <br>
            Support vector berada di g(x)=+1 dan g(x)=-1.
        </div>

        <div class="calc-animation">
            <div class="calc-stage calc-fade" id="svmCalcStage">
                <div class="calc-step-title" id="svmCalcTitle"></div>
                <div class="calc-step-text" id="svmCalcText"></div>
                <div class="calc-equation" id="svmCalcEquation"></div>

                <div class="calc-grid">
                    <div class="calc-box" id="svmCalcBox1">
                        <div class="calc-box-title" id="svmCalcBox1Title">w</div>
                        <div class="calc-box-value" id="svmCalcBox1Value">-</div>
                    </div>
                    <div class="calc-box" id="svmCalcBox2">
                        <div class="calc-box-title" id="svmCalcBox2Title">b</div>
                        <div class="calc-box-value" id="svmCalcBox2Value">-</div>
                    </div>
                    <div class="calc-box" id="svmCalcBox3">
                        <div class="calc-box-title" id="svmCalcBox3Title">Hasil</div>
                        <div class="calc-box-value" id="svmCalcBox3Value">-</div>
                    </div>
                </div>

                <div class="calc-meter">
                    <div class="calc-meter-fill" id="svmCalcMeter"></div>
                </div>

                <div class="svm-calc-visual-wrap">
                    <div class="svm-calc-visual-title">Visualisasi dari Hitungan</div>

                    <svg class="svm-calc-svg" viewBox="0 0 900 420" aria-label="SVM calculation visualization">
                        <rect x="55" y="35" width="650" height="320" rx="12" fill="#ffffff" stroke="#e2e8f0"></rect>

                        <!-- axes -->
                        <line x1="100" y1="320" x2="665" y2="320" stroke="#94a3b8" stroke-width="1.5"></line>
                        <line x1="100" y1="320" x2="100" y2="70" stroke="#94a3b8" stroke-width="1.5"></line>
                        <text x="380" y="346" class="svm-calc-subtext">x₁</text>
                        <text x="78" y="65" class="svm-calc-subtext">x₂</text>

                        <!-- static data points -->
                        <g id="svmCalcPoints"></g>

                        <!-- canonical hyperplane and margins -->
                        <line id="svmCalcHyperplane" class="svm-calc-line hyperplane"
                              x1="145" y1="300" x2="625" y2="128" opacity="0"></line>

                        <line id="svmCalcMarginPos" class="svm-calc-line margin"
                              x1="145" y1="255" x2="625" y2="83" opacity="0"></line>

                        <line id="svmCalcMarginNeg" class="svm-calc-line margin"
                              x1="145" y1="345" x2="625" y2="173" opacity="0"></line>

                        <text id="svmCalcHyperplaneLabel" class="svm-calc-subtext"
                              x="590" y="116" opacity="0">g(x)=0</text>
                        <text id="svmCalcMarginPosLabel" class="svm-calc-subtext"
                              x="590" y="72" opacity="0">g(x)=+1</text>
                        <text id="svmCalcMarginNegLabel" class="svm-calc-subtext"
                              x="590" y="190" opacity="0">g(x)=-1</text>

                        <!-- support vector halos -->
                        <circle id="svmCalcHaloBlue" class="svm-calc-halo" cx="0" cy="0" r="19" opacity="0"></circle>
                        <circle id="svmCalcHaloRed" class="svm-calc-halo" cx="0" cy="0" r="19" opacity="0"></circle>

                        <!-- normal vector w -->
                        <line id="svmCalcWeightVector" class="svm-calc-line weight"
                              x1="385" y1="214" x2="430" y2="155" opacity="0"></line>
                        <polygon id="svmCalcWeightArrow"
                                 points="430,155 418,162 425,172"
                                 fill="#7c3aed" opacity="0"></polygon>
                        <text id="svmCalcWeightLabel" class="svm-calc-subtext"
                              x="450" y="155" opacity="0">w=[0.5,0.5]</text>

                        <!-- margin width bracket -->
                        <line id="svmCalcBracketLine" class="svm-calc-bracket"
                              x1="690" y1="96" x2="690" y2="185" opacity="0"></line>
                        <line id="svmCalcBracketTop" class="svm-calc-bracket"
                              x1="680" y1="96" x2="700" y2="96" opacity="0"></line>
                        <line id="svmCalcBracketBottom" class="svm-calc-bracket"
                              x1="680" y1="185" x2="700" y2="185" opacity="0"></line>
                        <text id="svmCalcMarginWidthLabel" class="svm-calc-subtext"
                              x="755" y="142" opacity="0">2/||w|| ≈ 2.828</text>

                        <!-- new point + its distance -->
                        <circle id="svmCalcNewPoint" class="svm-calc-point"
                                cx="0" cy="0" r="11" fill="#111827" opacity="0"></circle>
                        <text id="svmCalcNewPointLabel" class="svm-calc-subtext"
                              x="0" y="0" opacity="0">(6,4)</text>

                        <line id="svmCalcDistanceLine" class="svm-calc-line distance"
                              x1="0" y1="0" x2="0" y2="0" opacity="0"></line>
                        <text id="svmCalcDistanceLabel" class="svm-calc-subtext"
                              x="0" y="0" opacity="0">d ≈ 0.7071</text>

                        <!-- result summary -->
                        <rect id="svmCalcResultBox" class="svm-calc-result-box"
                              x="720" y="240" width="150" height="92" rx="12"></rect>
                        <text id="svmCalcResultTitle" class="svm-calc-label"
                              x="795" y="270">Hasil</text>
                        <text id="svmCalcResultValue" class="svm-calc-label"
                              x="795" y="298">-</text>
                        <text id="svmCalcResultSub" class="svm-calc-subtext"
                              x="795" y="317"></text>
                    </svg>
                </div>
            </div>

            <div class="control-row">
                <button class="btn-secondary" type="button" onclick="svmCalcPrev()">← Sebelumnya</button>
                <button class="btn-primary" type="button" onclick="svmCalcNext()">Berikutnya →</button>
                <button class="btn-primary" type="button" onclick="svmCalcPlay()">▶ Play Hitungan</button>
                <button class="btn-secondary" type="button" onclick="svmCalcPause()">⏸ Pause</button>
                <button class="btn-secondary" type="button" onclick="svmCalcReset()">↺ Reset</button>
            </div>

            <div class="step-chip-grid">
                <div class="step-chip" data-svmcalc-chip="0">Parameter</div>
                <div class="step-chip" data-svmcalc-chip="1">SV Biru</div>
                <div class="step-chip" data-svmcalc-chip="2">SV Merah</div>
                <div class="step-chip" data-svmcalc-chip="3">Lebar Margin</div>
                <div class="step-chip" data-svmcalc-chip="4">Titik Baru</div>
                <div class="step-chip" data-svmcalc-chip="5">Jarak</div>
            </div>
        </div>

        <div class="score-table-wrap" style="margin-top:16px;">
            <table class="score-table">
                <thead>
                    <tr>
                        <th>Titik</th>
                        <th>Hitung</th>
                        <th>Nilai</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>(3,4)</td>
                        <td>0.5(3) + 0.5(4) - 4.5</td>
                        <td>-1</td>
                        <td>Biru (support vector)</td>
                    </tr>
                    <tr>
                        <td>(5,6)</td>
                        <td>0.5(5) + 0.5(6) - 4.5</td>
                        <td>1</td>
                        <td>Merah (support vector)</td>
                    </tr>
                    <tr class="best">
                        <td>(6,4)</td>
                        <td>0.5(6) + 0.5(4) - 4.5</td>
                        <td><strong>0.5</strong></td>
                        <td><strong>Merah</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="cute-note" style="margin-top:16px;">
            Jadi pas ada titik baru, SVM tinggal hitung nilainya.
            Kalau hasilnya positif, masuk satu sisi. Kalau negatif, masuk sisi satunya.
            Simpel kalau sudah jadi modelnya.
        </div>
    </div>
</main>

<script>
const svmPoints = [
    { x: 2, y: 2, cls: 'blue' },
    { x: 3, y: 2, cls: 'blue' },
    { x: 3, y: 4, cls: 'blue', sv: true },
    { x: 4, y: 3, cls: 'blue' },
    { x: 5, y: 6, cls: 'red', sv: true },
    { x: 6, y: 5, cls: 'red' },
    { x: 6, y: 7, cls: 'red' },
    { x: 7, y: 7, cls: 'red' }
];

const svmSteps = [
    {
        title: 'Langkah 1 — Lihat dua kelas dulu',
        text: 'Kita punya dua kubu titik: biru dan merah. Jelas kelihatan mereka agak berjauhan.',
        formula: 'Tujuan awal: cari pemisah antara 2 kelas'
    },
    {
        title: 'Langkah 2 — Banyak garis bisa memisahkan',
        text: 'Kalau cuma “asal pisah”, banyak garis juga bisa. Tapi SVM tidak berhenti di situ.',
        formula: 'Belum tentu garis yang bisa misah itu garis terbaik'
    },
    {
        title: 'Langkah 3 — Cari margin terbesar',
        text: 'SVM pilih garis yang punya jarak paling lega ke kedua kelas. Itu yang bikin model lebih tahan terhadap noise kecil.',
        formula: 'maximize margin'
    },
    {
        title: 'Langkah 4 — Siapa support vector-nya?',
        text: 'Support vector adalah titik yang paling dekat ke batas. Mereka ini yang paling berpengaruh ke posisi garis.',
        formula: 'Di contoh ini: (3,4) dan (5,6)'
    },
    {
        title: 'Langkah 5 — Bentuk fungsi keputusan',
        text: 'Setelah hyperplane ketemu, kita tinggal bikin fungsi keputusan buat nentuin kelas titik baru.',
        formula: 'g(x) = 0.5x₁ + 0.5x₂ - 4.5'
    },
    {
        title: 'Langkah 6 — Prediksi titik baru',
        text: 'Untuk titik baru (6,4), nilai g(x)=0.5. Karena positif, dia masuk kelas Merah.',
        formula: '0.5(6) + 0.5(4) - 4.5 = 0.5 > 0 → Merah'
    }
];

let svmIndex = 0;
let svmTimer = null;

function sMapX(x) {
    return 100 + ((x - 1) / 8) * 540;
}
function sMapY(y) {
    return 320 - ((y - 1) / 8) * 220;
}

function renderSvmPoints() {
    const g = document.getElementById('svmPoints');
    g.innerHTML = '';
    svmPoints.forEach(p => {
        const grp = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        const c = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        c.setAttribute('cx', sMapX(p.x));
        c.setAttribute('cy', sMapY(p.y));
        c.setAttribute('r', 10);
        c.setAttribute('fill', p.cls === 'blue' ? '#2563eb' : '#ef4444');
        grp.appendChild(c);

        const t = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        t.setAttribute('x', sMapX(p.x));
        t.setAttribute('y', sMapY(p.y) - 16);
        t.setAttribute('text-anchor', 'middle');
        t.setAttribute('class', 'subtle-text');
        t.textContent = '(' + p.x + ',' + p.y + ')';
        grp.appendChild(t);
        g.appendChild(grp);
    });
}

function svmSetOpacity(id, value) {
    document.getElementById(id).setAttribute('opacity', value);
}

function svmRender() {
    renderSvmPoints();
    ['svmCand1','svmCand2','svmCand3','svmBest','svmMargin1','svmMargin2',
     'svmBestLabel','svmMarginLabel1','svmMarginLabel2','svHaloBlue','svHaloRed',
     'svmNewPoint','svmNewText'].forEach(id => svmSetOpacity(id, 0));

    if (svmIndex >= 1) {
        ['svmCand1','svmCand2','svmCand3'].forEach(id => svmSetOpacity(id, 1));
    }
    if (svmIndex >= 2) {
        svmSetOpacity('svmBest', 1);
        svmSetOpacity('svmMargin1', 1);
        svmSetOpacity('svmMargin2', 1);
        svmSetOpacity('svmBestLabel', 1);
        svmSetOpacity('svmMarginLabel1', 1);
        svmSetOpacity('svmMarginLabel2', 1);
    }
    if (svmIndex >= 3) {
        document.getElementById('svHaloBlue').setAttribute('cx', sMapX(3));
        document.getElementById('svHaloBlue').setAttribute('cy', sMapY(4));
        document.getElementById('svHaloRed').setAttribute('cx', sMapX(5));
        document.getElementById('svHaloRed').setAttribute('cy', sMapY(6));
        svmSetOpacity('svHaloBlue', 1);
        svmSetOpacity('svHaloRed', 1);
    }
    if (svmIndex >= 5) {
        document.getElementById('svmNewPoint').setAttribute('cx', sMapX(6));
        document.getElementById('svmNewPoint').setAttribute('cy', sMapY(4));
        document.getElementById('svmNewText').setAttribute('x', sMapX(6));
        document.getElementById('svmNewText').setAttribute('y', sMapY(4) - 18);
        svmSetOpacity('svmNewPoint', 1);
        svmSetOpacity('svmNewText', 1);
    }

    const step = svmSteps[svmIndex];
    document.getElementById('svmTitle').textContent = step.title;
    document.getElementById('svmText').textContent = step.text;
    document.getElementById('svmFormula').innerHTML = step.formula;

    document.querySelectorAll('[data-svm-chip]').forEach(chip => {
        chip.classList.toggle('active', Number(chip.dataset.svmChip) === svmIndex);
    });
}
function svmNext() {
    if (svmIndex < svmSteps.length - 1) {
        svmIndex++;
        svmRender();
    }
}
function svmPrev() {
    if (svmIndex > 0) {
        svmIndex--;
        svmRender();
    }
}
function svmPlay() {
    svmPause();
    svmTimer = setInterval(() => {
        if (svmIndex >= svmSteps.length - 1) {
            svmPause();
            return;
        }
        svmNext();
    }, 1700);
}
function svmPause() {
    if (svmTimer !== null) {
        clearInterval(svmTimer);
        svmTimer = null;
    }
}
function svmReset() {
    svmPause();
    svmIndex = 0;
    svmRender();
}
svmRender();


const svmCalcSteps = [
    {
        title: 'Hitungan 1 — Ambil parameter hyperplane',
        text: 'Kita pakai bentuk canonical supaya support vector pas di margin ±1.',
        equation: 'w = [0.5, 0.5], b = -4.5 → g(x) = w·x + b',
        box1Title: 'w',
        box1Value: '[0.5, 0.5]',
        box2Title: 'b',
        box2Value: '-4.5',
        box3Title: 'Hyperplane',
        box3Value: 'g(x)=0',
        meter: 17
    },
    {
        title: 'Hitungan 2 — Cek support vector Biru (3,4)',
        text: 'Kalau benar support vector sisi Biru, nilainya harus tepat -1 pada bentuk canonical.',
        equation: 'g(3,4) = 0.5(3) + 0.5(4) - 4.5 = 1.5 + 2 - 4.5 = -1',
        box1Title: 'x',
        box1Value: '(3,4)',
        box2Title: 'g(x)',
        box2Value: '-1',
        box3Title: 'Status',
        box3Value: 'Support Vector Biru',
        meter: 34
    },
    {
        title: 'Hitungan 3 — Cek support vector Merah (5,6)',
        text: 'Support vector di sisi Merah harus tepat +1.',
        equation: 'g(5,6) = 0.5(5) + 0.5(6) - 4.5 = 2.5 + 3 - 4.5 = 1',
        box1Title: 'x',
        box1Value: '(5,6)',
        box2Title: 'g(x)',
        box2Value: '+1',
        box3Title: 'Status',
        box3Value: 'Support Vector Merah',
        meter: 50
    },
    {
        title: 'Hitungan 4 — Hitung lebar margin',
        text: 'Norm weight memberi tahu seberapa “miring/kuat” hyperplane. Lebar dua margin adalah 2 dibagi norm w.',
        equation: '||w|| = √(0.5² + 0.5²) = √0.5 ≈ 0.7071 | Margin width = 2/0.7071 ≈ 2.828',
        box1Title: '||w||',
        box1Value: '0.7071',
        box2Title: '2 / ||w||',
        box2Value: '2.828',
        box3Title: 'Makna',
        box3Value: 'Lebar antar-margin',
        meter: 67
    },
    {
        title: 'Hitungan 5 — Prediksi titik baru (6,4)',
        text: 'Sekarang tinggal masukkan titik baru ke fungsi keputusan.',
        equation: 'g(6,4) = 0.5(6) + 0.5(4) - 4.5 = 3 + 2 - 4.5 = 0.5',
        box1Title: 'x baru',
        box1Value: '(6,4)',
        box2Title: 'g(x)',
        box2Value: '+0.5',
        box3Title: 'Prediksi',
        box3Value: 'Merah',
        meter: 84
    },
    {
        title: 'Hitungan 6 — Seberapa jauh titik baru dari hyperplane?',
        text: 'Nilai fungsi keputusan belum sama dengan jarak geometris. Jarak sebenarnya dibagi dengan norm w.',
        equation: 'distance = |g(x)| / ||w|| = 0.5 / 0.7071 ≈ 0.7071',
        box1Title: '|g(x)|',
        box1Value: '0.5',
        box2Title: '||w||',
        box2Value: '0.7071',
        box3Title: 'Jarak',
        box3Value: '≈ 0.7071',
        meter: 100
    }
];

let svmCalcIndex = 0;
let svmCalcTimer = null;

function svmCalcMapX(x)
{
    return 100 + ((x - 1) / 8) * 540;
}

function svmCalcMapY(y)
{
    return 320 - ((y - 1) / 8) * 220;
}

function svmCalcSetOpacity(ids, value)
{
    ids.forEach(id => {
        document.getElementById(id).setAttribute('opacity', value);
    });
}

function svmCalcRenderPoints()
{
    const group = document.getElementById('svmCalcPoints');

    if (group.childNodes.length > 0) {
        return;
    }

    svmPoints.forEach(point => {
        const circle = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'circle'
        );

        circle.setAttribute('cx', svmCalcMapX(point.x));
        circle.setAttribute('cy', svmCalcMapY(point.y));
        circle.setAttribute('r', 9);
        circle.setAttribute(
            'fill',
            point.cls === 'blue' ? '#2563eb' : '#ef4444'
        );

        const text = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'text'
        );

        text.setAttribute('x', svmCalcMapX(point.x));
        text.setAttribute('y', svmCalcMapY(point.y) - 15);
        text.setAttribute('class', 'svm-calc-subtext');
        text.textContent = '(' + point.x + ',' + point.y + ')';

        group.appendChild(circle);
        group.appendChild(text);
    });
}

function svmCalcRender()
{
    const step = svmCalcSteps[svmCalcIndex];

    document.getElementById('svmCalcTitle').textContent = step.title;
    document.getElementById('svmCalcText').textContent = step.text;
    document.getElementById('svmCalcEquation').textContent = step.equation;

    document.getElementById('svmCalcBox1Title').textContent = step.box1Title;
    document.getElementById('svmCalcBox1Value').textContent = step.box1Value;
    document.getElementById('svmCalcBox2Title').textContent = step.box2Title;
    document.getElementById('svmCalcBox2Value').textContent = step.box2Value;
    document.getElementById('svmCalcBox3Title').textContent = step.box3Title;
    document.getElementById('svmCalcBox3Value').textContent = step.box3Value;

    ['svmCalcBox1', 'svmCalcBox2', 'svmCalcBox3'].forEach(id => {
        document.getElementById(id).classList.remove(
            'active',
            'success',
            'warning'
        );
        document.getElementById(id).classList.add('active');
    });

    if (
        svmCalcIndex === 1 ||
        svmCalcIndex === 2 ||
        svmCalcIndex === 4 ||
        svmCalcIndex === 5
    ) {
        document.getElementById('svmCalcBox3').classList.add('success');
    }

    document.getElementById('svmCalcMeter').style.width =
        step.meter + '%';

    svmCalcRenderPoints();

    /* Reset calculation illustration. */
    svmCalcSetOpacity([
        'svmCalcHyperplane',
        'svmCalcHyperplaneLabel',
        'svmCalcMarginPos',
        'svmCalcMarginNeg',
        'svmCalcMarginPosLabel',
        'svmCalcMarginNegLabel',
        'svmCalcHaloBlue',
        'svmCalcHaloRed',
        'svmCalcWeightVector',
        'svmCalcWeightArrow',
        'svmCalcWeightLabel',
        'svmCalcBracketLine',
        'svmCalcBracketTop',
        'svmCalcBracketBottom',
        'svmCalcMarginWidthLabel',
        'svmCalcNewPoint',
        'svmCalcNewPointLabel',
        'svmCalcDistanceLine',
        'svmCalcDistanceLabel'
    ], 0);

    document.getElementById('svmCalcResultBox')
        .classList.remove('active', 'success');

    document.getElementById('svmCalcResultTitle').textContent = 'Hasil';
    document.getElementById('svmCalcResultValue').textContent = '-';
    document.getElementById('svmCalcResultSub').textContent = '';

    /*
     * Step 1 — parameter w and b.
     */
    if (svmCalcIndex >= 0) {
        svmCalcSetOpacity([
            'svmCalcHyperplane',
            'svmCalcHyperplaneLabel',
            'svmCalcWeightVector',
            'svmCalcWeightArrow',
            'svmCalcWeightLabel'
        ], 1);

        document.getElementById('svmCalcResultBox')
            .classList.add('active');

        document.getElementById('svmCalcResultValue').textContent =
            'g(x)=0';
        document.getElementById('svmCalcResultSub').textContent =
            'hyperplane';
    }

    /*
     * Step 2 — blue support vector.
     */
    if (svmCalcIndex >= 1) {
        document.getElementById('svmCalcHaloBlue').setAttribute(
            'cx',
            svmCalcMapX(3)
        );
        document.getElementById('svmCalcHaloBlue').setAttribute(
            'cy',
            svmCalcMapY(4)
        );

        svmCalcSetOpacity([
            'svmCalcMarginNeg',
            'svmCalcMarginNegLabel',
            'svmCalcHaloBlue'
        ], 1);

        document.getElementById('svmCalcResultValue').textContent =
            'g(3,4) = -1';
        document.getElementById('svmCalcResultSub').textContent =
            'support vector Biru';
    }

    /*
     * Step 3 — red support vector.
     */
    if (svmCalcIndex >= 2) {
        document.getElementById('svmCalcHaloRed').setAttribute(
            'cx',
            svmCalcMapX(5)
        );
        document.getElementById('svmCalcHaloRed').setAttribute(
            'cy',
            svmCalcMapY(6)
        );

        svmCalcSetOpacity([
            'svmCalcMarginPos',
            'svmCalcMarginPosLabel',
            'svmCalcHaloRed'
        ], 1);

        document.getElementById('svmCalcResultValue').textContent =
            'g(5,6) = +1';
        document.getElementById('svmCalcResultSub').textContent =
            'support vector Merah';
    }

    /*
     * Step 4 — visualize width between margins.
     */
    if (svmCalcIndex >= 3) {
        svmCalcSetOpacity([
            'svmCalcMarginPos',
            'svmCalcMarginNeg',
            'svmCalcMarginPosLabel',
            'svmCalcMarginNegLabel',
            'svmCalcBracketLine',
            'svmCalcBracketTop',
            'svmCalcBracketBottom',
            'svmCalcMarginWidthLabel'
        ], 1);

        document.getElementById('svmCalcResultValue').textContent =
            '2.828';
        document.getElementById('svmCalcResultSub').textContent =
            'lebar margin';
    }

    /*
     * Step 5 — show new point and its predicted side.
     */
    if (svmCalcIndex >= 4) {
        const newX = svmCalcMapX(6);
        const newY = svmCalcMapY(4);

        document.getElementById('svmCalcNewPoint').setAttribute(
            'cx',
            newX
        );
        document.getElementById('svmCalcNewPoint').setAttribute(
            'cy',
            newY
        );

        document.getElementById('svmCalcNewPointLabel').setAttribute(
            'x',
            newX
        );
        document.getElementById('svmCalcNewPointLabel').setAttribute(
            'y',
            newY - 18
        );

        svmCalcSetOpacity([
            'svmCalcNewPoint',
            'svmCalcNewPointLabel'
        ], 1);

        document.getElementById('svmCalcResultBox')
            .classList.add('success');

        document.getElementById('svmCalcResultValue').textContent =
            'Merah';
        document.getElementById('svmCalcResultSub').textContent =
            'g(6,4)=+0.5';
    }

    /*
     * Step 6 — show perpendicular-ish distance to hyperplane.
     *
     * For this educational drawing we use the analytic projection for
     * w=[0.5,0.5], b=-4.5.
     *
     * x = (6,4)
     * g(x) = 0.5
     * ||w||² = 0.5
     * projection = x - (g/||w||²)w
     *            = (6,4) - 1*(0.5,0.5)
     *            = (5.5,3.5)
     */
    if (svmCalcIndex >= 5) {
        const x1 = svmCalcMapX(6);
        const y1 = svmCalcMapY(4);
        const x2 = svmCalcMapX(5.5);
        const y2 = svmCalcMapY(3.5);

        document.getElementById('svmCalcDistanceLine').setAttribute(
            'x1',
            x1
        );
        document.getElementById('svmCalcDistanceLine').setAttribute(
            'y1',
            y1
        );
        document.getElementById('svmCalcDistanceLine').setAttribute(
            'x2',
            x2
        );
        document.getElementById('svmCalcDistanceLine').setAttribute(
            'y2',
            y2
        );

        document.getElementById('svmCalcDistanceLabel').setAttribute(
            'x',
            (x1 + x2) / 2 + 35
        );
        document.getElementById('svmCalcDistanceLabel').setAttribute(
            'y',
            (y1 + y2) / 2 - 8
        );

        svmCalcSetOpacity([
            'svmCalcDistanceLine',
            'svmCalcDistanceLabel'
        ], 1);

        document.getElementById('svmCalcResultBox')
            .classList.add('success');

        document.getElementById('svmCalcResultValue').textContent =
            'd ≈ 0.7071';
        document.getElementById('svmCalcResultSub').textContent =
            'jarak ke hyperplane';
    }

    const stage = document.getElementById('svmCalcStage');
    stage.classList.remove('calc-fade');
    void stage.offsetWidth;
    stage.classList.add('calc-fade');

    document.querySelectorAll('[data-svmcalc-chip]').forEach(chip => {
        chip.classList.toggle(
            'active',
            Number(chip.dataset.svmcalcChip) === svmCalcIndex
        );
    });
}

function svmCalcNext()
{
    if (svmCalcIndex < svmCalcSteps.length - 1) {
        svmCalcIndex++;
        svmCalcRender();
    }
}

function svmCalcPrev()
{
    if (svmCalcIndex > 0) {
        svmCalcIndex--;
        svmCalcRender();
    }
}

function svmCalcPlay()
{
    svmCalcPause();

    svmCalcTimer = setInterval(() => {
        if (svmCalcIndex >= svmCalcSteps.length - 1) {
            svmCalcPause();
            return;
        }

        svmCalcNext();
    }, 1800);
}

function svmCalcPause()
{
    if (svmCalcTimer !== null) {
        clearInterval(svmCalcTimer);
        svmCalcTimer = null;
    }
}

function svmCalcReset()
{
    svmCalcPause();
    svmCalcIndex = 0;
    svmCalcRender();
}

svmCalcRender();

</script>