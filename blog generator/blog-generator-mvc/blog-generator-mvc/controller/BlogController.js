/**
 * CONTROLLER – BlogController.js
 *
 * Verantwoordelijkheid:
 *   - Koppelt het Model aan de View.
 *   - Reageert op gebruikersacties (via View-events).
 *   - Roept ApiService aan, slaat data op in BlogModel en
 *     instrueert BlogView om de UI bij te werken.
 *   - Bevat de volledige applicatielogica / gebruikersstroom.
 */

const BlogController = (() => {

    // ── Privé: gecombineerde loading-helper ──────────────────────
    function _setLoading(bool) {
        BlogModel.setLoading(bool);
        BlogView.setLoading(bool);
    }

    // ── Handler: gebruiker klikt op Start ────────────────────────
    async function onStart() {
        const topic = BlogView.getTopicValue();
        if (!topic || BlogModel.isLoading()) return;

        console.log(topic);

        BlogModel.setTopic(topic);
        BlogModel.reset();

        BlogView.hideError();
        BlogView.clearBlogs();
        _setLoading(true);
        BlogView.showStatus('Genereer 10 belangrijke onderwerpen...');

        try {
            const rawText   = await ApiService.fetchSubtopics(topic);
            const subtopics = rawText
                .split('\n')
                .map(line => line.trim())
                .filter(line => line.length > 0)
                .slice(0, 10);
            if (subtopics.length === 0) throw new Error('Geen onderwerpen gegenereerd');

            BlogModel.setSubtopics(subtopics);
            BlogView.renderSubtopics(subtopics, BlogModel.getWordCount());
            BlogView.hideStatus();

        } catch (err) {
            BlogView.showError('Fout bij het genereren van onderwerpen: ' + err.message);
        } finally {
            _setLoading(false);
        }
    }

    // ── Handler: gebruiker kiest een bloglengte ──────────────────
    function onSelectLength(words) {
        if (BlogModel.isLoading()) return;
        BlogModel.setWordCount(words);
        BlogView.setActiveLengthBtn(words);
    }

    // ── Handler: gebruiker klikt op "Genereer alle blogs" ────────
    async function onGenerateAll() {
        _setLoading(true);
        BlogView.hideGenerateBtn();
        BlogView.clearBlogs();

        const subtopics = BlogModel.getSubtopics();
        for (let i = 0; i < subtopics.length; i++) {
            await _generateOneBlog(subtopics[i], i);
        }

        BlogView.showStatus('Alle blogs zijn klaar!');
        setTimeout(() => BlogView.hideStatus(), 3000);
        _setLoading(false);
        BlogView.showDownloadBtn();
    }

    // ── Privé: genereer één blog en update Model + View ──────────
    async function _generateOneBlog(subtopic, index) {
        const wordCount = BlogModel.getWordCount();

        BlogView.showStatus(`Blog ${index + 1}/10 wordt geschreven: ${subtopic}... (${wordCount} woorden)`);
        BlogView.setSubtopicStatus(index, '<span class="text-indigo-500 font-medium">⏳ Bezig...</span>');

        try {
            const blogText = await ApiService.fetchBlog(
                subtopic,
                BlogModel.getTopic(),
                wordCount,
                BlogModel.getLengthInstruction()
            );

            BlogModel.storeBlog(subtopic, blogText);
            BlogView.setSubtopicStatus(index, '<span class="text-green-600 font-medium">✓ Klaar</span>');
            BlogView.appendBlog(subtopic, blogText, wordCount);

        } catch (err) {
            const errMsg = `Fout bij het genereren: ${err.message}`;
            BlogModel.storeBlog(subtopic, errMsg);
            BlogView.setSubtopicStatus(index, '<span class="text-red-500 font-medium">✗ Fout</span>');
        }
    }

    // ── Handler: gebruiker klikt op "Download alle blogs" ────────
    function onDownload() {
        const content  = BlogModel.generateDownloadText();
        const topic    = BlogModel.getTopic();
        const words    = BlogModel.getWordCount();

        const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
        const url  = URL.createObjectURL(blob);
        const a    = document.createElement('a');
        a.href     = url;
        a.download = `blogs-${topic.toLowerCase().replace(/\s+/g, '-')}-${words}woorden.txt`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // ── Init: koppel alle View-events aan hun handlers ────────────
    function init() {
        BlogView.bindStart(onStart);
        BlogView.bindLengthBtns(onSelectLength);
        BlogView.bindGenerateAll(onGenerateAll);
        BlogView.bindDownload(onDownload);
    }

    return { init };

})();
