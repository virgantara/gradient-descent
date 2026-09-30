
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

.rf-calc-forest-wrap {
    margin-top: 18px;
    padding: 16px;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    background: #f8fafc;
}

.rf-calc-forest-title {
    margin-bottom: 12px;
    color: #334155;
    font-size: 14px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
}

.rf-calc-forest-svg {
    width: 100%;
    height: auto;
    display: block;
}

.rf-calc-tree-card {
    fill: #ffffff;
    stroke: #e2e8f0;
    stroke-width: 2;
    transition: stroke .25s ease, fill .25s ease, opacity .25s ease;
}

.rf-calc-tree-card.active {
    stroke: #93c5fd;
    fill: #f8fbff;
}

.rf-calc-tree-node {
    fill: #ffffff;
    stroke: #cbd5e1;
    stroke-width: 2.5;
    transition: fill .25s ease, stroke .25s ease, opacity .25s ease;
}

.rf-calc-tree-node.active {
    fill: #eff6ff;
    stroke: #2563eb;
}

.rf-calc-tree-node.leaf-yes.active,
.rf-calc-tree-node.path-yes {
    fill: #ecfdf5;
    stroke: #16a34a;
}

.rf-calc-tree-node.leaf-no.active,
.rf-calc-tree-node.path-no {
    fill: #fef2f2;
    stroke: #ef4444;
}

.rf-calc-tree-link {
    stroke: #cbd5e1;
    stroke-width: 2.5;
    transition: stroke .25s ease, opacity .25s ease;
}

.rf-calc-tree-link.active {
    stroke: #2563eb;
}

.rf-calc-tree-link.path-yes {
    stroke: #16a34a;
    stroke-width: 4;
}

.rf-calc-tree-link.path-no {
    stroke: #ef4444;
    stroke-width: 4;
}

.rf-calc-tree-text {
    fill: #0f172a;
    font-size: 12px;
    font-weight: 700;
    text-anchor: middle;
}

.rf-calc-tree-subtext {
    fill: #64748b;
    font-size: 10px;
    text-anchor: middle;
}

.rf-calc-vote-box {
    fill: #ffffff;
    stroke: #cbd5e1;
    stroke-width: 2;
    transition: fill .25s ease, stroke .25s ease, opacity .25s ease;
}

.rf-calc-vote-box.active {
    fill: #eff6ff;
    stroke: #2563eb;
}

.rf-calc-vote-box.winner {
    fill: #ecfdf5;
    stroke: #16a34a;
    stroke-width: 3;
}

</style>

