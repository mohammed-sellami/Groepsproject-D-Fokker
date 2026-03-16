/**
 * VIEW – BlogView.js
 *
 * Verantwoordelijkheid:
 *   - Leest invoer uit de DOM en toont uitvoer in de DOM.
 *   - Bevat GEEN applicatielogica en GEEN API-aanroepen.
 *   - Biedt bind-methoden zodat de Controller events kan koppelen.
 */

const BlogView = (() => {

    // ── Cache alle DOM-elementen eenmalig ────────────────────────
    const el = {
        topicInput          : document.getElementById('topicInput'),
        startBtn            : document.getElementById('startBtn'),
        errorBox            : document.getElementById('errorBox'),
        statusBox           : document.getElementById('statusBox'),
        statusText          : document.getElementById('statusText'),
        subtopicsContainer  : document.getElementById('subtopicsContainer'),
        subtopicsList       : document.getElementById('subtopicsList'),
        subtopicCount       : document.getElementById('subtopicCount'),
        generateAllBtn      : document.getElementById('generateAllBtn'),
        downloadBtn         : document.getElementById('downloadBtn'),
        blogsContainer      : document.getElementById('blogsContainer'),
        selectedLengthBadge : document.getElementById('selectedLengthBadge'),
        lengthBtns          : document.querySelectorAll('.length-btn')
    };

    // ── Publieke interface ───────────────────────────────────────
    return {

        // ── Invoer lezen ─────────────────────────────────────────
        getTopicValue: () => el.topicInput.value.trim(),

        // ── Fout- en statusmeldingen ─────────────────────────────
        showError  : (msg) => {
            el.errorBox.textContent = msg;
            el.errorBox.classList.remove('hidden');
        },
        hideError  : ()    => el.errorBox.classList.add('hidden'),

        showStatus : (msg) => {
            el.statusText.textContent = msg;
            el.statusBox.classList.remove('hidden');
        },
        hideStatus : ()    => el.statusBox.classList.add('hidden'),

        // ── Loading-blokkering ───────────────────────────────────
        setLoading: (loading) => {
            el.startBtn.disabled   = loading;
            el.topicInput.disabled = loading;
            el.startBtn.classList.toggle('bg-gray-400',        loading);
            el.startBtn.classList.toggle('cursor-not-allowed', loading);
            el.lengthBtns.forEach(b => (b.disabled = loading));
        },

        // ── Lengte-knoppen ───────────────────────────────────────
        setActiveLengthBtn: (words) => {
            el.lengthBtns.forEach(b => {
                b.classList.toggle('active', parseInt(b.dataset.words) === words);
            });
        },

        // ── Subtopics renderen ───────────────────────────────────
        renderSubtopics: (subtopics, wordCount) => {
            el.subtopicsList.innerHTML = '';

            subtopics.forEach((subtopic, i) => {
                const div = document.createElement('div');
                div.className = 'p-4 bg-gray-50 rounded-lg border border-gray-200';
                div.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 bg-indigo-100
                                         text-indigo-600 rounded-full font-semibold text-sm">
                                ${i + 1}
                            </span>
                            <span class="font-medium text-gray-700">${subtopic}</span>
                        </div>
                        <span id="status-${i}" class="text-sm"></span>
                    </div>`;
                el.subtopicsList.appendChild(div);
            });

            el.subtopicCount.textContent       = subtopics.length;
            el.selectedLengthBadge.textContent = `~${wordCount} woorden`;
            el.subtopicsContainer.classList.remove('hidden');
            el.generateAllBtn.classList.remove('hidden');
            el.downloadBtn.classList.add('hidden');
        },

        // ── Status per subtopic-rij ──────────────────────────────
        setSubtopicStatus: (index, html) => {
            const badge = document.getElementById(`status-${index}`);
            if (badge) badge.innerHTML = html;
        },

        // ── Blog toevoegen aan de lijst ──────────────────────────
        appendBlog: (subtopic, content, wordCount) => {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-lg shadow-lg p-8';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-4 pb-3 border-b">
                    <h3 class="text-2xl font-bold text-gray-800">${subtopic}</h3>
                    <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700
                                 rounded-full font-medium whitespace-nowrap">
                        ~${wordCount} woorden
                    </span>
                </div>
                <div class="prose max-w-none">
                    <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">${content}</div>
                </div>`;
            el.blogsContainer.appendChild(div);
        },

        // ── Hulpacties ───────────────────────────────────────────
        clearBlogs      : ()  => { el.blogsContainer.innerHTML = ''; },
        showDownloadBtn : ()  => el.downloadBtn.classList.remove('hidden'),
        hideGenerateBtn : ()  => el.generateAllBtn.classList.add('hidden'),

        // ── Event-bindings (aangeroepen vanuit Controller) ────────
        bindStart: (handler) => {
            el.startBtn.addEventListener('click', handler);
            el.topicInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && el.topicInput.value.trim()) handler();
            });
        },
        bindLengthBtns : (handler) => {
            el.lengthBtns.forEach(btn =>
                btn.addEventListener('click', () => handler(parseInt(btn.dataset.words)))
            );
        },
        bindGenerateAll : (handler) => el.generateAllBtn.addEventListener('click', handler),
        bindDownload    : (handler) => el.downloadBtn.addEventListener('click', handler)
    };

})();
