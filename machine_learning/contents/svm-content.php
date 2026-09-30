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
                        <text id="svmMarginLabel1" x="560" y="74" class="subtle-text" style="opacity:0;">Margin +1</text>
                        <text id="svmMarginLabel2" x="560" y="185" class="subtle-text" style="opacity:0;">Margin -1</text>

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
                        <div class="kpi-value">f(x) = sign(w·x+b)</div>
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
            f(x) = x₁ + x₂ - 9
            <br>
            Jika f(x) &gt; 0 → kelas Merah
            <br>
            Jika f(x) &lt; 0 → kelas Biru
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
                        <td>3 + 4 - 9</td>
                        <td>-2</td>
                        <td>Biru (support vector)</td>
                    </tr>
                    <tr>
                        <td>(5,6)</td>
                        <td>5 + 6 - 9</td>
                        <td>2</td>
                        <td>Merah (support vector)</td>
                    </tr>
                    <tr class="best">
                        <td>(6,4)</td>
                        <td>6 + 4 - 9</td>
                        <td><strong>1</strong></td>
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
        formula: 'f(x) = x₁ + x₂ - 9'
    },
    {
        title: 'Langkah 6 — Prediksi titik baru',
        text: 'Untuk titik baru (6,4), nilai f(x)=1. Karena positif, dia masuk kelas Merah.',
        formula: '6 + 4 - 9 = 1 > 0 → Merah'
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
</script>