<main class="container">
    <div class="page-header">
        <h1>Random Forest</h1>
        <p>
            Kalau Decision Tree itu satu orang yang bikin keputusan,
            Random Forest itu kayak <strong>ngumpulin banyak orang pintar lalu voting</strong>.
            Jadi biasanya hasilnya lebih stabil, nggak gampang “baper” ke data tertentu.
        </p>
    </div>

    <div class="card">
        <h2>Intuisi Super Casual</h2>
        <div class="cute-note">
            Bayangin kamu nanya ke satu teman:
            <em>“Aku bakal lulus nggak ya?”</em>
            Bisa aja dia terlalu pede atau terlalu galak.
            Tapi kalau kamu tanya ke <strong>10 teman</strong>, lalu ambil suara terbanyak,
            hasilnya biasanya lebih fair. Nah, itu vibe-nya Random Forest.
        </div>
    </div>

    <div class="card">
        <h2>1) Animasi: Dari Satu Data ke Banyak Pohon</h2>

        <div class="explain-grid">
            <div>
                <div class="stage-box">
                    <svg id="rfStage" viewBox="0 0 760 430">
                        <text x="118" y="26" class="tree-text" style="font-size:15px;">Dataset Asli</text>
                        <rect x="30" y="40" width="180" height="155" rx="12" fill="#fff" stroke="#e2e8f0"></rect>
                        <text x="48" y="68" class="subtle-text">A (2,60) → Tidak</text>
                        <text x="48" y="88" class="subtle-text">B (3,65) → Tidak</text>
                        <text x="48" y="108" class="subtle-text">C (4,70) → Tidak</text>
                        <text x="48" y="128" class="subtle-text">D (5,72) → Lulus</text>
                        <text x="48" y="148" class="subtle-text">E (6,80) → Lulus</text>
                        <text x="48" y="168" class="subtle-text">F (7,78) → Lulus</text>

                        <text x="384" y="26" class="tree-text" style="font-size:15px;">Bootstrap Samples</text>
                        <rect id="boot1" x="260" y="40" width="110" height="155" rx="12" fill="#fff" stroke="#e2e8f0"></rect>
                        <rect id="boot2" x="385" y="40" width="110" height="155" rx="12" fill="#fff" stroke="#e2e8f0"></rect>
                        <rect id="boot3" x="510" y="40" width="110" height="155" rx="12" fill="#fff" stroke="#e2e8f0"></rect>
                        <text x="315" y="62" class="tree-text">Sample 1</text>
                        <text x="440" y="62" class="tree-text">Sample 2</text>
                        <text x="565" y="62" class="tree-text">Sample 3</text>
                        <text id="boot1Text" x="315" y="96" class="subtle-text" text-anchor="middle"></text>
                        <text id="boot1Text2" x="315" y="116" class="subtle-text" text-anchor="middle"></text>
                        <text id="boot1Text3" x="315" y="136" class="subtle-text" text-anchor="middle"></text>
                        <text id="boot2Text" x="440" y="96" class="subtle-text" text-anchor="middle"></text>
                        <text id="boot2Text2" x="440" y="116" class="subtle-text" text-anchor="middle"></text>
                        <text id="boot2Text3" x="440" y="136" class="subtle-text" text-anchor="middle"></text>
                        <text id="boot3Text" x="565" y="96" class="subtle-text" text-anchor="middle"></text>
                        <text id="boot3Text2" x="565" y="116" class="subtle-text" text-anchor="middle"></text>
                        <text id="boot3Text3" x="565" y="136" class="subtle-text" text-anchor="middle"></text>

                        <text x="384" y="232" class="tree-text" style="font-size:15px;">Mini Trees + Voting</text>

                        <rect id="treeCard1" x="220" y="248" width="150" height="140" rx="12" fill="#fff" stroke="#e2e8f0"></rect>
                        <text x="295" y="270" class="tree-text">Tree 1</text>
                        <line x1="295" y1="286" x2="255" y2="326" class="tree-link" id="rfL1"></line>
                        <line x1="295" y1="286" x2="335" y2="326" class="tree-link" id="rfL2"></line>
                        <circle cx="295" cy="286" r="16" class="tree-node" id="rfRoot1"></circle>
                        <circle cx="255" cy="326" r="13" class="tree-node" id="rfLeaf1A"></circle>
                        <circle cx="335" cy="326" r="13" class="tree-node" id="rfLeaf1B"></circle>
                        <text x="295" y="286" class="tree-text" style="font-size:10px;">?</text>
                        <text x="255" y="326" class="tree-text" style="font-size:10px;">N</text>
                        <text x="335" y="326" class="tree-text" style="font-size:10px;">Y</text>
                        <text id="rfVote1" x="295" y="365" class="tree-text" style="font-size:14px;"></text>

                        <rect id="treeCard2" x="390" y="248" width="150" height="140" rx="12" fill="#fff" stroke="#e2e8f0"></rect>
                        <text x="465" y="270" class="tree-text">Tree 2</text>
                        <line x1="465" y1="286" x2="425" y2="326" class="tree-link" id="rfL3"></line>
                        <line x1="465" y1="286" x2="505" y2="326" class="tree-link" id="rfL4"></line>
                        <circle cx="465" cy="286" r="16" class="tree-node" id="rfRoot2"></circle>
                        <circle cx="425" cy="326" r="13" class="tree-node" id="rfLeaf2A"></circle>
                        <circle cx="505" cy="326" r="13" class="tree-node" id="rfLeaf2B"></circle>
                        <text x="465" y="286" class="tree-text" style="font-size:10px;">?</text>
                        <text x="425" y="326" class="tree-text" style="font-size:10px;">N</text>
                        <text x="505" y="326" class="tree-text" style="font-size:10px;">Y</text>
                        <text id="rfVote2" x="465" y="365" class="tree-text" style="font-size:14px;"></text>

                        <rect id="treeCard3" x="560" y="248" width="150" height="140" rx="12" fill="#fff" stroke="#e2e8f0"></rect>
                        <text x="635" y="270" class="tree-text">Tree 3</text>
                        <line x1="635" y1="286" x2="595" y2="326" class="tree-link" id="rfL5"></line>
                        <line x1="635" y1="286" x2="675" y2="326" class="tree-link" id="rfL6"></line>
                        <circle cx="635" cy="286" r="16" class="tree-node" id="rfRoot3"></circle>
                        <circle cx="595" cy="326" r="13" class="tree-node" id="rfLeaf3A"></circle>
                        <circle cx="675" cy="326" r="13" class="tree-node" id="rfLeaf3B"></circle>
                        <text x="635" y="286" class="tree-text" style="font-size:10px;">?</text>
                        <text x="595" y="326" class="tree-text" style="font-size:10px;">N</text>
                        <text x="675" y="326" class="tree-text" style="font-size:10px;">Y</text>
                        <text id="rfVote3" x="635" y="365" class="tree-text" style="font-size:14px;"></text>

                        <text id="rfFinalVote" x="382" y="410" class="tree-text" style="font-size:18px;"></text>
                    </svg>
                </div>

                <div class="control-row">
                    <button class="btn-secondary" type="button" onclick="rfPrev()">← Sebelumnya</button>
                    <button class="btn-primary" type="button" onclick="rfNext()">Berikutnya →</button>
                    <button class="btn-primary" type="button" onclick="rfPlay()">▶ Play</button>
                    <button class="btn-secondary" type="button" onclick="rfPause()">⏸ Pause</button>
                    <button class="btn-secondary" type="button" onclick="rfReset()">↺ Reset</button>
                </div>

                <div class="step-chip-grid">
                    <div class="step-chip" data-rf-chip="0">Dataset</div>
                    <div class="step-chip" data-rf-chip="1">Bootstrap</div>
                    <div class="step-chip" data-rf-chip="2">Tree 1</div>
                    <div class="step-chip" data-rf-chip="3">Tree 2</div>
                    <div class="step-chip" data-rf-chip="4">Tree 3</div>
                    <div class="step-chip" data-rf-chip="5">Voting</div>
                </div>
            </div>

            <div class="explain-panel">
                <div class="explain-card">
                    <strong id="rfTitle"></strong>
                    <div id="rfText" style="margin-top:8px;"></div>
                    <div id="rfFormula" class="mini-formula"></div>
                </div>

                <div class="vote-box">
                    <div class="vote-card">
                        <h4>Tree 1</h4>
                        <div>Prediksi untuk siswa baru (5,79):</div>
                        <div class="vote-result" id="rfVoteBox1">-</div>
                    </div>
                    <div class="vote-card">
                        <h4>Tree 2</h4>
                        <div>Prediksi untuk siswa baru (5,79):</div>
                        <div class="vote-result" id="rfVoteBox2">-</div>
                    </div>
                    <div class="vote-card">
                        <h4>Tree 3</h4>
                        <div>Prediksi untuk siswa baru (5,79):</div>
                        <div class="vote-result" id="rfVoteBox3">-</div>
                    </div>
                </div>

                <div class="cute-note">
                    Inti Random Forest:
                    <strong>jangan terlalu percaya satu pohon</strong>.
                    Mending bikin banyak pohon, lalu pakai suara terbanyak.
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>2) Hitungan Ringan: Kenapa Disebut “Random”?</h2>

        <ul class="info-list">
            <li><strong>Random #1:</strong> tiap tree dilatih pakai <em>bootstrap sample</em>, yaitu sampling acak dengan pengembalian.</li>
            <li><strong>Random #2:</strong> tiap node biasanya hanya melihat <em>sebagian fitur</em>, bukan semua fitur.</li>
            <li>Karena tiap tree agak beda, mereka bikin kesalahan yang beda-beda juga. Nah, di sinilah voting jadi powerful.</li>
        </ul>

        <div class="mini-formula" style="margin-top:16px;">
            Prediksi akhir = mode(Tree₁, Tree₂, Tree₃, ..., Treeₙ)
        </div>

        <div class="calc-animation">
            <div class="calc-stage calc-fade" id="rfCalcStage">
                <div class="calc-step-title" id="rfCalcTitle"></div>
                <div class="calc-step-text" id="rfCalcText"></div>
                <div class="calc-equation" id="rfCalcEquation"></div>

                <div class="calc-grid">
                    <div class="calc-box" id="rfCalcBox1">
                        <div class="calc-box-title" id="rfCalcBox1Title">Tree 1</div>
                        <div class="calc-box-value" id="rfCalcBox1Value">-</div>
                    </div>
                    <div class="calc-box" id="rfCalcBox2">
                        <div class="calc-box-title" id="rfCalcBox2Title">Tree 2</div>
                        <div class="calc-box-value" id="rfCalcBox2Value">-</div>
                    </div>
                    <div class="calc-box" id="rfCalcBox3">
                        <div class="calc-box-title" id="rfCalcBox3Title">Tree 3</div>
                        <div class="calc-box-value" id="rfCalcBox3Value">-</div>
                    </div>
                </div>

                <div class="calc-meter">
                    <div class="calc-meter-fill" id="rfCalcMeter"></div>
                </div>

                <div class="rf-calc-forest-wrap">
                    <div class="rf-calc-forest-title">Bentuk Random Forest dari Perhitungan</div>

                    <svg class="rf-calc-forest-svg" viewBox="0 0 920 430" aria-label="Random Forest calculation animation">
                        <!-- TREE 1 -->
                        <rect id="rfCalcCard1" class="rf-calc-tree-card" x="20" y="25" width="270" height="300" rx="14"></rect>
                        <text class="rf-calc-tree-text" x="155" y="50">Tree 1</text>
                        <text id="rfCalcSample1" class="rf-calc-tree-subtext" x="155" y="68">A,C,D,D,F,H</text>

                        <line id="rfCalcT1L1" class="rf-calc-tree-link" x1="155" y1="110" x2="90" y2="170"></line>
                        <line id="rfCalcT1L2" class="rf-calc-tree-link" x1="155" y1="110" x2="220" y2="170"></line>

                        <circle id="rfCalcT1Root" class="rf-calc-tree-node" cx="155" cy="100" r="38"></circle>
                        <text class="rf-calc-tree-text" x="155" y="95">Jam</text>
                        <text class="rf-calc-tree-subtext" x="155" y="111">≤ 4.5 ?</text>

                        <circle id="rfCalcT1LeafN" class="rf-calc-tree-node leaf-no" cx="90" cy="180" r="32"></circle>
                        <text class="rf-calc-tree-text" x="90" y="184">Tidak</text>

                        <circle id="rfCalcT1Node2" class="rf-calc-tree-node" cx="220" cy="180" r="34"></circle>
                        <text class="rf-calc-tree-text" x="220" y="176">Hadir</text>
                        <text class="rf-calc-tree-subtext" x="220" y="191">≤ 75 ?</text>

                        <line id="rfCalcT1L3" class="rf-calc-tree-link" x1="220" y1="214" x2="180" y2="260"></line>
                        <line id="rfCalcT1L4" class="rf-calc-tree-link" x1="220" y1="214" x2="255" y2="260"></line>

                        <circle id="rfCalcT1LeafN2" class="rf-calc-tree-node leaf-no" cx="180" cy="270" r="28"></circle>
                        <text class="rf-calc-tree-text" x="180" y="274">Tidak</text>

                        <circle id="rfCalcT1LeafY" class="rf-calc-tree-node leaf-yes" cx="255" cy="270" r="28"></circle>
                        <text class="rf-calc-tree-text" x="255" y="274">Lulus</text>

                        <text id="rfCalcVote1" class="rf-calc-tree-text" x="155" y="308">Vote: -</text>

                        <!-- TREE 2 -->
                        <rect id="rfCalcCard2" class="rf-calc-tree-card" x="325" y="25" width="270" height="300" rx="14"></rect>
                        <text class="rf-calc-tree-text" x="460" y="50">Tree 2</text>
                        <text id="rfCalcSample2" class="rf-calc-tree-subtext" x="460" y="68">B,C,E,F,G,H</text>

                        <line id="rfCalcT2L1" class="rf-calc-tree-link" x1="460" y1="110" x2="395" y2="185"></line>
                        <line id="rfCalcT2L2" class="rf-calc-tree-link" x1="460" y1="110" x2="525" y2="185"></line>

                        <circle id="rfCalcT2Root" class="rf-calc-tree-node" cx="460" cy="100" r="40"></circle>
                        <text class="rf-calc-tree-text" x="460" y="95">Hadir</text>
                        <text class="rf-calc-tree-subtext" x="460" y="112">≤ 76 ?</text>

                        <circle id="rfCalcT2LeafN" class="rf-calc-tree-node leaf-no" cx="395" cy="195" r="32"></circle>
                        <text class="rf-calc-tree-text" x="395" y="199">Tidak</text>

                        <circle id="rfCalcT2LeafY" class="rf-calc-tree-node leaf-yes" cx="525" cy="195" r="32"></circle>
                        <text class="rf-calc-tree-text" x="525" y="199">Lulus</text>

                        <text id="rfCalcVote2" class="rf-calc-tree-text" x="460" y="308">Vote: -</text>

                        <!-- TREE 3 -->
                        <rect id="rfCalcCard3" class="rf-calc-tree-card" x="630" y="25" width="270" height="300" rx="14"></rect>
                        <text class="rf-calc-tree-text" x="765" y="50">Tree 3</text>
                        <text id="rfCalcSample3" class="rf-calc-tree-subtext" x="765" y="68">A,B,C,E,H,H</text>

                        <line id="rfCalcT3L1" class="rf-calc-tree-link" x1="765" y1="110" x2="700" y2="185"></line>
                        <line id="rfCalcT3L2" class="rf-calc-tree-link" x1="765" y1="110" x2="830" y2="185"></line>

                        <circle id="rfCalcT3Root" class="rf-calc-tree-node" cx="765" cy="100" r="40"></circle>
                        <text class="rf-calc-tree-text" x="765" y="95">Jam</text>
                        <text class="rf-calc-tree-subtext" x="765" y="112">≤ 5.5 ?</text>

                        <circle id="rfCalcT3LeafN" class="rf-calc-tree-node leaf-no" cx="700" cy="195" r="32"></circle>
                        <text class="rf-calc-tree-text" x="700" y="199">Tidak</text>

                        <circle id="rfCalcT3LeafY" class="rf-calc-tree-node leaf-yes" cx="830" cy="195" r="32"></circle>
                        <text class="rf-calc-tree-text" x="830" y="199">Lulus</text>

                        <text id="rfCalcVote3" class="rf-calc-tree-text" x="765" y="308">Vote: -</text>

                        <!-- voting -->
                        <line id="rfCalcVoteLine1" class="rf-calc-tree-link" x1="155" y1="325" x2="395" y2="365"></line>
                        <line id="rfCalcVoteLine2" class="rf-calc-tree-link" x1="460" y1="325" x2="460" y2="365"></line>
                        <line id="rfCalcVoteLine3" class="rf-calc-tree-link" x1="765" y1="325" x2="525" y2="365"></line>

                        <rect id="rfCalcFinalVoteBox" class="rf-calc-vote-box" x="350" y="355" width="220" height="58" rx="12"></rect>
                        <text id="rfCalcFinalVoteText1" class="rf-calc-tree-text" x="460" y="380">Voting</text>
                        <text id="rfCalcFinalVoteText2" class="rf-calc-tree-subtext" x="460" y="400">belum dihitung</text>
                    </svg>
                </div>
            </div>

            <div class="control-row">
                <button class="btn-secondary" type="button" onclick="rfCalcPrev()">← Sebelumnya</button>
                <button class="btn-primary" type="button" onclick="rfCalcNext()">Berikutnya →</button>
                <button class="btn-primary" type="button" onclick="rfCalcPlay()">▶ Play Hitungan</button>
                <button class="btn-secondary" type="button" onclick="rfCalcPause()">⏸ Pause</button>
                <button class="btn-secondary" type="button" onclick="rfCalcReset()">↺ Reset</button>
            </div>

            <div class="step-chip-grid">
                <div class="step-chip" data-rfcalc-chip="0">Bootstrap</div>
                <div class="step-chip" data-rfcalc-chip="1">Prediksi Tree</div>
                <div class="step-chip" data-rfcalc-chip="2">Encode Vote</div>
                <div class="step-chip" data-rfcalc-chip="3">Jumlah Vote</div>
                <div class="step-chip" data-rfcalc-chip="4">Majority</div>
                <div class="step-chip" data-rfcalc-chip="5">Final</div>
            </div>
        </div>

        <div class="score-table-wrap" style="margin-top:16px;">
            <table class="score-table">
                <thead>
                    <tr>
                        <th>Tree</th>
                        <th>Contoh bootstrap sample</th>
                        <th>Prediksi untuk siswa baru (5,79)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tree 1</td>
                        <td>A, C, D, D, F, H</td>
                        <td><span class="badge badge-yes">Lulus</span></td>
                    </tr>
                    <tr>
                        <td>Tree 2</td>
                        <td>B, C, E, F, G, H</td>
                        <td><span class="badge badge-yes">Lulus</span></td>
                    </tr>
                    <tr>
                        <td>Tree 3</td>
                        <td>A, B, C, E, H, H</td>
                        <td><span class="badge badge-no">Tidak</span></td>
                    </tr>
                    <tr class="best">
                        <td><strong>Voting</strong></td>
                        <td>2 suara Lulus vs 1 suara Tidak</td>
                        <td><strong>Lulus</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="cute-note" style="margin-top:16px;">
            Satu tree bisa ngaco. Tapi kalau banyak tree dan mayoritas bilang hal yang sama,
            kita jadi lebih pede sama hasilnya.
        </div>
    </div>
