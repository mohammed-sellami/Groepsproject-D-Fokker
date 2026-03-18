// ─────────────────────────────────────────
//  VIEW — DOM-rendering & UI updates
//  Geen businesslogica hier, alleen weergave
// ─────────────────────────────────────────

const View = (() => {

    // ── DOM-referenties (één keer ophalen) ──
    const els = {
        modelSelect:    document.getElementById('modelSelect'),
        sizeGrid:       document.getElementById('sizeGrid'),
        totalPrice:     document.getElementById('totalPrice'),
        summaryMeta:    document.getElementById('summaryMeta'),
        aantalInput:    document.getElementById('aantalInput'),
        hoofdbordGroup: document.getElementById('hoofdbordGroup'),
        hbSub:          document.getElementById('hbSub'),
        toast:          document.getElementById('toast'),
        toastBody:      document.getElementById('toastBody'),
    };

    // ── Helpers ──

    // Veilig tekst instellen (geen innerHTML met variabelen → geen XSS)
    function tekst(el, waarde) {
        el.textContent = waarde;
    }

    // Maak een DOM-element aan (veiliger dan innerHTML +=)
    function maakEl(tag, attrs = {}, ...kinderen) {
        const el = document.createElement(tag);
        Object.entries(attrs).forEach(([k, v]) => {
            if (k === 'class')    el.className = v;
            else if (k === 'data') Object.entries(v).forEach(([dk, dv]) => el.dataset[dk] = dv);
            else el.setAttribute(k, v);
        });
        kinderen.forEach(kind => {
            if (typeof kind === 'string') el.appendChild(document.createTextNode(kind));
            else if (kind) el.appendChild(kind);
        });
        return el;
    }

    // ── Renderfuncties ──

    function renderMaten(maten, huidigeMaat) {
        els.sizeGrid.innerHTML = '';

        maten.forEach(([naam]) => {
            const dot  = maakEl('div', { class: 'size-dot' });
            const span = maakEl('span', { class: 'size-name' }, naam);

            // Verborgen radio (voor semantiek)
            const radio = maakEl('input', { type: 'radio', name: 'size', value: naam });

            const optie = maakEl('div',
                {
                    class: `size-option${naam === huidigeMaat ? ' active' : ''}`,
                    data:  { maat: naam },
                },
                radio, dot, span
            );

            els.sizeGrid.appendChild(optie);
        });
    }

    function updatePrijs(bedragTekst) {
        tekst(els.totalPrice, bedragTekst);
    }

    function updateSamenvatting(state, hbTekst) {
        tekst(
            els.summaryMeta,
            `${state.aantal}× ${state.model} — ${state.maat} — ${state.kleur}${hbTekst}`
        );
    }

    function updateAantalInput(aantal) {
        els.aantalInput.value = aantal;
    }

    function toonHoofdbordSub(zichtbaar) {
        if (zichtbaar) {
            els.hbSub.classList.add('visible');
        } else {
            els.hbSub.classList.remove('visible');
        }
    }

    // Actieve klasse op maat-opties bijwerken
    function setActieveMaat(maat) {
        els.sizeGrid.querySelectorAll('.size-option').forEach(el => {
            el.classList.toggle('active', el.dataset.maat === maat);
        });
    }

    // Actieve klasse op kleur-kaarten bijwerken
    function setActieveKleur(kleur) {
        document.querySelectorAll('.color-card').forEach(el => {
            el.classList.toggle('active', el.dataset.kleur === kleur);
        });
    }

    // Actieve klasse op hoofdbord-opties bijwerken
    function setActieveHoofdbordKeuze(keuze) {
        document.querySelectorAll('#hoofdbordGroup > .radio-option').forEach(el => {
            el.classList.toggle('active', el.dataset.waarde === keuze);
        });
    }

    function setActieveHoofdbordType(type) {
        document.querySelectorAll('#hbSub .radio-option').forEach(el => {
            el.classList.toggle('active', el.dataset.waarde === type);
        });
    }

    // Toast tonen
    function toonToast(model, kleur, maat, aantal, hbTekst, totaalTekst) {
        // Veilig opbouwen via DOM (geen innerHTML met variabelen)
        els.toastBody.innerHTML = '';

        const regel1 = document.createTextNode(`${model} · ${kleur} · ${maat}`);
        const br1    = document.createElement('br');
        const regel2 = document.createTextNode(`Aantal: ${aantal} · Hoofdbord: ${hbTekst}`);
        const br2    = document.createElement('br');
        const sterk  = document.createElement('strong');
        sterk.textContent = `€ ${totaalTekst}`;

        els.toastBody.append(regel1, br1, regel2, br2, sterk);

        els.toast.classList.add('show');
        setTimeout(() => els.toast.classList.remove('show'), 4000);
    }

    // ── Event-handlers registreren (Controller koppelt ze) ──

    function onModelChange(handler) {
        els.modelSelect.addEventListener('change', () => handler(els.modelSelect.value));
    }

    function onMaatKlik(handler) {
        els.sizeGrid.addEventListener('click', e => {
            const optie = e.target.closest('.size-option');
            if (optie) handler(optie.dataset.maat);
        });
    }

    function onAantalInput(handler) {
        els.aantalInput.addEventListener('input', () => handler(els.aantalInput.value));
    }

    function onAantalKnop(handler) {
        document.querySelectorAll('.antal-btn').forEach(btn => {
            btn.addEventListener('click', () => handler(btn.dataset.delta));
        });
    }

    function onKleurKlik(handler) {
        document.querySelectorAll('.color-card').forEach(card => {
            card.addEventListener('click', () => handler(card.dataset.kleur));
        });
    }

    function onHoofdbordKeuze(handler) {
        document.querySelectorAll('#hoofdbordGroup > .radio-option').forEach(opt => {
            opt.addEventListener('click', () => handler(opt.dataset.waarde));
        });
    }

    function onHoofdbordType(handler) {
        document.querySelectorAll('#hbSub .radio-option').forEach(opt => {
            opt.addEventListener('click', () => handler(opt.dataset.waarde));
        });
    }

    function onOpslaan(handler) {
        document.getElementById('btnOpslaan').addEventListener('click', handler);
    }

    // ── Publieke API ──
    return {
        renderMaten,
        updatePrijs,
        updateSamenvatting,
        updateAantalInput,
        toonHoofdbordSub,
        setActieveMaat,
        setActieveKleur,
        setActieveHoofdbordKeuze,
        setActieveHoofdbordType,
        toonToast,
        onModelChange,
        onMaatKlik,
        onAantalInput,
        onAantalKnop,
        onKleurKlik,
        onHoofdbordKeuze,
        onHoofdbordType,
        onOpslaan,
    };

})();
