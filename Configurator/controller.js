// ─────────────────────────────────────────
//  CONTROLLER — koppelt Model en View
//  Verwerkt gebruikersacties, stuurt Model
//  aan en vraagt View om te hertekenen
// ─────────────────────────────────────────

const Controller = (() => {

    // ── Hulpfunctie: volledige UI hertekenen op basis van huidige state ──
    function refresh() {
        const state  = Model.getState();
        const totaal = Model.berekenTotaal();
        const bedrag = Model.formateerBedrag(totaal);

        // Samenvatting-tekst voor hoofdbord
        let hbTekst;
        if (state.hoofdbord === 'Ja' && state.hbType) {
            hbTekst = ` — Hoofdbord: ${state.hbType}`;
        } else if (state.hoofdbord === 'Ja') {
            hbTekst = ' — Hoofdbord (geen keuze)';
        } else {
            hbTekst = ' — Hoofdbord niet gekozen';
        }

        View.updatePrijs(bedrag);
        View.updateSamenvatting(state, hbTekst);
        View.updateAantalInput(state.aantal);
    }

    // ── Acties ──

    function modelGewijzigd(model) {
        Model.setModel(model);
        const state = Model.getState();
        View.renderMaten(Model.getMaten(model), state.maat);
        refresh();
    }

    function maatGekozen(maat) {
        Model.setMaat(maat);
        View.setActieveMaat(maat);
        refresh();
    }

    function kleurGekozen(kleur) {
        Model.setKleur(kleur);
        View.setActieveKleur(kleur);
        refresh();
    }

    function aantalGewijzigd(waarde) {
        Model.setAantal(waarde);
        refresh();
    }

    function aantalKnopGedrukt(delta) {
        const huidig = Model.getState().aantal;
        Model.setAantal(huidig + parseInt(delta));
        refresh();
    }

    function hoofdbordGekozen(keuze) {
        Model.setHoofdbord(keuze);
        View.setActieveHoofdbordKeuze(keuze);
        View.toonHoofdbordSub(keuze === 'Ja');
        refresh();
    }

    function hoofdbordTypeGekozen(type) {
        Model.setHoofdbordType(type);
        View.setActieveHoofdbordType(type);
        refresh();
    }

    function opgeslagen() {
        const state  = Model.getState();
        const totaal = Model.berekenTotaal();
        const bedrag = Model.formateerBedrag(totaal);
        const hbTekst = state.hoofdbord === 'Ja' && state.hbType
            ? `Ja — ${state.hbType}`
            : state.hoofdbord;

        View.toonToast(
            state.model,
            state.kleur,
            state.maat,
            state.aantal,
            hbTekst,
            bedrag
        );
    }

    // ── Initialisatie ──
    function init() {
        // Koppel alle View-events aan Controller-acties
        View.onModelChange(modelGewijzigd);
        View.onMaatKlik(maatGekozen);
        View.onKleurKlik(kleurGekozen);
        View.onAantalInput(aantalGewijzigd);
        View.onAantalKnop(aantalKnopGedrukt);
        View.onHoofdbordKeuze(hoofdbordGekozen);
        View.onHoofdbordType(hoofdbordTypeGekozen);
        View.onOpslaan(opgeslagen);

        // Eerste render
        const state = Model.getState();
        View.renderMaten(Model.getMaten(state.model), state.maat);
        refresh();
    }

    return { init };

})();

// ── App starten zodra de DOM klaar is ──
document.addEventListener('DOMContentLoaded', Controller.init);