</main>

<script>
const rfSteps = [
    {
        title: 'Langkah 1 — Mulai dari dataset asli',
        text: 'Semua tree berangkat dari dataset yang sama, tapi nanti masing-masing akan melihat sampel acak yang sedikit berbeda.',
        formula: 'Dataset asli → banyak versi bootstrap'
    },
    {
        title: 'Langkah 2 — Bootstrap sampling',
        text: 'Bootstrap artinya ambil sampel secara acak dengan pengembalian. Jadi data yang sama bisa muncul lebih dari sekali.',
        formula: 'Contoh: A, C, D, D, F, H'
    },
    {
        title: 'Langkah 3 — Tree 1 tumbuh',
        text: 'Tree pertama belajar dari sampel bootstrap pertama. Aturannya bisa mirip Decision Tree, tapi data latihnya tidak persis sama.',
        formula: 'Setiap tree = decision tree kecil'
    },
    {
        title: 'Langkah 4 — Tree 2 tumbuh',
        text: 'Tree kedua dapat sampel lain, jadi bisa menghasilkan split yang beda tipis.',
        formula: 'Perbedaan kecil antar-tree itu justru bagus'
    },
    {
        title: 'Langkah 5 — Tree 3 tumbuh',
        text: 'Tree ketiga juga begitu. Hasil individual bisa beda, dan itu normal.',
        formula: 'Nggak harus semua tree sepakat'
    },
    {
        title: 'Langkah 6 — Voting!',
        text: 'Akhirnya semua tree vote. Untuk contoh siswa baru (5,79), mayoritas tree bilang “Lulus”.',
        formula: 'mode(Y, Y, N) = Y'
    }
];

