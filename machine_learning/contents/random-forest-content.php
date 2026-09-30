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
</script>