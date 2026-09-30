<main class="container">
    <div class="page-header">
        <h1>Decision Tree</h1>
        <p>
            Bayangin Decision Tree itu kayak game “tanya jawab cepat”.
            Modelnya terus nanya: <em>“jam belajar lebih dari segini nggak?”</em>,
            <em>“kehadiran cukup nggak?”</em>, lalu pelan-pelan menyaring data sampai ketemu keputusan.
        </p>
    </div>

    <div class="card">
        <h2>Contoh Santai: Prediksi Lulus atau Tidak</h2>
        <p>
            Kita pakai 2 fitur:
            <strong>jam belajar per minggu</strong> dan <strong>persentase kehadiran</strong>.
            Targetnya: <strong>Lulus</strong> atau <strong>Tidak Lulus</strong>.
        </p>

        <div class="score-table-wrap">
            <table class="score-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Jam Belajar</th>
                        <th>Kehadiran</th>
                        <th>Label</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>A</td><td>2</td><td>60</td><td><span class="badge badge-no">Tidak</span></td></tr>
                    <tr><td>B</td><td>3</td><td>65</td><td><span class="badge badge-no">Tidak</span></td></tr>
                    <tr><td>C</td><td>4</td><td>70</td><td><span class="badge badge-no">Tidak</span></td></tr>
                    <tr><td>D</td><td>5</td><td>72</td><td><span class="badge badge-yes">Lulus</span></td></tr>
                    <tr><td>E</td><td>6</td><td>80</td><td><span class="badge badge-yes">Lulus</span></td></tr>
                    <tr><td>F</td><td>7</td><td>78</td><td><span class="badge badge-yes">Lulus</span></td></tr>
                    <tr><td>G</td><td>8</td><td>90</td><td><span class="badge badge-yes">Lulus</span></td></tr>
                    <tr><td>H</td><td>5</td><td>74</td><td><span class="badge badge-no">Tidak</span></td></tr>
                </tbody>
            </table>
        </div>

        <div class="cute-note" style="margin-top:16px;">
            Intuisi paling gampang:
            Decision Tree itu mirip dosen yang bilang,
            <strong>“Kalau belajarnya dikit banget, kayaknya susah lulus.”</strong>
            Lalu kalau datanya masih campur, dia nanya lagi dengan aturan kedua.
        </div>
    </div>

    <div class="card">
        <h2>1) Animasi Intuisi sampai Pohonnya Jadi</h2>

        <div class="explain-grid">
            <div>
                <div class="stage-box">
                    <svg id="dtStage" viewBox="0 0 760 430">
                        <rect x="55" y="45" width="300" height="300" rx="10" fill="#ffffff" stroke="#e2e8f0"></rect>
                        <text x="205" y="28" class="tree-text" style="font-size:15px;">Plot Data</text>

                        <!-- axes -->
                        <line x1="95" y1="315" x2="325" y2="315" stroke="#94a3b8" stroke-width="1.5"></line>
                        <line x1="95" y1="315" x2="95" y2="75" stroke="#94a3b8" stroke-width="1.5"></line>
                        <text x="215" y="338" class="subtle-text">Jam Belajar</text>
                        <text x="58" y="68" class="subtle-text">Kehadiran</text>

                        <!-- split lines -->
                        <line id="dtSplit1" x1="210" y1="75" x2="210" y2="315" stroke="#2563eb" stroke-width="4" stroke-dasharray="8 6" opacity="0"></line>
                        <text id="dtSplit1Text" x="210" y="62" class="subtle-text" style="opacity:0; text-anchor:middle;">Jam Belajar ≤ 4.5</text>

                        <line id="dtSplit2" x1="210" y1="200" x2="325" y2="200" stroke="#16a34a" stroke-width="4" stroke-dasharray="8 6" opacity="0"></line>
                        <text id="dtSplit2Text" x="268" y="188" class="subtle-text" style="opacity:0; text-anchor:middle;">Kehadiran ≤ 76</text>

                        <line id="dtSplit3" x1="210" y1="216" x2="240" y2="216" stroke="#f59e0b" stroke-width="4" stroke-dasharray="8 6" opacity="0"></line>
                        <text id="dtSplit3Text" x="226" y="234" class="subtle-text" style="opacity:0; text-anchor:middle;">≤ 73</text>

                        <!-- points -->
                        <g id="dtPoints"></g>

                        <!-- tree -->
                        <text x="560" y="28" class="tree-text" style="font-size:15px;">Pohon Keputusan</text>

                        <line id="treeL1" class="tree-link" x1="560" y1="90" x2="450" y2="180"></line>
                        <line id="treeL2" class="tree-link" x1="560" y1="90" x2="670" y2="180"></line>

                        <circle id="treeRoot" class="tree-node" cx="560" cy="90" r="42"></circle>
                        <text id="treeRootText" class="tree-text" x="560" y="82">?</text>
                        <text id="treeRootSub" class="subtle-text" x="560" y="102" text-anchor="middle">root</text>

                        <circle id="treeLeftLeaf" class="tree-node" cx="450" cy="180" r="38" opacity="0"></circle>
                        <text id="treeLeftLeafText" class="tree-text" x="450" y="180" opacity="0">Tidak</text>

                        <circle id="treeRightNode" class="tree-node" cx="670" cy="180" r="42" opacity="0"></circle>
                        <text id="treeRightNodeText" class="tree-text" x="670" y="172" opacity="0">?</text>
                        <text id="treeRightNodeSub" class="subtle-text" x="670" y="192" text-anchor="middle" opacity="0">campur</text>

                        <line id="treeL3" class="tree-link" x1="670" y1="222" x2="610" y2="302" opacity="0"></line>
                        <line id="treeL4" class="tree-link" x1="670" y1="222" x2="730" y2="302" opacity="0"></line>

                        <circle id="treeMidNode" class="tree-node" cx="610" cy="302" r="36" opacity="0"></circle>
                        <text id="treeMidNodeText" class="tree-text" x="610" y="302" opacity="0">mix</text>

                        <circle id="treeRightLeaf" class="tree-node" cx="730" cy="302" r="36" opacity="0"></circle>
                        <text id="treeRightLeafText" class="tree-text" x="730" y="302" opacity="0">Lulus</text>

                        <line id="treeL5" class="tree-link" x1="610" y1="338" x2="570" y2="388" opacity="0"></line>
                        <line id="treeL6" class="tree-link" x1="610" y1="338" x2="650" y2="388" opacity="0"></line>

                        <circle id="treeLeafY" class="tree-node" cx="570" cy="388" r="28" opacity="0"></circle>
                        <text id="treeLeafYText" class="tree-text" x="570" y="388" opacity="0">Lulus</text>
                        <circle id="treeLeafN" class="tree-node" cx="650" cy="388" r="28" opacity="0"></circle>
                        <text id="treeLeafNText" class="tree-text" x="650" y="388" opacity="0">Tidak</text>
                    </svg>
                </div>

                <div class="legend-row">
                    <span><span class="legend-dot" style="background:#2563eb;"></span>Lulus</span>
                    <span><span class="legend-dot" style="background:#ef4444;"></span>Tidak Lulus</span>
                </div>

                <div class="control-row">
                    <button class="btn-secondary" type="button" onclick="dtPrev()">← Sebelumnya</button>
                    <button class="btn-primary" type="button" onclick="dtNext()">Berikutnya →</button>
                    <button class="btn-primary" type="button" onclick="dtPlay()">▶ Play</button>
                    <button class="btn-secondary" type="button" onclick="dtPause()">⏸ Pause</button>
                    <button class="btn-secondary" type="button" onclick="dtReset()">↺ Reset</button>
                </div>

                <div class="step-chip-grid">
                    <div class="step-chip" data-dt-chip="0">Data</div>
                    <div class="step-chip" data-dt-chip="1">Campur</div>
                    <div class="step-chip" data-dt-chip="2">Coba Split</div>
                    <div class="step-chip" data-dt-chip="3">Split Root</div>
                    <div class="step-chip" data-dt-chip="4">Split Lanjutan</div>
                    <div class="step-chip" data-dt-chip="5">Prediksi</div>
                </div>
            </div>

            <div class="explain-panel">
                <div class="explain-card">
                    <strong id="dtTitle"></strong>
                    <div id="dtText" style="margin-top:8px;"></div>
                    <div id="dtFormula" class="mini-formula"></div>
                </div>

                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kpi-label">Root Gini</div>
                        <div class="kpi-value">0.50</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Best Split 1</div>
                        <div class="kpi-value">Jam ≤ 4.5</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Best Split 2</div>
                        <div class="kpi-value">Kehadiran ≤ 76</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Best Split 3</div>
                        <div class="kpi-value">Kehadiran ≤ 73</div>
                    </div>
                </div>

                <div class="cute-note">
                    Bahasa gampangnya:
                    pohon ini cuma lagi cari pertanyaan yang bikin data
                    <strong>semakin rapi dan tidak campur-campur</strong>.
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>2) Hitungan Sederhana: Kenapa Split itu Dipilih?</h2>
        <p>
            Di sini kita pakai <strong>Gini impurity</strong>.
            Kalau satu node isinya campur aduk, Gini besar.
            Kalau node isinya satu kelas doang, Gini = 0.
        </p>

        <div class="mini-formula">
            Gini = 1 - Σ p(class)²
        </div>

        <div class="score-table-wrap" style="margin-top:16px;">
            <table class="score-table">
                <thead>
                    <tr>
                        <th>Kandidat Split</th>
                        <th>Kiri / Bawah</th>
                        <th>Kanan / Atas</th>
                        <th>Weighted Gini</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="best">
                        <td>Jam Belajar ≤ 4.5</td>
                        <td>3 Tidak → Gini 0</td>
                        <td>4 Lulus, 1 Tidak → Gini 0.32</td>
                        <td><strong>0.20</strong></td>
                        <td>Dipakai sebagai root</td>
                    </tr>
                    <tr>
                        <td>Kehadiran ≤ 76</td>
                        <td>1 Lulus, 4 Tidak → Gini 0.32</td>
                        <td>3 Lulus → Gini 0</td>
                        <td>0.20</td>
                        <td>Sama bagusnya, tapi kita pilih split belajar dulu supaya lebih gampang dibaca</td>
                    </tr>
                    <tr>
                        <td>Jam Belajar ≤ 5.5</td>
                        <td>1 Lulus, 4 Tidak → Gini 0.32</td>
                        <td>3 Lulus → Gini 0</td>
                        <td>0.20</td>
                        <td>Masih tie</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="cute-note" style="margin-top:16px;">
            Jadi intinya gini:
            <strong>semakin kecil weighted Gini, semakin “rapi” hasil pecahannya</strong>.
            Kalau ada beberapa yang tie, implementasi nyata biasanya punya aturan tambahan
            buat milih salah satu.
        </div>
    </div>

    <div class="card">
        <h2>3) Contoh Prediksi</h2>
        <p>
            Misal ada siswa baru:
            <strong>jam belajar = 5</strong>,
            <strong>kehadiran = 79</strong>.
        </p>

        <div class="mini-formula">
            Root: 5 ≤ 4.5 ? → Tidak → ke kanan
            <br>
            Node 2: 79 ≤ 76 ? → Tidak → ke kanan
            <br>
            Leaf akhir: <strong>Lulus</strong>
        </div>

        <div class="cute-note" style="margin-top:16px;">
            Nah, ini enaknya Decision Tree:
            jalur keputusannya kelihatan jelas. Jadi lebih gampang dijelasin ke orang non-teknis.
        </div>
    </div>