let rfIndex = 0;
let rfTimer = null;

function rfSet(id, text) {
    document.getElementById(id).textContent = text;
}
function rfRender() {
    rfSet('boot1Text', rfIndex >= 1 ? 'A, C, D' : '');
    rfSet('boot1Text2', rfIndex >= 1 ? 'D, F, H' : '');
    rfSet('boot1Text3', rfIndex >= 1 ? '(ada D dua kali)' : '');
    rfSet('boot2Text', rfIndex >= 1 ? 'B, C, E' : '');
    rfSet('boot2Text2', rfIndex >= 1 ? 'F, G, H' : '');
    rfSet('boot2Text3', rfIndex >= 1 ? '' : '');
    rfSet('boot3Text', rfIndex >= 1 ? 'A, B, C' : '');
    rfSet('boot3Text2', rfIndex >= 1 ? 'E, H, H' : '');
    rfSet('boot3Text3', rfIndex >= 1 ? '(ada H dua kali)' : '');

    document.getElementById('rfVote1').textContent = rfIndex >= 2 ? 'Vote: Lulus' : '';
    document.getElementById('rfVote2').textContent = rfIndex >= 3 ? 'Vote: Lulus' : '';
    document.getElementById('rfVote3').textContent = rfIndex >= 4 ? 'Vote: Tidak' : '';
    document.getElementById('rfVoteBox1').textContent = rfIndex >= 2 ? 'Lulus' : '-';
    document.getElementById('rfVoteBox2').textContent = rfIndex >= 3 ? 'Lulus' : '-';
    document.getElementById('rfVoteBox3').textContent = rfIndex >= 4 ? 'Tidak' : '-';
    document.getElementById('rfFinalVote').textContent = rfIndex >= 5 ? 'Mayoritas vote: LULUS ✅' : '';

    const step = rfSteps[rfIndex];
    document.getElementById('rfTitle').textContent = step.title;
    document.getElementById('rfText').textContent = step.text;
    document.getElementById('rfFormula').innerHTML = step.formula;

    document.querySelectorAll('[data-rf-chip]').forEach(chip => {
        chip.classList.toggle('active', Number(chip.dataset.rfChip) === rfIndex);
    });
}
function rfNext() {
    if (rfIndex < rfSteps.length - 1) {
        rfIndex++;
        rfRender();
    }
}
function rfPrev() {
    if (rfIndex > 0) {
        rfIndex--;
        rfRender();
    }
}
function rfPlay() {
    rfPause();
    rfTimer = setInterval(() => {
        if (rfIndex >= rfSteps.length - 1) {
            rfPause();
            return;
        }
        rfNext();
    }, 1600);
}
function rfPause() {
    if (rfTimer !== null) {
        clearInterval(rfTimer);
        rfTimer = null;
    }
}
function rfReset() {
    rfPause();
    rfIndex = 0;
    rfRender();
}
rfRender();


