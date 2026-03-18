// ─────────────────────────────────────────
//  MODEL — data, state & prijsberekening
//  Geen DOM-toegang hier, alleen pure logica
// ─────────────────────────────────────────

const Model = (() => {

    // ── Productdata (zou later uit een database/API komen) ──
    const DATA = {
        Odense:  { base: 858,  sizes: [["70x200",0],["70x210",60],["70x220",120],["80x200",0],["80x210",60],["80x220",120],["90x200",0],["90x210",60],["90x220",120],["100x200",120],["100x210",200],["100x220",280],["120x200",280],["120x210",380],["120x220",490]] },
        Osted:   { base: 858,  sizes: [["70x200",0],["70x210",70],["70x220",140],["80x200",0],["80x210",70],["80x220",140],["90x200",0],["90x210",70],["90x220",140],["100x200",80],["100x210",160],["100x220",250],["120x200",280],["120x210",410],["120x220",530]] },
        Retro:   { base: 858,  sizes: [["70x200",0],["70x210",70],["70x220",150],["80x200",0],["80x210",70],["80x220",150],["90x200",0],["90x210",70],["90x220",150],["100x200",100],["100x210",190],["100x220",280],["120x200",270],["120x210",380],["120x220",500]] },
        Solida:  { base: 858,  sizes: [["70x200",0],["70x210",50],["70x220",110],["80x200",0],["80x210",50],["80x220",110],["90x200",0],["90x210",50],["90x220",110],["100x200",120],["100x210",190],["100x220",270],["120x200",270],["120x210",360],["120x220",460]] },
        Supra:   { base: 858,  sizes: [["70x200",0],["70x210",80],["70x220",160],["80x200",0],["80x210",80],["80x220",160],["90x200",-8],["90x210",160],["90x220",120],["100x200",80],["100x210",170],["100x220",260],["120x200",280],["120x210",380],["120x220",520]] },
        Escape:  { base: 1172, sizes: [["80x200",-314],["90x200",-314],["100x200",-164],["120x200",0]] },
        Fly:     { base: 858,  sizes: [["70x200",0],["70x210",64],["70x220",176],["80x200",0],["80x210",64],["80x220",176],["90x200",0],["90x210",64],["90x220",176],["100x200",84],["100x210",164],["100x220",250],["120x200",274],["120x210",380],["120x220",490]] },
    };

    const KLEUREN = ['Zwart', 'Grijs', 'Bruin'];

    const HOOFDBORDEN = [
        { naam: 'Capi',    prijs: 425 },
        { naam: 'Espirit', prijs: 300 },
        { naam: 'Fenya',   prijs: 500 },
    ];

    // ── Applicatiestatus ──
    let state = {
        model:     'Odense',
        kleur:     'Zwart',
        maat:      '70x200',
        aantal:    1,
        hoofdbord: 'Nee',
        hbType:    '',
        hbPrijs:   0,
    };

    // ── Getters ──
    function getModellen()    { return Object.keys(DATA); }
    function getKleuren()     { return KLEUREN; }
    function getHoofdborden() { return HOOFDBORDEN; }
    function getMaten(model)  { return DATA[model]?.sizes ?? []; }
    function getState()       { return { ...state }; } // kopie, niet referentie

    // ── Setters met validatie ──
    function setModel(model) {
        if (!DATA[model]) return;
        state.model = model;
        // Reset maat naar eerste beschikbare maat van het nieuwe model
        state.maat = DATA[model].sizes[0][0];
    }

    function setKleur(kleur) {
        if (!KLEUREN.includes(kleur)) return;
        state.kleur = kleur;
    }

    function setMaat(maat) {
        const geldig = DATA[state.model].sizes.some(s => s[0] === maat);
        if (!geldig) return;
        state.maat = maat;
    }

    function setAantal(n) {
        // Validatie: alleen 1–99 toegestaan
        state.aantal = Math.max(1, Math.min(99, parseInt(n) || 1));
    }

    function setHoofdbord(keuze) {
        if (!['Ja', 'Nee'].includes(keuze)) return;
        state.hoofdbord = keuze;
        if (keuze === 'Nee') {
            state.hbType  = '';
            state.hbPrijs = 0;
        }
    }

    function setHoofdbordType(naam) {
        const hb = HOOFDBORDEN.find(h => h.naam === naam);
        if (!hb) return;
        state.hbType  = hb.naam;
        state.hbPrijs = hb.prijs;
    }

    // ── Prijsberekening (pure functie, geen DOM) ──
    function getToeslag(model, maat) {
        const rij = DATA[model].sizes.find(s => s[0] === maat);
        return rij ? rij[1] : 0;
    }

    function berekenTotaal() {
        const basis    = DATA[state.model].base;
        const toeslag  = getToeslag(state.model, state.maat);
        return (basis + toeslag) * state.aantal + state.hbPrijs;
    }

    // ── Formattering (geen DOM, wel presentatielogica) ──
    function formateerBedrag(bedrag) {
        return bedrag.toLocaleString('nl-NL', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    // ── Publieke API ──
    return {
        getModellen,
        getKleuren,
        getHoofdborden,
        getMaten,
        getState,
        setModel,
        setKleur,
        setMaat,
        setAantal,
        setHoofdbord,
        setHoofdbordType,
        berekenTotaal,
        formateerBedrag,
    };

})();
