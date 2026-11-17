(function () {
  'use strict';
  var D = window.mbData;
  var root = document.getElementById('mb-app');
  if (!D || !root) return;

  D.menus = window.mbMenus || D.menus || {};
  var values = D.values;
  var feats = D.features;
  var byId = {};
  feats.forEach(function (f) { byId[f.id] = f; });
  var layer = document.createElement('div');
  layer.className = 'mb-layer';
  var tipbox = document.createElement('div');
  tipbox.className = 'mb-tipbox';
  tipbox.setAttribute('role', 'tooltip');
  tipbox.hidden = true;
  layer.appendChild(tipbox);
  var route = 'overview';
  var query = '';

  /* ───── ابزارها ───── */
  var ICONS = {
    grid: '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
    eye: '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
    message: '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
    layout: '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
    cart: '<circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/><path d="M2 3h3l2.7 12.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"/>',
    puzzle: '<path d="M10 3h4v3a2 2 0 1 0 4 0V3h3v7h-3a2 2 0 1 0 0 4h3v7H3V3z"/>',
    sparkles: '<path d="M12 3l1.8 4.7L18.5 9.5l-4.7 1.8L12 16l-1.8-4.7L5.5 9.5l4.7-1.8z"/><path d="M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8z"/>',
    type: '<path d="M4 7V4h16v3M9 20h6M12 4v16"/>',
    trash: '<path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/>',
    shield: '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/>',
    globe: '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/>',
    menu: '<path d="M4 6h16M4 12h16M4 18h10"/>',
    search: '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
    sun: '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
    moon: '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
    alert: '<path d="M12 3l10 18H2z"/><path d="M12 10v5M12 18h.01"/>',
    reset: '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/>',
    wand: '<path d="M15 4l5 5L9 20l-5-5z"/><path d="M13 6l5 5"/>',
    shieldlogo: '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/>'
  };
  function icon(n) {
    return '<svg viewBox="0 0 24 24" aria-hidden="true">' + (ICONS[n] || '') + '</svg>';
  }
  function esc(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }
  function fa(n) { return String(n).replace(/\d/g, function (d) { return '۰۱۲۳۴۵۶۷۸۹'[d]; }); }
  function isOn(f) {
    var v = values[f.id];
    if (f.type === 'select') return v !== f['default'];
    if (f.type === 'checks' || f.type === 'tree') return Array.isArray(v) && v.length > 0;
    if (f.type === 'text' || f.type === 'textarea') return String(v || '') !== '';
    if (f.type === 'action') return false;
    return String(v) === '1';
  }
  var BIG = { select: 1, checks: 1, textarea: 1, tree: 1, action: 1, text: 1 };
  function isWide(f) {
    if (BIG[f.type]) return true;
    return (f.fields || []).some(function (x) { return x.type === 'textarea' || x.type === 'checks'; });
  }
  function catOf(id) {
    var found = null;
    D.groups.forEach(function (g) { g.items.forEach(function (c) { if (c.id === id) found = c; }); });
    return found;
  }
  function activeIn(cat) {
    return feats.filter(function (f) { return f.cat === cat && isOn(f); }).length;
  }
  function totalIn(cat) {
    return feats.filter(function (f) { return f.cat === cat; }).length;
  }

  /* ───── ارتباط با سرور ───── */
  function post(action, extra) {
    var fd = new FormData();
    fd.append('action', action);
    fd.append('nonce', D.nonce);
    Object.keys(extra || {}).forEach(function (k) { fd.append(k, extra[k]); });
    return fetch(D.ajax, { method: 'POST', credentials: 'same-origin', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (j) {
        if (!j || !j.success) throw new Error('fail');
        return j.data;
      });
  }
  var toastTimer;
  function toast(msg, err) {
    var old = layer.querySelector('.mb-toast');
    if (old) old.remove();
    var t = document.createElement('div');
    t.className = 'mb-toast' + (err ? ' err' : '');
    t.setAttribute('role', 'status');
    t.textContent = msg;
    layer.appendChild(t);
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { t.remove(); }, 2200);
  }
  function save(id, val, done) {
    var prev = values[id];
    values[id] = val;
    post('mb_save', { key: id, value: Array.isArray(val) ? JSON.stringify(val) : val }).then(function (d) {
      values = d.values;
      toast('ذخیره شد');
      if (done) done(true);
      refreshChrome();
    }).catch(function () {
      values[id] = prev;
      toast('ذخیره نشد. دوباره تلاش کنید.', true);
      if (done) done(false);
      render();
    });
  }

  /* ───── پنجره‌ی تأیید ───── */
  function confirmBox(title, lines, okText, onOk, danger) {
    var bg = document.createElement('div');
    bg.className = 'mb-modal-bg';
    var items = lines.map(function (l) { return '<li>' + esc(l) + '</li>'; }).join('');
    bg.innerHTML = '<div class="mb-modal" role="dialog" aria-modal="true" aria-label="' + esc(title) + '">' +
      '<h3>' + icon('alert') + esc(title) + '</h3><ul>' + items + '</ul>' +
      '<div class="mb-modal-f"><button class="mb-btn ' + (danger ? 'danger' : 'primary') + '" data-ok>' + esc(okText) + '</button>' +
      '<button class="mb-btn" data-cancel>انصراف</button></div></div>';
    function close() { bg.remove(); document.removeEventListener('keydown', onKey); }
    function onKey(e) { if (e.key === 'Escape') close(); }
    bg.addEventListener('click', function (e) { if (e.target === bg) close(); });
    bg.querySelector('[data-cancel]').addEventListener('click', close);
    bg.querySelector('[data-ok]').addEventListener('click', function () { close(); onOk(); });
    document.addEventListener('keydown', onKey);
    layer.appendChild(bg);
    bg.querySelector('[data-cancel]').focus();
  }

  /* پیام‌های هشدار و هم‌پوشانی قبل از روشن‌کردن یک امکان */
  function warnings(f) {
    var out = [];
    if (f.warn) out.push(f.warn);
    feats.forEach(function (o) {
      if (o.id === f.id || !isOn(o)) return;
      var a = (f.overlaps || []).indexOf(o.id) > -1;
      var b = (o.overlaps || []).indexOf(f.id) > -1;
      if (a || b) out.push('با «' + o.title + '» که فعال است هم‌پوشانی دارد؛ لازم نیست هر دو را روشن نگه دارید.');
    });
    return out;
  }

  /* ───── ساخت ردیف‌ها ───── */
  function tipHtml(f) {
    return '<span class="mb-tip" tabindex="0" role="note" aria-label="' + esc(f.tip) + '" data-tip="' + esc(f.tip) + '">?</span>';
  }
  function badgeHtml(f) {
    if (f.badge === 'rec') return '<span class="mb-badge rec">پیشنهادی</span>';
    if (f.badge === 'caution') return '<span class="mb-badge caution">نیازمند بررسی</span>';
    return '';
  }
  function checksHtml(f, id, opts) {
    var cur = values[id] || [];
    return '<div class="mb-checks">' + Object.keys(opts).map(function (k) {
      return '<label class="mb-chk"><input type="checkbox" data-chk="' + id + '" value="' + esc(k) + '"' + (cur.indexOf(k) > -1 ? ' checked' : '') + '><span>' + esc(opts[k]) + '</span></label>';
    }).join('') + '</div>';
  }
  function taHtml(f, fld) {
    var id = fld.id, presets = fld.presets || [];
    var pr = presets.length ? '<div class="mb-presets"><span class="mb-unit">افزودن سریع:</span>' + presets.map(function (p, i) {
      return '<button type="button" class="mb-btn sm" data-preset="' + id + '" data-pi="' + i + '">' + esc(p.label) + '</button>';
    }).join('') + '</div>' : '';
    return pr + '<textarea class="mb-ta" dir="ltr" rows="' + (fld.rows || 5) + '" data-ta="' + id + '" placeholder="' + esc(fld.placeholder || '') + '" aria-label="' + esc(f.title) + '">' + esc(values[id] || '') + '</textarea>';
  }
  function treeHtml(f) {
    var list = (D.menus && D.menus[f.source]) || [];
    var cur = values[f.id] || [];
    if (!list.length) return '<div class="mb-empty">فهرستی پیدا نشد. یک بار این صفحه را دوباره باز کنید.</div>';
    return '<div class="mb-tree" data-tree="' + f.id + '">' + list.map(function (m) {
      var kids = (m.children || []).map(function (c) {
        return '<label class="mb-chk mb-sub"><input type="checkbox" data-tchk="' + f.id + '" value="' + esc(c.key) + '"' + (cur.indexOf(c.key) > -1 ? ' checked' : '') + '><span>' + esc(c.title) + '</span></label>';
      }).join('');
      return '<div class="mb-tree-g"><label class="mb-chk mb-top"><input type="checkbox" data-tchk="' + f.id + '" value="' + esc(m.key) + '"' + (cur.indexOf(m.key) > -1 ? ' checked' : '') + '><span>' + esc(m.title) + '</span></label>' + kids + '</div>';
    }).join('') + '</div>';
  }
  function fieldHtml(f, fld) {
    var hidden = fld.show_if && values[fld.show_if[0]] !== fld.show_if[1];
    var show = f.type === 'toggle' ? isOn(f) : true;
    var h = (hidden || !show) ? ' hidden' : '';
    if (fld.type === 'text') {
      return '<label class="mb-field" data-field="' + fld.id + '"' + h + '><input class="mb-txt" type="text" value="' + esc(values[fld.id] || '') + '" placeholder="' + esc(fld.placeholder || '') + '" data-txt="' + fld.id + '" aria-label="' + esc(f.title) + '"></label>';
    }
    return '<label class="mb-field" data-field="' + fld.id + '"' + h + '>' +
      '<input class="mb-num" type="number" min="' + fld.min + '" max="' + fld.max + '" value="' + esc(values[fld.id]) + '" data-num="' + fld.id + '" aria-label="' + esc(f.title) + '">' +
      '<span class="mb-unit">' + esc(fld.unit || '') + '</span></label>';
  }
  function extraHtml(f) {
    var out = '';
    if (f.type === 'checks') out += checksHtml(f, f.id, f.options || {});
    if (f.type === 'textarea') out += taHtml(f, f);
    if (f.type === 'tree') out += treeHtml(f);
    if (f.type === 'action') out += '<div class="mb-act-res" data-res="' + f.id + '"></div>';
    (f.fields || []).forEach(function (fld) {
      if (fld.type === 'textarea' && (f.type !== 'toggle' || isOn(f))) out += taHtml(f, fld);
      if (fld.type === 'checks' && (f.type !== 'toggle' || isOn(f))) out += checksHtml(f, fld.id, fld.options || {});
    });
    return out ? '<div class="mb-extra">' + out + '</div>' : '';
  }
  function rowHtml(f, showCat) {
    var wide = isWide(f);
    var disabled = f.available === false;
    var ctl = '';
    if (f.type === 'select') {
      var opts = Object.keys(f.options).map(function (k) {
        return '<option value="' + esc(k) + '"' + (values[f.id] === k ? ' selected' : '') + '>' + esc(f.options[k]) + '</option>';
      }).join('');
      ctl += '<select class="mb-sel" data-sel="' + f.id + '" aria-label="' + esc(f.title) + '"' + (disabled ? ' disabled' : '') + '>' + opts + '</select>';
    } else if (f.type === 'text') {
      ctl += '<input class="mb-txt" type="text" value="' + esc(values[f.id] || '') + '" placeholder="' + esc(f.placeholder || '') + '" data-txt="' + f.id + '" aria-label="' + esc(f.title) + '"' + (disabled ? ' disabled' : '') + '>';
    } else if (f.type === 'action') {
      ctl += '<button type="button" class="mb-btn primary sm" data-act="' + f.id + '"' + (disabled ? ' disabled' : '') + '>' + esc(f.button || 'اجرا') + '</button>';
    } else if (f.type === 'checks' || f.type === 'textarea' || f.type === 'tree') {
      ctl += '';
    } else {
      ctl += '<button type="button" class="mb-switch" role="switch" aria-checked="' + (isOn(f) ? 'true' : 'false') + '" data-sw="' + f.id + '" aria-label="' + esc(f.title) + '"' + (disabled ? ' disabled' : '') + '></button>';
    }
    (f.fields || []).forEach(function (fld) { if (fld.type === 'number' || fld.type === 'text') ctl = fieldHtml(f, fld) + ctl; });
    var req = disabled ? '<span class="mb-req">نیازمند ' + esc(f.requires.label) + '</span>' : '';
    var cat = showCat ? '<span class="mb-res-c">' + esc((catOf(f.cat) || {}).title || '') + '</span>' : '';
    return '<div class="mb-row' + (wide ? ' mb-wide' : '') + (disabled ? ' is-off-plugin' : '') + '" data-row="' + f.id + '">' +
      '<div class="mb-row-t"><span class="mb-title">' + esc(f.title) + '</span>' + badgeHtml(f) + tipHtml(f) + req + cat + '</div>' +
      '<div class="mb-ctl">' + ctl + '</div>' + extraHtml(f) + '</div>';
  }

  function rowsHtml(items, showCat) {
    var out = '', col = 0;
    items.forEach(function (f) {
      var wide = isWide(f);
      if (wide && col === 1) { out += '<div class="mb-row mb-fill"></div>'; col = 0; }
      out += rowHtml(f, showCat);
      col = wide ? 0 : (col + 1) % 2;
    });
    if (col === 1) out += '<div class="mb-row mb-fill"></div>';
    return '<div class="mb-rows">' + out + '</div>';
  }

  /* ───── صفحه‌ها ───── */
  function sideHtml() {
    var nav = '<button class="mb-nav-i" data-go="overview"' + (route === 'overview' && !query ? ' aria-current="page"' : '') + '>' + icon('grid') + '<span class="mb-grow">نمای کلی</span></button>';
    D.groups.forEach(function (g) {
      nav += '<div class="mb-nav-g">' + esc(g.title) + '</div>';
      g.items.forEach(function (c) {
        if (c.planned) {
          nav += '<button class="mb-nav-i" disabled>' + icon(c.icon) + '<span class="mb-grow">' + esc(c.title) + '</span><span class="mb-soon">به‌زودی</span></button>';
        } else {
          var cur = route === c.id && !query ? ' aria-current="page"' : '';
          nav += '<button class="mb-nav-i" data-go="' + c.id + '"' + cur + '>' + icon(c.icon) + '<span class="mb-grow">' + esc(c.title) + '</span><span class="mb-count" data-cnt="' + c.id + '">' + fa(activeIn(c.id)) + '</span></button>';
        }
      });
    });
    return '<aside class="mb-side">' +
      '<div class="mb-brand"><span class="mb-logo">' + icon('shieldlogo') + '</span><span class="mb-brand-t">Disable Bloat Manager</span><span class="mb-ver">v' + esc(D.version) + '</span></div>' +
      '<div class="mb-search">' + icon('search') + '<input type="search" id="mb-q" placeholder="جستجوی تنظیمات" value="' + esc(query) + '" aria-label="جستجوی تنظیمات"><kbd>/</kbd></div>' +
      '<nav class="mb-nav" aria-label="بخش‌ها">' + nav + '</nav>' +
      '<div class="mb-side-foot"><button class="mb-icon-btn" id="mb-theme" aria-label="تغییر حالت روشن و تیره"></button></div></aside>';
  }

  function categoryHtml(id) {
    var c = catOf(id);
    var sections = [];
    feats.filter(function (f) { return f.cat === id; }).forEach(function (f) {
      var s = sections.filter(function (x) { return x.name === f.section; })[0];
      if (!s) { s = { name: f.section, items: [] }; sections.push(s); }
      s.items.push(f);
    });
    var html = '<div class="mb-head"><h1>' + esc(c.title) + '</h1><p>' + esc(c.desc || '') + '</p></div>';
    sections.forEach(function (s) {
      html += '<section class="mb-card"><div class="mb-card-h">' + esc(s.name) + '<small>' + fa(s.items.filter(isOn).length) + ' از ' + fa(s.items.length) + ' فعال</small></div>' +
        rowsHtml(s.items) + '</section>';
    });
    return html;
  }

  function searchHtml() {
    var q = query.trim().toLowerCase();
    var res = feats.filter(function (f) {
      return (f.title + ' ' + f.tip).toLowerCase().indexOf(q) > -1;
    });
    var html = '<div class="mb-head"><h1>نتیجه‌ی جستجو</h1><p>' + fa(res.length) + ' مورد برای «' + esc(query) + '» پیدا شد.</p></div>';
    html += '<section class="mb-card">' + (res.length ? rowsHtml(res, true) : '<div class="mb-empty">چیزی پیدا نشد. کلمه‌ی ساده‌تری بنویسید، مثلاً «فید» یا «دیدگاه».</div>') + '</section>';
    return html;
  }

  function overviewHtml() {
    var active = feats.filter(isOn);
    var caution = active.filter(function (f) { return f.badge === 'caution'; });
    var html = '<div class="mb-head"><h1>نمای کلی</h1><p>وضعیت امکان‌های فعال و اطلاعات سایت.</p></div>';
    html += '<div class="mb-stats">' +
      '<div class="mb-stat"><b>' + fa(active.length) + '</b><span>امکان فعال</span></div>' +
      '<div class="mb-stat"><b>' + fa(feats.length) + '</b><span>امکان موجود</span></div>' +
      '<div class="mb-stat warn"><b>' + fa(caution.length) + '</b><span>فعال و نیازمند بررسی</span></div></div>';

    var prog = '';
    D.groups.forEach(function (g) {
      g.items.forEach(function (c) {
        if (c.planned) return;
        var a = activeIn(c.id), t = totalIn(c.id);
        prog += '<div class="mb-prog"><span>' + esc(c.title) + '</span><span class="mb-bar"><i style="width:' + (t ? Math.round(a / t * 100) : 0) + '%"></i></span><small>' + a + '/' + t + '</small></div>';
      });
    });
    var cautionHtml = caution.length ? caution.map(function (f) {
      return '<div class="mb-row" style="min-height:42px"><div class="mb-row-t"><span class="mb-title">' + esc(f.title) + '</span>' + tipHtml(f) + '<span class="mb-res-c">' + esc((catOf(f.cat) || {}).title) + '</span></div>' +
        '<div class="mb-ctl"><button class="mb-btn sm" data-off="' + f.id + '">خاموش کن</button></div></div>';
    }).join('') : '<div class="mb-empty">هیچ امکان فعالی نیاز به بررسی ندارد.</div>';

    html += '<div class="mb-two">' +
      '<section class="mb-card"><div class="mb-card-h">فعال بر اساس بخش</div><div style="padding:8px 0">' + prog + '</div></section>' +
      '<section class="mb-card"><div class="mb-card-h">فعال و نیازمند بررسی<small>بعد از روشن‌کردن، سایت را یک بار ببینید</small></div>' + cautionHtml + '</section></div>';

    var s = D.site;
    html += '<div class="mb-two" style="margin-top:16px">' +
      '<section class="mb-card"><div class="mb-card-h">اطلاعات سایت</div>' +
      kv('نسخه‌ی وردپرس', s.wp) + kv('نسخه‌ی PHP', s.php) + kv('قالب فعال', s.theme) + kv('افزونه‌های فعال', fa(s.plugins)) +
      kv('ووکامرس', s.woo ? 'نصب و فعال' : 'نصب نیست') + kv('المنتور', s.elementor ? 'نصب و فعال' : 'نصب نیست') + '</section>' +
      '<section class="mb-card"><div class="mb-card-h">ابزارها</div><div class="mb-card-b">' +
      '<p class="mb-tool-d">«تنظیمات پیشنهادی» فقط امکان‌های کم‌خطر را روشن می‌کند و به بقیه دست نمی‌زند. «بازنشانی» همه‌ی گزینه‌ها را به حالت اولیه برمی‌گرداند.</p>' +
      '<div class="mb-tools"><button class="mb-btn primary" id="mb-rec">' + icon('wand') + 'تنظیمات پیشنهادی</button>' +
      '<button class="mb-btn danger" id="mb-reset">' + icon('reset') + 'بازنشانی تنظیمات</button></div></div></section></div>';
    return html;
  }
  function kv(k, v) { return '<div class="mb-kv"><span>' + esc(k) + '</span><b>' + esc(v) + '</b></div>'; }

  /* ───── رندر ───── */
  function theme() { return localStorage.getItem('mbTheme') === 'dark' ? 'dark' : 'light'; }
  function applyTheme() {
    var t = theme();
    root.setAttribute('data-theme', t);
    var b = document.getElementById('mb-theme');
    if (b) b.innerHTML = icon(t === 'dark' ? 'sun' : 'moon') + (t === 'dark' ? 'حالت روشن' : 'حالت تیره');
  }
  function mainHtml() {
    if (query.trim()) return searchHtml();
    if (route === 'overview') return overviewHtml();
    return categoryHtml(route);
  }
  function render() {
    var focusQ = document.activeElement && document.activeElement.id === 'mb-q';
    var pos = focusQ ? document.activeElement.selectionStart : 0;
    root.innerHTML = '<div class="mb-main"><div class="mb-main-in">' + mainHtml() + '</div></div>' + sideHtml();
    root.appendChild(layer);
    tipbox.hidden = true;
    applyTheme();
    if (focusQ) { var q = document.getElementById('mb-q'); q.focus(); q.setSelectionRange(pos, pos); }
  }
  /* بعد از ذخیره فقط شمارنده‌ها و سرتیتر کارت‌ها را تازه می‌کنیم تا جای صفحه نپرد */
  function refreshChrome() {
    document.querySelectorAll('[data-cnt]').forEach(function (n) { n.textContent = fa(activeIn(n.getAttribute('data-cnt'))); });
    if (route === 'overview' && !query.trim()) render();
  }
  function go(r) {
    route = r; query = '';
    history.replaceState(null, '', '#' + r);
    render();
    window.scrollTo(0, 0);
  }

  /* ───── رویدادها ───── */
  function changeFeature(f, newVal) {
    var turningOn = f.type === 'select' ? newVal !== f['default'] : newVal === 1;
    var msgs = turningOn ? warnings(f) : [];
    function commit() {
      save(f.id, newVal, function () { render(); });
    }
    if (msgs.length) confirmBox('قبل از فعال‌سازی', msgs, 'متوجه شدم، فعال شود', commit);
    else commit();
  }

  root.addEventListener('click', function (e) {
    var t = e.target.closest('button');
    if (!t) return;
    if (t.hasAttribute('data-go')) return go(t.getAttribute('data-go'));
    if (t.id === 'mb-theme') {
      localStorage.setItem('mbTheme', theme() === 'dark' ? 'light' : 'dark');
      return applyTheme();
    }
    if (t.hasAttribute('data-sw')) {
      var f = byId[t.getAttribute('data-sw')];
      var next = isOn(f) ? 0 : 1;
      return changeFeature(f, next);
    }
    if (t.hasAttribute('data-preset')) {
      var pid = t.getAttribute('data-preset');
      var pf = null;
      feats.forEach(function (x) { if (x.id === pid) pf = x; (x.fields || []).forEach(function (fl) { if (fl.id === pid) pf = fl; }); });
      var cur = String(values[pid] || '').split('\n').filter(Boolean);
      pf.presets[+t.getAttribute('data-pi')].items.forEach(function (h) { if (cur.indexOf(h) < 0) cur.push(h); });
      return save(pid, cur.join('\n'), function () { render(); });
    }
    if (t.hasAttribute('data-act')) {
      var af = byId[t.getAttribute('data-act')];
      var box = root.querySelector('[data-res="' + af.id + '"]');
      t.disabled = true;
      box.innerHTML = '<div class="mb-empty">در حال بررسی... ممکن است چند دقیقه طول بکشد.</div>';
      return post(af.action).then(function (d) {
        t.disabled = false;
        var items = d.items || [];
        box.innerHTML = items.length ? '<div class="mb-found">' + items.map(function (it) {
          return '<div class="mb-found-r"><span dir="ltr">' + esc(it.host) + '</span><small>' + fa(it.count) + ' بار</small><button type="button" class="mb-btn sm" data-add="' + esc(it.host) + '" data-to="' + af.target + '">افزودن به فهرست مسدود</button></div>';
        }).join('') + '</div>' : '<div class="mb-empty">دامنه‌ی خارجیِ تازه‌ای پیدا نشد.</div>';
      }).catch(function () { t.disabled = false; box.innerHTML = '<div class="mb-empty">بررسی انجام نشد. دوباره تلاش کنید.</div>'; });
    }
    if (t.hasAttribute('data-add')) {
      var to = t.getAttribute('data-to');
      var lst = String(values[to] || '').split('\n').filter(Boolean);
      var hh = t.getAttribute('data-add');
      if (lst.indexOf(hh) < 0) lst.push(hh);
      return save(to, lst.join('\n'), function () { t.closest('.mb-found-r').remove(); var ta = root.querySelector('[data-ta="' + to + '"]'); if (ta) ta.value = lst.join('\n'); });
    }
    if (t.hasAttribute('data-off')) {
      return save(t.getAttribute('data-off'), 0, function () { render(); });
    }
    if (t.id === 'mb-rec') {
      return confirmBox('تنظیمات پیشنهادی', ['امکان‌های کم‌خطر و پیشنهادی روشن می‌شوند.', 'گزینه‌هایی که الان روشن کرده‌اید خاموش نمی‌شوند.'], 'اعمال شود', function () {
        post('mb_recommended').then(function (d) { values = d.values; toast('تنظیمات پیشنهادی اعمال شد'); render(); })
          .catch(function () { toast('اعمال نشد. دوباره تلاش کنید.', true); });
      });
    }
    if (t.id === 'mb-reset') {
      return confirmBox('بازنشانی تنظیمات', ['همه‌ی گزینه‌ها به حالت اولیه برمی‌گردند و همه‌ی امکان‌ها خاموش می‌شوند.', 'این کار قابل بازگشت نیست.'], 'بازنشانی شود', function () {
        post('mb_reset').then(function (d) { values = d.values; toast('تنظیمات بازنشانی شد'); render(); })
          .catch(function () { toast('بازنشانی نشد. دوباره تلاش کنید.', true); });
      }, true);
    }
  });

  root.addEventListener('change', function (e) {
    var el = e.target;
    if (el.hasAttribute('data-chk')) {
      var cid = el.getAttribute('data-chk');
      var arr = [].slice.call(root.querySelectorAll('[data-chk="' + cid + '"]:checked')).map(function (x) { return x.value; });
      var cf = byId[cid];
      var was = (values[cid] || []).length > 0;
      if (cf && !was && arr.length && warnings(cf).length) {
        el.checked = false;
        return confirmBox('قبل از فعال‌سازی', warnings(cf), 'متوجه شدم، فعال شود', function () { save(cid, arr, function () { render(); }); });
      }
      return save(cid, arr);
    }
    if (el.hasAttribute('data-tchk')) {
      var tid = el.getAttribute('data-tchk');
      var t = [].slice.call(root.querySelectorAll('[data-tchk="' + tid + '"]:checked')).map(function (x) { return x.value; });
      return save(tid, t);
    }
    if (el.hasAttribute('data-txt')) return save(el.getAttribute('data-txt'), el.value);
    if (el.hasAttribute('data-ta')) return save(el.getAttribute('data-ta'), el.value, function () { render(); });
    if (el.hasAttribute('data-sel')) {
      var f = byId[el.getAttribute('data-sel')];
      var prev = values[f.id];
      var val = el.value;
      var msgs = val !== f['default'] ? warnings(f) : [];
      if (msgs.length) {
        el.value = prev;
        return confirmBox('قبل از فعال‌سازی', msgs, 'متوجه شدم، فعال شود', function () { save(f.id, val, function () { render(); }); });
      }
      return save(f.id, val, function () { render(); });
    }
    if (el.hasAttribute('data-num')) {
      var id = el.getAttribute('data-num');
      var n = parseInt(el.value, 10);
      var def = null;
      feats.forEach(function (x) { (x.fields || []).forEach(function (fl) { if (fl.id === id) def = fl; }); });
      if (isNaN(n)) n = def['default'];
      n = Math.max(def.min, Math.min(def.max, n));
      el.value = n;
      save(id, n);
    }
  });

  root.addEventListener('input', function (e) {
    if (e.target.id === 'mb-q') { query = e.target.value; render(); }
  });

  /* راهنما: بیرون از کارت‌ها نمایش داده می‌شود تا هیچ‌جا بریده نشود */
  function showTip(el) {
    tipbox.textContent = el.getAttribute('data-tip');
    tipbox.hidden = false;
    var r = el.getBoundingClientRect();
    var w = tipbox.offsetWidth, h = tipbox.offsetHeight;
    var left = Math.max(8, Math.min(r.right - w + 10, window.innerWidth - w - 8));
    var top = r.top - h - 9;
    if (top < 40) top = r.bottom + 9;
    tipbox.style.left = left + 'px';
    tipbox.style.top = top + 'px';
  }
  root.addEventListener('mouseover', function (e) {
    var tip = e.target.closest && e.target.closest('.mb-tip');
    if (tip) showTip(tip);
  });
  root.addEventListener('mouseout', function (e) {
    if (e.target.closest && e.target.closest('.mb-tip')) tipbox.hidden = true;
  });
  root.addEventListener('focusin', function (e) {
    var tip = e.target.closest && e.target.closest('.mb-tip');
    if (tip) showTip(tip);
  });
  root.addEventListener('focusout', function () { tipbox.hidden = true; });
  window.addEventListener('scroll', function () { tipbox.hidden = true; }, true);

  document.addEventListener('keydown', function (e) {
    var tag = (document.activeElement && document.activeElement.tagName) || '';
    if (e.key === '/' && !/INPUT|TEXTAREA|SELECT/.test(tag)) {
      e.preventDefault();
      var q = document.getElementById('mb-q');
      if (q) q.focus();
    }
  });

  var h = (location.hash || '').replace('#', '');
  if (h && catOf(h) && !catOf(h).planned) route = h;
  render();
})();