const rfCalcSteps = [
    {
        title: 'Hitungan 1 — Lihat bootstrap tiap tree',
        text: 'Setiap tree belajar dari sampel berbeda. Ada data yang bisa terambil dua kali, ada juga yang tidak kebagian.',
        equation: 'Tree 1: A,C,D,D,F,H | Tree 2: B,C,E,F,G,H | Tree 3: A,B,C,E,H,H',
        box1Title: 'Tree 1 Sample',
        box1Value: 'A,C,D,D,F,H',
        box2Title: 'Tree 2 Sample',
        box2Value: 'B,C,E,F,G,H',
        box3Title: 'Tree 3 Sample',
        box3Value: 'A,B,C,E,H,H',
        meter: 17
    },
    {
        title: 'Hitungan 2 — Masing-masing tree bikin prediksi',
        text: 'Untuk siswa baru (5,79), tiap tree jalan dengan aturan yang dia pelajari sendiri.',
        equation: 'Tree₁ → Lulus | Tree₂ → Lulus | Tree₃ → Tidak',
        box1Title: 'Tree 1',
        box1Value: 'Lulus',
        box2Title: 'Tree 2',
        box2Value: 'Lulus',
        box3Title: 'Tree 3',
        box3Value: 'Tidak',
        meter: 34
    },
    {
        title: 'Hitungan 3 — Biar gampang dihitung, encode vote',
        text: 'Kita bisa anggap Lulus = 1 dan Tidak = 0. Ini cuma trik hitung sederhana.',
        equation: 'Vote = [1, 1, 0]',
        box1Title: 'Vote 1',
        box1Value: '1',
        box2Title: 'Vote 2',
        box2Value: '1',
        box3Title: 'Vote 3',
        box3Value: '0',
        meter: 50
    },
    {
        title: 'Hitungan 4 — Jumlahkan suara Lulus',
        text: 'Dari tiga tree, dua tree memilih Lulus.',
        equation: 'Jumlah vote Lulus = 1 + 1 + 0 = 2',
        box1Title: 'Total Tree',
        box1Value: '3',
        box2Title: 'Vote Lulus',
        box2Value: '2',
        box3Title: 'Vote Tidak',
        box3Value: '1',
        meter: 67
    },
    {
        title: 'Hitungan 5 — Cek majority threshold',
        text: 'Dengan 3 tree, mayoritas butuh lebih dari 1.5 suara. Karena kita punya 2 suara Lulus, kelas Lulus menang.',
        equation: '2 > 3/2 = 1.5 → mayoritas Lulus',
        box1Title: 'Threshold',
        box1Value: '> 1.5',
        box2Title: 'Vote Lulus',
        box2Value: '2',
        box3Title: 'Status',
        box3Value: 'Mayoritas',
        meter: 84
    },
    {
        title: 'Hitungan 6 — Ambil mode / suara terbanyak',
        text: 'Hasil akhir Random Forest adalah kelas yang paling banyak dipilih tree.',
        equation: 'mode(Lulus, Lulus, Tidak) = Lulus',
        box1Title: 'Lulus',
        box1Value: '2 suara',
        box2Title: 'Tidak',
        box2Value: '1 suara',
        box3Title: 'Prediksi Akhir',
        box3Value: 'LULUS',
        meter: 100
    }
];