</main>

<script>
const dtData = [
    { name: 'A', x: 2, y: 60, label: 'Tidak' },
    { name: 'B', x: 3, y: 65, label: 'Tidak' },
    { name: 'C', x: 4, y: 70, label: 'Tidak' },
    { name: 'D', x: 5, y: 72, label: 'Lulus' },
    { name: 'E', x: 6, y: 80, label: 'Lulus' },
    { name: 'F', x: 7, y: 78, label: 'Lulus' },
    { name: 'G', x: 8, y: 90, label: 'Lulus' },
    { name: 'H', x: 5, y: 74, label: 'Tidak' }
];

const dtSteps = [
    {
        title: 'Langkah 1 — Lihat datanya dulu',
        text: 'Awalnya semua data masih numpuk jadi satu. Belum ada split, belum ada aturan apa-apa.',
        formula: 'Node root berisi 4 Lulus + 4 Tidak'
    },
    {
        title: 'Langkah 2 — Kita sadar datanya masih campur',
        text: 'Kalau dalam satu node isinya campur kelas biru dan merah, berarti node itu belum “rapi”.',
        formula: 'Gini(root) = 1 - (4/8)² - (4/8)² = 0.50'
    },
    {
        title: 'Langkah 3 — Coba beberapa pertanyaan kandidat',
        text: 'Pohon mencoba banyak kandidat split. Bukan ngasal milih pertanyaan, tapi cari pertanyaan yang paling bikin data rapi.',
        formula: 'Contoh kandidat: Jam ≤ 4.5, Kehadiran ≤ 76, Jam ≤ 5.5'
    },
    {
        title: 'Langkah 4 — Pilih split pertama',
        text: 'Kita ambil Jam Belajar ≤ 4.5. Bagian kiri langsung bersih: isinya cuma “Tidak”. Lumayan banget.',
        formula: 'Weighted Gini = (3/8)×0 + (5/8)×0.32 = 0.20'
    },
    {
        title: 'Langkah 5 — Node kanan masih campur, pecah lagi',
        text: 'Di cabang kanan masih ada campuran. Jadi kita pecah lagi dengan Kehadiran ≤ 76, lalu satu cabang kecil dipecah sekali lagi dengan ≤ 73.',
        formula: 'Pohon berhenti saat leaf cukup bersih / cukup jelas'
    },
    {
        title: 'Langkah 6 — Pakai pohonnya buat prediksi',
        text: 'Begitu pohon jadi, prediksi itu tinggal jalan-jalan dari root ke leaf. Santai, runtut, dan enak dijelasin.',
        formula: 'Contoh baru: (5,79) → kanan → kanan → Lulus'
    }
];

