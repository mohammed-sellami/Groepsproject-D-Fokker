/**
 * MODEL – BlogModel.js
 *
 * Verantwoordelijkheid:
 *   - Beheert de volledige applicatiestatus (state).
 *   - Bevat bedrijfslogica die niet aan de UI gebonden is.
 *   - Weet NIETS van de DOM of de Controller.
 */

const BlogModel = (() => {

    // ── Privé state ──────────────────────────────────────────────
    let _topic     = '';
    let _subtopics = [];
    let _blogs     = {};      // { subtopic: blogText }
    let _wordCount = 300;
    let _isLoading = false;

    // ── Constanten (blogopbouw per lengte) ───────────────────────
    const LENGTH_INSTRUCTIONS = {
        300: `- Een pakkende titel\n- Een korte inleiding (1-2 zinnen)\n- 2 beknopte paragrafen\n- Een korte conclusie\nHoud het totaal op ongeveer 300 woorden.`,
        500: `- Een pakkende titel\n- Een inleiding\n- 3 paragrafen met inhoudelijke informatie\n- Een afsluitende conclusie\nHoud het totaal op ongeveer 500 woorden.`,
        800: `- Een pakkende titel\n- Een uitgebreide inleiding\n- 4-5 paragrafen met tussenkopjes en diepgaande informatie\n- Praktische tips of voorbeelden\n- Een uitgebreide conclusie\nHoud het totaal op ongeveer 800 woorden.`
    };

    // ── Publieke interface ───────────────────────────────────────
    return {

        // Getters
        getTopic             : ()        => _topic,
        getSubtopics         : ()        => [..._subtopics],
        getBlogs             : ()        => ({ ..._blogs }),
        getWordCount         : ()        => _wordCount,
        isLoading            : ()        => _isLoading,
        getLengthInstruction : ()        => LENGTH_INSTRUCTIONS[_wordCount],

        // Setters / mutators
        setTopic      : (topic)  => { _topic     = topic; },
        setSubtopics  : (arr)    => { _subtopics = [...arr]; },
        setWordCount  : (n)      => { _wordCount  = n; },
        setLoading    : (bool)   => { _isLoading  = bool; },

        storeBlog     : (subtopic, text) => { _blogs[subtopic] = text; },

        reset         : () => {
            _subtopics = [];
            _blogs     = {};
        },

        // Bedrijfslogica: stel de downloadtekst samen
        generateDownloadText: () => {
            let content  = `BLOG SERIE: ${_topic.toUpperCase()}\n`;
            content += `Bloglengte: ~${_wordCount} woorden per blog\n`;
            content += `Gegenereerd op: ${new Date().toLocaleDateString('nl-NL')}\n`;
            content += `${'='.repeat(60)}\n\n`;

            _subtopics.forEach((subtopic, i) => {
                content += `\n\n${'#'.repeat(60)}\n`;
                content += `BLOG ${i + 1}: ${subtopic}\n`;
                content += `${'#'.repeat(60)}\n\n`;
                content += _blogs[subtopic] || 'Nog niet gegenereerd';
                content += '\n\n';
            });

            return content;
        }
    };

})();