let rfCalcIndex = 0;
let rfCalcTimer = null;

function rfCalcSetOpacity(ids, value)
{
    ids.forEach(id => {
        document.getElementById(id).setAttribute('opacity', value);
    });
}

function rfCalcToggleClass(ids, className, enabled)
{
    ids.forEach(id => {
        document.getElementById(id).classList.toggle(className, enabled);
    });
}

function rfCalcRender()
{
    const step = rfCalcSteps[rfCalcIndex];

    document.getElementById('rfCalcTitle').textContent = step.title;
    document.getElementById('rfCalcText').textContent = step.text;
    document.getElementById('rfCalcEquation').textContent = step.equation;

    document.getElementById('rfCalcBox1Title').textContent = step.box1Title;
    document.getElementById('rfCalcBox1Value').textContent = step.box1Value;
    document.getElementById('rfCalcBox2Title').textContent = step.box2Title;
    document.getElementById('rfCalcBox2Value').textContent = step.box2Value;
    document.getElementById('rfCalcBox3Title').textContent = step.box3Title;
    document.getElementById('rfCalcBox3Value').textContent = step.box3Value;

    ['rfCalcBox1', 'rfCalcBox2', 'rfCalcBox3'].forEach(id => {
        document.getElementById(id).classList.remove('active', 'success', 'warning');
        document.getElementById(id).classList.add('active');
    });

    if (rfCalcIndex === 1) {
        document.getElementById('rfCalcBox1').classList.add('success');
        document.getElementById('rfCalcBox2').classList.add('success');
        document.getElementById('rfCalcBox3').classList.add('warning');
    }

    if (rfCalcIndex === 5) {
        document.getElementById('rfCalcBox3').classList.add('success');
    }

    document.getElementById('rfCalcMeter').style.width = step.meter + '%';

    /* Reset tree animation state. */
    rfCalcToggleClass(
        ['rfCalcCard1', 'rfCalcCard2', 'rfCalcCard3'],
        'active',
        false
    );

    rfCalcToggleClass(
        [
            'rfCalcT1Root','rfCalcT1LeafN','rfCalcT1Node2','rfCalcT1LeafN2','rfCalcT1LeafY',
            'rfCalcT2Root','rfCalcT2LeafN','rfCalcT2LeafY',
            'rfCalcT3Root','rfCalcT3LeafN','rfCalcT3LeafY'
        ],
        'active',
        false
    );

    ['path-yes', 'path-no'].forEach(className => {
        rfCalcToggleClass(
            [
                'rfCalcT1Root','rfCalcT1LeafN','rfCalcT1Node2','rfCalcT1LeafN2','rfCalcT1LeafY',
                'rfCalcT2Root','rfCalcT2LeafN','rfCalcT2LeafY',
                'rfCalcT3Root','rfCalcT3LeafN','rfCalcT3LeafY'
            ],
            className,
            false
        );

        rfCalcToggleClass(
            [
                'rfCalcT1L1','rfCalcT1L2','rfCalcT1L3','rfCalcT1L4',
                'rfCalcT2L1','rfCalcT2L2',
                'rfCalcT3L1','rfCalcT3L2',
                'rfCalcVoteLine1','rfCalcVoteLine2','rfCalcVoteLine3'
            ],
            className,
            false
        );
    });

    rfCalcToggleClass(
        [
            'rfCalcT1L1','rfCalcT1L2','rfCalcT1L3','rfCalcT1L4',
            'rfCalcT2L1','rfCalcT2L2',
            'rfCalcT3L1','rfCalcT3L2',
            'rfCalcVoteLine1','rfCalcVoteLine2','rfCalcVoteLine3'
        ],
        'active',
        false
    );

    document.getElementById('rfCalcFinalVoteBox').classList.remove('active', 'winner');

    document.getElementById('rfCalcVote1').textContent = 'Vote: -';
    document.getElementById('rfCalcVote2').textContent = 'Vote: -';
    document.getElementById('rfCalcVote3').textContent = 'Vote: -';
    document.getElementById('rfCalcFinalVoteText1').textContent = 'Voting';
    document.getElementById('rfCalcFinalVoteText2').textContent = 'belum dihitung';

    if (rfCalcIndex >= 0) {
        rfCalcToggleClass(['rfCalcCard1', 'rfCalcCard2', 'rfCalcCard3'], 'active', true);
        rfCalcToggleClass(['rfCalcT1Root', 'rfCalcT2Root', 'rfCalcT3Root'], 'active', true);
    }

    /*
     * Step 1: show bootstrap samples in each tree.
     */
    if (rfCalcIndex >= 0) {
        document.getElementById('rfCalcSample1').textContent = 'A,C,D,D,F,H';
        document.getElementById('rfCalcSample2').textContent = 'B,C,E,F,G,H';
        document.getElementById('rfCalcSample3').textContent = 'A,B,C,E,H,H';
    }

    /*
     * Step 2+: animate prediction paths for x=(5,79).
     *
     * Tree 1:
     * 5 <= 4.5? no -> right
     * 79 <= 75? no -> right -> Lulus
     */
    if (rfCalcIndex >= 1) {
        rfCalcToggleClass(
            ['rfCalcT1Root', 'rfCalcT1Node2', 'rfCalcT1LeafY'],
            'path-yes',
            true
        );
        rfCalcToggleClass(
            ['rfCalcT1L2', 'rfCalcT1L4'],
            'path-yes',
            true
        );
        document.getElementById('rfCalcVote1').textContent = 'Vote: Lulus';
    }

    /*
     * Tree 2:
     * 79 <= 76? no -> right -> Lulus
     */
    if (rfCalcIndex >= 1) {
        rfCalcToggleClass(
            ['rfCalcT2Root', 'rfCalcT2LeafY'],
            'path-yes',
            true
        );
        rfCalcToggleClass(
            ['rfCalcT2L2'],
            'path-yes',
            true
        );
        document.getElementById('rfCalcVote2').textContent = 'Vote: Lulus';
    }

    /*
     * Tree 3:
     * 5 <= 5.5? yes -> left -> Tidak
     */
    if (rfCalcIndex >= 1) {
        rfCalcToggleClass(
            ['rfCalcT3Root', 'rfCalcT3LeafN'],
            'path-no',
            true
        );
        rfCalcToggleClass(
            ['rfCalcT3L1'],
            'path-no',
            true
        );
        document.getElementById('rfCalcVote3').textContent = 'Vote: Tidak';
    }

    /*
     * Step 3+: encode vote is already visible in the calculation boxes.
     * Keep the three tree results visually active.
     */
    if (rfCalcIndex >= 2) {
        document.getElementById('rfCalcFinalVoteBox').classList.add('active');
        document.getElementById('rfCalcFinalVoteText1').textContent = 'Vote = [1, 1, 0]';
        document.getElementById('rfCalcFinalVoteText2').textContent = 'Lulus=1, Tidak=0';
    }

    /*
     * Step 4+: connect the trees into voting.
     */
    if (rfCalcIndex >= 3) {
        rfCalcToggleClass(
            ['rfCalcVoteLine1', 'rfCalcVoteLine2', 'rfCalcVoteLine3'],
            'active',
            true
        );

        document.getElementById('rfCalcFinalVoteText1').textContent = '2 vs 1';
        document.getElementById('rfCalcFinalVoteText2').textContent = '2 Lulus, 1 Tidak';
    }

    /*
     * Step 5: majority threshold.
     */
    if (rfCalcIndex >= 4) {
        document.getElementById('rfCalcFinalVoteBox').classList.add('winner');
        document.getElementById('rfCalcFinalVoteText1').textContent = '2 > 1.5';
        document.getElementById('rfCalcFinalVoteText2').textContent = 'mayoritas = Lulus';
    }

    /*
     * Final step.
     */
    if (rfCalcIndex >= 5) {
        document.getElementById('rfCalcFinalVoteBox').classList.add('winner');
        document.getElementById('rfCalcFinalVoteText1').textContent = 'LULUS ✅';
        document.getElementById('rfCalcFinalVoteText2').textContent = 'mode(Y,Y,N) = Y';

        rfCalcToggleClass(
            ['rfCalcVoteLine1', 'rfCalcVoteLine2'],
            'path-yes',
            true
        );
        rfCalcToggleClass(
            ['rfCalcVoteLine3'],
            'path-no',
            true
        );
    }

    const stage = document.getElementById('rfCalcStage');
    stage.classList.remove('calc-fade');
    void stage.offsetWidth;
    stage.classList.add('calc-fade');

    document.querySelectorAll('[data-rfcalc-chip]').forEach(chip => {
        chip.classList.toggle(
            'active',
            Number(chip.dataset.rfcalcChip) === rfCalcIndex
        );
    });
}

function rfCalcNext()
{
    if (rfCalcIndex < rfCalcSteps.length - 1) {
        rfCalcIndex++;
        rfCalcRender();
    }
}

function rfCalcPrev()
{
    if (rfCalcIndex > 0) {
        rfCalcIndex--;
        rfCalcRender();
    }
}

function rfCalcPlay()
{
    rfCalcPause();

    rfCalcTimer = setInterval(() => {
        if (rfCalcIndex >= rfCalcSteps.length - 1) {
            rfCalcPause();
            return;
        }

        rfCalcNext();
    }, 1800);
}

function rfCalcPause()
{
    if (rfCalcTimer !== null) {
        clearInterval(rfCalcTimer);
        rfCalcTimer = null;
    }
}

function rfCalcReset()
{
    rfCalcPause();
    rfCalcIndex = 0;
    rfCalcRender();
}

rfCalcRender();

</script>