/* fabulous teaching – kleine Helfer */
(function () {
  'use strict';
  document.documentElement.classList.remove('no-js');

  // ---------- Menü auf dem Handy ----------
  var knopf = document.querySelector('.menue-knopf');
  var menue = document.getElementById('hauptmenue');
  if (knopf && menue) {
    var schliessen = function () {
      knopf.setAttribute('aria-expanded', 'false');
      menue.classList.remove('offen');
    };
    knopf.addEventListener('click', function () {
      var offen = knopf.getAttribute('aria-expanded') === 'true';
      knopf.setAttribute('aria-expanded', String(!offen));
      menue.classList.toggle('offen', !offen);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && menue.classList.contains('offen')) { schliessen(); knopf.focus(); }
    });
    window.matchMedia('(min-width: 901px)').addEventListener('change', function (m) { if (m.matches) schliessen(); });
  }

  // ---------- Schatten unter der Kopfzeile beim Scrollen ----------
  var kopf = document.querySelector('.kopf');
  if (kopf) {
    var pruefen = function () { kopf.classList.toggle('gescrollt', window.scrollY > 8); };
    window.addEventListener('scroll', pruefen, { passive: true });
    pruefen();
  }

  // ---------- Filter bei den Unterrichtsmaterialien ----------
  var raster = document.getElementById('raster');
  if (!raster) return;

  var karten = Array.prototype.slice.call(raster.querySelectorAll('.karte'));
  var fachKnoepfe = Array.prototype.slice.call(document.querySelectorAll('.fach-kachel'));
  var zyklusKnoepfe = Array.prototype.slice.call(document.querySelectorAll('.chip[data-zyklus]'));
  var suchfeld = document.getElementById('suchfeld');
  var zahl = document.getElementById('treffer-zahl');
  var leer = document.getElementById('leer');
  var zurueck = document.getElementById('filter-zurueck');

  var url = new URLSearchParams(window.location.search);
  var zustand = {
    fach: url.get('fach') || '',
    zyklus: url.get('zyklus') || '',
    suche: url.get('suche') || ''
  };
  if (suchfeld) suchfeld.value = zustand.suche;

  function anwenden(urlAktualisieren) {
    var begriffe = zustand.suche.toLowerCase().trim().split(/\s+/).filter(Boolean);
    var sichtbar = 0;

    karten.forEach(function (k) {
      var zyklen = (k.getAttribute('data-zyklen') || '').split(' ').filter(Boolean);
      var text = k.getAttribute('data-suche') || '';
      var passt =
        (!zustand.fach || k.getAttribute('data-fach') === zustand.fach) &&
        (!zustand.zyklus || zyklen.length === 0 || zyklen.indexOf(zustand.zyklus) !== -1) &&
        begriffe.every(function (b) { return text.indexOf(b) !== -1; });
      k.hidden = !passt;
      if (passt) sichtbar++;
    });

    fachKnoepfe.forEach(function (b) { b.setAttribute('aria-pressed', String(b.getAttribute('data-fach') === zustand.fach)); });
    zyklusKnoepfe.forEach(function (b) { b.setAttribute('aria-pressed', String(b.getAttribute('data-zyklus') === zustand.zyklus)); });
    if (zahl) zahl.textContent = sichtbar;
    if (zahl && zahl.nextSibling) zahl.nextSibling.textContent = sichtbar === 1 ? ' Material' : ' Materialien';
    if (leer) leer.hidden = sichtbar !== 0;

    if (urlAktualisieren) {
      var p = new URLSearchParams();
      if (zustand.fach) p.set('fach', zustand.fach);
      if (zustand.zyklus) p.set('zyklus', zustand.zyklus);
      if (zustand.suche.trim()) p.set('suche', zustand.suche.trim());
      var neu = window.location.pathname + (p.toString() ? '?' + p.toString() : '');
      window.history.replaceState(null, '', neu);
    }
  }

  fachKnoepfe.forEach(function (b) {
    b.addEventListener('click', function () { zustand.fach = b.getAttribute('data-fach'); anwenden(true); });
  });
  zyklusKnoepfe.forEach(function (b) {
    b.addEventListener('click', function () { zustand.zyklus = b.getAttribute('data-zyklus'); anwenden(true); });
  });
  if (suchfeld) {
    suchfeld.addEventListener('input', function () { zustand.suche = suchfeld.value; anwenden(true); });
  }
  if (zurueck) {
    zurueck.addEventListener('click', function () {
      zustand = { fach: '', zyklus: '', suche: '' };
      if (suchfeld) suchfeld.value = '';
      anwenden(true);
    });
  }

  anwenden(false);
})();
