/* ----------------------------------------------------------------
   includes/js/main.js — AGX
---------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', function () {

  /* ----------------------------------------------------------------
     Copy buttons (Pix key): <button data-copy="..." data-copied-label="...">
  ---------------------------------------------------------------- */
  document.querySelectorAll('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var label = btn.textContent;
      copyText(btn.dataset.copy).then(function () {
        btn.textContent = btn.dataset.copiedLabel;
        setTimeout(function () { btn.textContent = label; }, 2000);
      });
    });
  });

  /* ----------------------------------------------------------------
     "Saiba mais" balloons: Bootstrap popovers (opt-in, so they must be
     created here). Trigger "hover focus" also opens them with a tap on
     phones and with Tab on the keyboard.
  ---------------------------------------------------------------- */
  document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function (el) {
    new bootstrap.Popover(el, { customClass: 'agx-popover' });
  });

  /* ----------------------------------------------------------------
     Transparency: filter document rows by type
  ---------------------------------------------------------------- */
  var filter = document.querySelector('[data-doc-filter]');
  if (filter) {
    var buttons = filter.querySelectorAll('button');
    var rows    = document.querySelectorAll('[data-doc-type]');

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var type = btn.dataset.type;
        buttons.forEach(function (b) {
          b.classList.toggle('active', b === btn);
          b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
        });
        rows.forEach(function (row) {
          row.hidden = type !== 'all' && row.dataset.docType !== type;
        });
      });
    });
  }
});

/* ----------------------------------------------------------------
   copyText(): copies to the clipboard. navigator.clipboard only works
   over HTTPS or localhost, so fall back to a hidden textarea.
---------------------------------------------------------------- */
function copyText(text) {
  if (navigator.clipboard && window.isSecureContext) {
    return navigator.clipboard.writeText(text);
  }
  var area = document.createElement('textarea');
  area.value = text;
  area.setAttribute('readonly', '');
  area.style.position = 'fixed';
  area.style.opacity = '0';
  document.body.appendChild(area);
  area.select();
  document.execCommand('copy');
  document.body.removeChild(area);
  return Promise.resolve();
}
