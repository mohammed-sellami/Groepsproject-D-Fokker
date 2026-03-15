/**
 * SERVICE – ApiService.js
 *
 * Verantwoordelijkheid:
 *   - Verzorgt ALLE communicatie met de externe Gemini API.
 *   - Weet niets van de DOM, de state of de Controller.
 *   - Valt onder de Model-laag (data ophalen).
 */

const ApiService = (() => {

    const API_KEY  = 'AIzaSyCuXFvdcP15hqJ0OhdRX8B5I004DBGdMIA';
    const BASE_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${API_KEY}`;

    // ── Privé helperfunctie: stuur één prompt naar de API ────────
    async function _post(promptText) {
        const response = await fetch(BASE_URL, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body   : JSON.stringify({
                contents: [{ parts: [{ text: promptText }] }]
            })
        });

        if (!response.ok) {
            const err = await response.json();
            throw new Error(err?.error?.message || `API fout: ${response.status}`);
        }

        const data = await response.json();
        return String(data.candidates?.[0]?.content?.parts?.[0]?.text || '');
    }

    // ── Publieke API-methoden ────────────────────────────────────
    return {

        /**
         * Vraag 10 subtopics op voor een gegeven onderwerp.
         * @param   {string}  topic
         * @returns {Promise<string>} ruwe tekst met subtopics (newline-gescheiden)
         */
        fetchSubtopics: (topic) =>
            _post(
                `Genereer precies 10 belangrijke onderwerpen over "${topic}". ` +
                `Geef ALLEEN de onderwerpen, elk op een nieuwe regel, zonder nummering of extra tekst.`
            ),

        /**
         * Genereer één blogartikel.
         * @param   {string}  subtopic
         * @param   {string}  topic
         * @param   {number}  wordCount
         * @param   {string}  lengthInstruction
         * @returns {Promise<string>} de blogtekst
         */
        fetchBlog: (subtopic, topic, wordCount, lengthInstruction) =>
            _post(
                `Schrijf een blogartikel van ongeveer ${wordCount} woorden over "${subtopic}" ` +
                `in de context van "${topic}".\n\n` +
                `Het artikel moet bevatten:\n${lengthInstruction}\n\n` +
                `Schrijf in het Nederlands, informatief en toegankelijk.`
            )
    };

})();