let dtIndex = 0;
let dtTimer = null;

function dtMapX(x) {
    return 95 + ((x - 1) / 8) * 215;
}
function dtMapY(y) {
    return 315 - ((y - 55) / 40) * 220;
}

function renderDtPoints() {
    const group = document.getElementById('dtPoints');
    group.innerHTML = '';

    dtData.forEach(item => {
        const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('cx', dtMapX(item.x));
        circle.setAttribute('cy', dtMapY(item.y));
        circle.setAttribute('r', 9);
        circle.setAttribute('fill', item.label === 'Lulus' ? '#2563eb' : '#ef4444');
        circle.setAttribute('opacity', '0.92');

        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        text.setAttribute('x', dtMapX(item.x));
        text.setAttribute('y', dtMapY(item.y) - 16);
        text.setAttribute('text-anchor', 'middle');
        text.setAttribute('class', 'subtle-text');
        text.textContent = item.name;

        g.appendChild(circle);
        g.appendChild(text);
        group.appendChild(g);
    });
}

function setOpacity(id, value) {
    document.getElementById(id).setAttribute('opacity', value);
}

function dtRender() {
    renderDtPoints();

    ['dtSplit1','dtSplit1Text','dtSplit2','dtSplit2Text','dtSplit3','dtSplit3Text',
     'treeLeftLeaf','treeLeftLeafText','treeRightNode','treeRightNodeText','treeRightNodeSub',
     'treeL3','treeL4','treeMidNode','treeMidNodeText','treeRightLeaf','treeRightLeafText',
     'treeL5','treeL6','treeLeafY','treeLeafYText','treeLeafN','treeLeafNText']
     .forEach(id => setOpacity(id, 0));

    document.getElementById('treeRootText').textContent = '?';
    document.getElementById('treeRoot').classList.remove('active');
    document.getElementById('treeLeftLeaf').classList.remove('active');
    document.getElementById('treeRightNode').classList.remove('active');
    document.getElementById('treeMidNode').classList.remove('active');
    document.getElementById('treeRightLeaf').classList.remove('active');
    document.getElementById('treeLeafY').classList.remove('active');
    document.getElementById('treeLeafN').classList.remove('active');
    ['treeL1','treeL2','treeL3','treeL4','treeL5','treeL6'].forEach(id => {
        document.getElementById(id).classList.remove('active');
    });

    if (dtIndex >= 1) {
        document.getElementById('treeRoot').classList.add('active');
        document.getElementById('treeRootText').textContent = 'Campur';
    }

    if (dtIndex >= 2) {
        setOpacity('dtSplit1', 1);
        setOpacity('dtSplit1Text', 1);
        document.getElementById('treeL1').classList.add('active');
        document.getElementById('treeL2').classList.add('active');
    }

    if (dtIndex >= 3) {
        setOpacity('treeLeftLeaf', 1);
        setOpacity('treeLeftLeafText', 1);
        setOpacity('treeRightNode', 1);
        setOpacity('treeRightNodeText', 1);
        setOpacity('treeRightNodeSub', 1);

        document.getElementById('treeLeftLeaf').classList.add('active');
        document.getElementById('treeRightNode').classList.add('active');
        document.getElementById('treeRootText').textContent = 'Jam ≤ 4.5';
        document.getElementById('treeRightNodeText').textContent = 'mix';
    }

    if (dtIndex >= 4) {
        setOpacity('dtSplit2', 1);
        setOpacity('dtSplit2Text', 1);

        ['treeL3','treeL4','treeMidNode','treeMidNodeText','treeRightLeaf','treeRightLeafText']
            .forEach(id => setOpacity(id, 1));

        document.getElementById('treeL3').classList.add('active');
        document.getElementById('treeL4').classList.add('active');
        document.getElementById('treeRightNodeText').textContent = 'Kehadiran ≤ 76';
        document.getElementById('treeMidNode').classList.add('active');
        document.getElementById('treeRightLeaf').classList.add('active');

        setOpacity('dtSplit3', 1);
        setOpacity('dtSplit3Text', 1);
        ['treeL5','treeL6','treeLeafY','treeLeafYText','treeLeafN','treeLeafNText']
            .forEach(id => setOpacity(id, 1));
        document.getElementById('treeL5').classList.add('active');
        document.getElementById('treeL6').classList.add('active');
        document.getElementById('treeMidNode').classList.add('active');
        document.getElementById('treeMidNodeText').textContent = 'Kehadiran ≤ 73';
        document.getElementById('treeLeafY').classList.add('active');
        document.getElementById('treeLeafN').classList.add('active');
    }

    if (dtIndex >= 5) {
        document.getElementById('treeRightLeaf').classList.add('active');
    }

    const step = dtSteps[dtIndex];
    document.getElementById('dtTitle').textContent = step.title;
    document.getElementById('dtText').textContent = step.text;
    document.getElementById('dtFormula').innerHTML = step.formula;

    document.querySelectorAll('[data-dt-chip]').forEach(chip => {
        chip.classList.toggle('active', Number(chip.dataset.dtChip) === dtIndex);
    });
}

function dtNext() {
    if (dtIndex < dtSteps.length - 1) {
        dtIndex++;
        dtRender();
    }
}
function dtPrev() {
    if (dtIndex > 0) {
        dtIndex--;
        dtRender();
    }
}
function dtPlay() {
    dtPause();
    dtTimer = setInterval(() => {
        if (dtIndex >= dtSteps.length - 1) {
            dtPause();
            return;
        }
        dtNext();
    }, 1700);
}
function dtPause() {
    if (dtTimer !== null) {
        clearInterval(dtTimer);
        dtTimer = null;
    }
}
function dtReset() {
    dtPause();
    dtIndex = 0;
    dtRender();
}
dtRender();
</script>