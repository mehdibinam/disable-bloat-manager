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
  var route = 'overview';
  var query = '';
  var ddOpen = {};
  var stepTimers = {};

  var layer = document.createElement('div');
  layer.className = 'mb-layer';
  var tipbox = document.createElement('div');
  tipbox.className = 'mb-tipbox';
  tipbox.setAttribute('role', 'tooltip');
  tipbox.hidden = true;
  layer.appendChild(tipbox);

  /* ───── ابزارها ───── */
  var ICONS = {
    grid: '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
    eye: '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
    message: '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
    layout: '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
    edit: '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
    widgets: '<rect x="3" y="3" width="8" height="8" rx="1"/><rect x="13" y="3" width="8" height="5" rx="1"/><rect x="13" y="10" width="8" height="11" rx="1"/><rect x="3" y="13" width="8" height="8" rx="1"/>',
    refresh: '<path d="M21 12a9 9 0 0 1-15.5 6.2L3 16"/><path d="M3 12A9 9 0 0 1 18.5 5.8L21 8"/><path d="M21 3v5h-5M3 21v-5h5"/>',
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
    chevron: '<path d="M6 9l6 6 6-6"/>',
    star: '<path d="M12 3l2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.2 6.4 20.2l1.1-6.2L3 9.6l6.2-.9z"/>',
    download: '<path d="M12 3v12M7 11l5 5 5-5M4 21h16"/>',
    upload: '<path d="M12 21V9M7 13l5-5 5 5M4 3h16"/>',
    plus: '<path d="M12 5v14M5 12h14"/>',
    cloud: '<path d="M20 16.5A4.5 4.5 0 0 0 17.5 8h-1.3A6 6 0 1 0 4 14.9"/><path d="M12 12v9M8 17l4 4 4-4"/>',
    image: '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.5"/><path d="M21 16l-5-5-8 8"/>',
    x: '<path d="M6 6l12 12M18 6L6 18"/>',
    chart: '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
    chat: '<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 9 9 0 0 1-3.6-.8L3 21l1.9-5.1A8.4 8.4 0 1 1 21 11.5z"/>',
    shieldlogo: '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/>'
  };
  function icon(n) { return '<svg viewBox="0 0 24 24" aria-hidden="true">' + (ICONS[n] || '') + '</svg>'; }
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
    if (f.type === 'learned') return false;
    return String(v) === '1';
  }
  function isWide(f) { return f.type === 'tree' || !!f.wide; }
  function catOf(id) {
    var found = null;
    D.groups.forEach(function (g) { g.items.forEach(function (c) { if (c.id === id) found = c; }); });
    return found;
  }
  var SYN = [
    ['رنگ', 'color', 'colour', 'colors', 'scheme'], ['فونت', 'font', 'fonts'], ['دیدگاه', 'کامنت', 'comment', 'comments'], ['ایموجی', 'emoji'],
    ['فید', 'feed', 'rss'], ['ورود', 'login', 'signin'], ['امنیت', 'security', 'secure'], ['فایروال', 'firewall'], ['ووکامرس', 'woocommerce', 'woo'],
    ['المنتور', 'elementor'], ['منو', 'menu', 'menus'], ['ابزارک', 'ویجت', 'widget', 'widgets'], ['بروزرسانی', 'بهروزرسانی', 'update', 'updates', 'upgrade'],
    ['هوش مصنوعی', 'ai', 'artificial'], ['سرعت', 'کارایی', 'speed', 'performance'], ['اسکریپت', 'script', 'javascript'], ['استایل', 'style', 'css'],
    ['اعلان', 'notice', 'notification'], ['دامنه', 'domain', 'host'], ['گوتنبرگ', 'بلوک', 'gutenberg', 'block'], ['پیشخوان', 'dashboard', 'admin'],
    ['نوار', 'bar', 'toolbar'], ['رمز', 'گذرواژه', 'password'], ['تصویر', 'رسانه', 'image', 'media'], ['ایمیل', 'email', 'mail'], ['کاربر', 'user', 'users'],
    ['خطا', 'error'], ['لوگو', 'logo'], ['جستجو', 'search']
  ];
  function norm(s) { return String(s).toLowerCase().replace(/ي/g, 'ی').replace(/ك/g, 'ک').replace(/[\u200c\u200f]/g, ''); }
  function matcher(q) {
    var groups = norm(q).split(/\s+/).filter(Boolean).map(function (w) {
      var g = null;
      SYN.forEach(function (grp) {
        if (g) return;
        var hit = grp.some(function (x) { x = norm(x); return x === w || (x.length > 2 && w.length > 2 && (x.indexOf(w) > -1 || w.indexOf(x) > -1)); });
        if (hit) g = grp.map(norm);
      });
      return g || [w];
    });
    return function (f) {
      var hay = norm(f.title + ' ' + f.tip + ' ' + (f.kw || '') + ' ' + f.id.replace(/_/g, ' '));
      return groups.every(function (g) { return g.some(function (t) { return hay.indexOf(t) > -1; }); });
    };
  }
  function activeIn(cat) { return feats.filter(function (f) { return f.cat === cat && isOn(f); }).length; }
  function totalIn(cat) { return feats.filter(function (f) { return f.cat === cat; }).length; }
  function findDef(id) {
    var r = null;
    feats.forEach(function (x) { if (x.id === id) r = x; (x.fields || []).forEach(function (fl) { if (fl.id === id) r = fl; }); });
    return r;
  }

  /* ───── ارتباط با سرور ───── */
  function post(action, extra) {
    var fd = new FormData();
    fd.append('action', action);
    fd.append('nonce', D.nonce);
    Object.keys(extra || {}).forEach(function (k) { fd.append(k, extra[k]); });
    return fetch(D.ajax, { method: 'POST', credentials: 'same-origin', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (j) { if (!j || !j.success) throw new Error('fail'); return j.data; });
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
    var items = lines.filter(Boolean).map(function (l) { return '<li>' + esc(l) + '</li>'; }).join('');
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

  /* پیام‌های هشدار، هم‌پوشانی و تداخل با افزونه‌های دیگر قبل از روشن‌کردن */
  function warnings(f) {
    var out = [];
    if (f.warn) out.push(f.warn);
    var ext = (D.external || {})[f.id];
    if (ext && ext.length) out.push('«' + ext.join('، ') + '» همین امکان را اجرا می‌کند؛ بهتر است فقط در یکی روشن باشد.');
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
    var h = '';
    if (f.badge === 'rec') h += '<span class="mb-badge rec">پیشنهادی</span>';
    if (f.badge === 'caution') h += '<span class="mb-badge caution">نیازمند بررسی</span>';
    if (f.badge === 'danger') h += '<span class="mb-badge danger">حساسیت بالا</span>';
    var ext = (D.external || {})[f.id];
    if (ext && ext.length) {
      h += '<span class="mb-badge ext" tabindex="0" data-tip="این امکان همزمان توسط ' + esc(ext.join('، ')) + ' هم اجرا می‌شود. بهتر است فقط در یکی از آن‌ها روشن باشد تا تداخل پیش نیاید.">در ' + esc(ext.join('، ')) + ' هم فعال است</span>';
    }
    return h;
  }
  function chip(attr, id, val, label, checked, cls) {
    return '<label class="mb-chip' + (cls ? ' ' + cls : '') + '"><input type="checkbox" ' + attr + '="' + esc(id) + '" value="' + esc(val) + '"' + (checked ? ' checked' : '') + '><span>' + esc(label) + '</span></label>';
  }
  function checksHtml(id, opts, dropdown, off) {
    var cur = values[id] || [];
    var dis = off ? ' disabled' : '';
    var chips = '<div class="mb-chips' + (off ? ' is-disabled' : '') + '">' + Object.keys(opts).map(function (k) {
      return chip('data-chk', id, k, opts[k], cur.indexOf(k) > -1).replace('<input ', '<input' + dis + ' ');
    }).join('') + '</div>';
    if (!dropdown) return chips;
    var open = !!ddOpen[id] && !off;
    var n = cur.length;
    return '<button type="button" class="mb-dd-b" data-dd="' + id + '" aria-expanded="' + open + '"' + dis + '><span>' + (n ? fa(n) + ' مورد انتخاب شده' : 'هیچ موردی انتخاب نشده') + '</span>' + icon('chevron') + '</button>' +
      (open ? '<div class="mb-dd-p">' + chips + '</div>' : '');
  }
  function taHtml(f, fld, off) {
    var id = fld.id, presets = fld.presets || [];
    var pbtn = presets.map(function (p, i) {
      return '<button type="button" class="mb-btn sm" data-preset="' + id + '" data-pi="' + i + '">' + esc(p.label) + '</button>';
    }).join('');
    var pr = '';
    if (presets.length || fld.clearable) {
      pr = '<div class="mb-presets">' + (presets.length ? '<span class="mb-unit">افزودن سریع:</span>' + pbtn : '') +
        (fld.clearable ? '<button type="button" class="mb-ibtn danger" data-clear="' + id + '" data-tip="پاک‌کردن فهرست" aria-label="پاک‌کردن فهرست">' + icon('trash') + '</button>' : '') + '</div>';
    }
    return pr + '<textarea class="mb-ta" dir="ltr" rows="' + (fld.rows || 5) + '" data-ta="' + id + '" placeholder="' + esc(fld.placeholder || '') + '" aria-label="' + esc(f.title) + '"' + (off ? ' disabled' : '') + '>' + esc(values[id] || '') + '</textarea>';
  }
  function treeHtml(f) {
    var list = (D.menus && D.menus[f.source]) || [];
    var cur = values[f.id] || [];
    if (!list.length) return '<div class="mb-empty">فهرستی پیدا نشد. یک بار این صفحه را دوباره باز کنید.</div>';
    return '<div class="mb-tree">' + list.map(function (m) {
      var kids = (m.children || []).map(function (c) { return chip('data-tchk', f.id, c.key, c.title, cur.indexOf(c.key) > -1, 'sm'); }).join('');
      var head = m.locked ? '<span class="mb-chip top locked" data-tip="این منو (تنظیمات وردپرس) قابل مخفی‌شدن نیست، چون صفحه‌ی همین افزونه داخل آن است."><span>' + esc(m.title) + ' (قفل)</span></span>' : chip('data-tchk', f.id, m.key, m.title, cur.indexOf(m.key) > -1, 'top');
      return '<div class="mb-tree-g">' + head + (kids ? '<div class="mb-chips">' + kids + '</div>' : '') + '</div>';
    }).join('') + '</div>';
  }
  function learnedHtml(f) {
    var list = D.learned || [];
    if (!list.length) return '<div class="mb-empty">هنوز دامنه‌ای شناسایی نشده. با بازدید از صفحه‌های سایت، این فهرست خودکار پر می‌شود.</div>';
    return '<div class="mb-found">' + list.map(function (it) {
      return '<div class="mb-found-r"><span dir="ltr">' + esc(it.host) + '</span><small>' + fa(it.count) + ' بار</small><button type="button" class="mb-block" data-add="' + esc(it.host) + '" data-to="' + f.target + '">مسدود کردن</button></div>';
    }).join('') + '</div>';
  }
  function fieldActive(f, fld) {
    if (fld.show_if) {
      var allowed = Array.isArray(fld.show_if[1]) ? fld.show_if[1] : [fld.show_if[1]];
      return allowed.indexOf(values[fld.show_if[0]]) > -1;
    }
    return f.type !== 'toggle' || isOn(f);
  }
  function fieldHtml(f, fld, off) {
    var dis = off ? ' disabled' : '';
    var cls = 'mb-fld' + (off ? ' is-disabled' : '');
    if (fld.type === 'text') {
      return '<label class="' + cls + ' wide"><input class="mb-txt" type="text" value="' + esc(values[fld.id] || '') + '" placeholder="' + esc(fld.placeholder || '') + '" data-txt="' + fld.id + '" aria-label="' + esc(f.title) + '"' + dis + '></label>';
    }
    if (fld.type === 'color') {
      return '<label class="' + cls + '"><input class="mb-clr" type="color" value="' + esc(values[fld.id] || fld['default']) + '" data-color="' + fld.id + '" aria-label="' + esc(fld.label || f.title) + '"' + dis + '><span class="mb-unit">' + esc(fld.label || '') + '</span></label>';
    }
    if (fld.type === 'image') {
      var u = values[fld.id] || '';
      return '<div class="' + cls + ' wide mb-img">' + (u ? '<img src="' + esc(u) + '" alt="">' : '<span class="mb-img-ph">' + icon('image') + '</span>') +
        '<button type="button" class="mb-btn sm" data-pick="' + fld.id + '"' + dis + '>' + icon('image') + (u ? 'تغییر تصویر' : 'انتخاب از رسانه‌ها') + '</button>' +
        (u ? '<button type="button" class="mb-ibtn danger" data-unpick="' + fld.id + '" data-tip="حذف تصویر" aria-label="حذف تصویر"' + dis + '>' + icon('x') + '</button>' : '') + '</div>';
    }
    return '<div class="' + cls + '"><span class="mb-step"><button type="button" class="mb-sb" data-step="1" data-for="' + fld.id + '" aria-label="افزایش"' + dis + '>+</button>' +
      '<input class="mb-num" type="number" min="' + fld.min + '" max="' + fld.max + '" value="' + esc(values[fld.id]) + '" data-num="' + fld.id + '" aria-label="' + esc(fld.unit || f.title) + '"' + dis + '>' +
      '<button type="button" class="mb-sb" data-step="-1" data-for="' + fld.id + '" aria-label="کاهش"' + dis + '>−</button></span>' + (fld.unit ? '<span class="mb-unit">' + esc(fld.unit) + '</span>' : '') + '</div>';
  }
  function noticesHtml() {
    var list = D.hiddenNotices || [];
    if (!list.length) return '';
    return '<div class="mb-hn"><div class="mb-hn-h"><span>اعلان‌های مخفی‌شده (' + fa(list.length) + ')</span><button type="button" class="mb-ibtn" data-unhide="all" data-tip="نمایش همه" aria-label="نمایش همه">' + icon('eye') + '</button></div>' +
      list.map(function (n) {
        return '<div class="mb-found-r"><span class="mb-hn-t">' + esc(n.text || 'اعلان (متنی ذخیره نشده است)') + '</span><button type="button" class="mb-ibtn" data-unhide="' + esc(n.hash) + '" data-tip="نمایش دوباره" aria-label="نمایش دوباره">' + icon('eye') + '</button></div>';
      }).join('') + '</div>';
  }
  /* زیرگزینه‌ها همیشه دیده می‌شوند، ولی تا وقتی امکان اصلی فعال نباشد غیرفعال‌اند */
  function extraHtml(f) {
    var out = '', inline = '';
    if (f.type === 'checks') out += checksHtml(f.id, f.options || {}, f.ui === 'dropdown', f.available === false);
    if (f.type === 'textarea') out += taHtml(f, f, false);
    if (f.type === 'tree') out += treeHtml(f);
    if (f.type === 'learned') out += learnedHtml(f);
    if (f.id === 'admin_notices') out += noticesHtml();
    (f.fields || []).forEach(function (fld) {
      var off = !fieldActive(f, fld) || f.available === false;
      if (fld.type === 'textarea') out += taHtml(f, fld, off);
      else if (fld.type === 'checks') out += checksHtml(fld.id, fld.options || {}, fld.ui === 'dropdown', off);
      else inline += fieldHtml(f, fld, off);
    });
    if (inline) out = '<div class="mb-fields">' + inline + '</div>' + out;
    return out ? '<div class="mb-extra">' + out + '</div>' : '';
  }
  function rowHtml(f, showCat, forceWide) {
    var disabled = f.available === false;
    var ctl = '';
    if (f.type === 'select') {
      var opts = Object.keys(f.options).map(function (k) {
        return '<option value="' + esc(k) + '"' + (values[f.id] === k ? ' selected' : '') + '>' + esc(f.options[k]) + '</option>';
      }).join('');
      ctl = '<select class="mb-sel" data-sel="' + f.id + '" aria-label="' + esc(f.title) + '"' + (disabled ? ' disabled' : '') + '>' + opts + '</select>';
    } else if (f.type === 'learned') {
      ctl = '<button type="button" class="mb-ibtn danger" data-learn-clear data-tip="' + esc(f.button || 'پاک‌کردن') + '" aria-label="' + esc(f.button || 'پاک‌کردن') + '">' + icon('trash') + '</button>';
    } else if (f.type === 'checks' || f.type === 'textarea' || f.type === 'tree' || f.type === 'text') {
      ctl = '';
    } else {
      ctl = '<button type="button" class="mb-switch" role="switch" aria-checked="' + (isOn(f) ? 'true' : 'false') + '" data-sw="' + f.id + '" aria-label="' + esc(f.title) + '"' + (disabled ? ' disabled' : '') + '></button>';
    }
    var req = disabled ? '<span class="mb-req">نیازمند ' + esc(f.requires.label) + '</span>' : '';
    var cat = showCat ? '<span class="mb-res-c">' + esc((catOf(f.cat) || {}).title || '') + '</span>' : '';
    return '<div class="mb-row' + (forceWide || isWide(f) ? ' mb-wide' : '') + (disabled ? ' is-off-plugin' : '') + '" data-row="' + f.id + '">' +
      '<div class="mb-row-t"><span class="mb-title">' + esc(f.title) + '</span>' + badgeHtml(f) + tipHtml(f) + req + cat + '</div>' +
      (ctl ? '<div class="mb-ctl">' + ctl + '</div>' : '') + extraHtml(f) + '</div>';
  }
  function isHeavy(f) {
    if (f.ui === 'dropdown') return false;
    return ['textarea', 'learned', 'checks', 'tree'].indexOf(f.type) > -1;
  }
  /* چیدمان: اول ردیف‌های ساده، بعد ردیف‌های سنگین (کادر متنی و فهرست)، تا کنار هم ارتفاع ناهمسان نیایند */
  function rowsHtml(items, showCat) {
    var light = [], heavy = [], wide = [], last = [], out = '';
    if (showCat) light = items;
    else items.forEach(function (f) { (f.last ? last : (isWide(f) ? wide : (isHeavy(f) ? heavy : light))).push(f); });
    light.forEach(function (f) { out += rowHtml(f, showCat); });
    if (light.length % 2) out += '<div class="mb-row mb-fill"></div>';
    heavy.forEach(function (f, i) { out += rowHtml(f, showCat, heavy.length % 2 === 1 && i === heavy.length - 1); });
    wide.forEach(function (f) { out += rowHtml(f, showCat, true); });
    last.forEach(function (f) { out += rowHtml(f, showCat, true); });
    return '<div class="mb-rows">' + out + '</div>';
  }

  /* ───── صفحه‌ها ───── */
  function sideHtml() {
    var nav = '<button class="mb-nav-i" data-go="overview"' + (route === 'overview' && !query ? ' aria-current="page"' : '') + '>' + icon('grid') + '<span class="mb-grow">نمای کلی</span></button>';
    D.groups.forEach(function (g) {
      if (g.title) nav += '<div class="mb-nav-g">' + esc(g.title) + '</div>';
      g.items.forEach(function (c) {
        var cur = route === c.id && !query ? ' aria-current="page"' : '';
        nav += '<button class="mb-nav-i" data-go="' + c.id + '"' + cur + '>' + icon(c.icon) + '<span class="mb-grow">' + esc(c.title) + '</span><span class="mb-count" data-cnt="' + c.id + '">' + fa(activeIn(c.id)) + '</span></button>';
      });
    });
    var star = D.repo
      ? '<a class="mb-star" href="' + esc(D.repo) + '" target="_blank" rel="noopener noreferrer">' + icon('star') + '<span>اگر این افزونه به کارتان آمد، در گیت‌هاب ستاره بدهید</span></a>'
      : '<span class="mb-star is-soon">' + icon('star') + '<span>اگر این افزونه به کارتان آمد، در گیت‌هاب ستاره بدهید <em>(به‌زودی)</em></span></span>';
    return '<aside class="mb-side">' +
      '<div class="mb-brand"><span class="mb-logo">' + icon('shieldlogo') + '</span><span class="mb-brand-t">مدیریت کارایی وردپرس</span></div>' +
      '<nav class="mb-nav" aria-label="بخش‌ها">' + nav + '</nav>' +
      '<div class="mb-side-foot">' + star + '<span class="mb-ver">نسخه‌ی ' + fa(D.version) + '</span></div></aside>';
  }
  function updateBtn() {
    var u = D.update;
    if (!u || !u.available || !u.url) return '';
    return '<a class="mb-icon-btn mb-upd" href="' + esc(u.url) + '" data-tip="نسخه‌ی جدید ' + esc(u.version) + ' آماده است. برای به‌روزرسانی کلیک کنید." aria-label="به‌روزرسانی به نسخه‌ی ' + esc(u.version) + '">' + icon('cloud') + '<i></i></a>';
  }
  function topHtml() {
    return '<header class="mb-top"><div class="mb-search">' + icon('search') + '<input type="search" id="mb-q" placeholder="جستجوی تنظیمات" value="' + esc(query) + '" aria-label="جستجوی تنظیمات"><kbd>/</kbd></div>' +
      '<div class="mb-top-r">' + updateBtn() + '<button class="mb-icon-btn" id="mb-theme" aria-label="تغییر حالت روشن و تیره"></button></div></header>';
  }
  function bulkables(id) {
    return feats.filter(function (f) { return f.cat === id && f.type === 'toggle' && f.bulk !== false && f.available !== false; });
  }
  function categoryHtml(id) {
    var c = catOf(id);
    var sections = [];
    feats.filter(function (f) { return f.cat === id; }).forEach(function (f) {
      var s = sections.filter(function (x) { return x.name === f.section; })[0];
      if (!s) { s = { name: f.section, items: [] }; sections.push(s); }
      s.items.push(f);
    });
    var bulk = bulkables(id);
    var allOn = bulk.length && bulk.every(isOn);
    var html = '<div class="mb-head"><div class="mb-head-r"><h1>' + esc(c.title) + '</h1><p>' + esc(c.desc || '') + '</p></div>' +
      (bulk.length ? '<button class="mb-btn" data-bulk="' + id + '">' + icon(allOn ? 'reset' : 'wand') + (allOn ? 'غیرفعال‌سازی همه' : 'فعال‌سازی همه') + '</button>' : '') + '</div>';
    sections.forEach(function (s) {
      html += '<section class="mb-card"><div class="mb-card-h">' + esc(s.name) + '<small>' + fa(s.items.filter(isOn).length) + ' از ' + fa(s.items.length) + ' فعال</small></div>' + rowsHtml(s.items) + '</section>';
    });
    return html;
  }
  function searchHtml() {
    var res = feats.filter(matcher(query));
    var html = '<div class="mb-head"><div class="mb-head-r"><h1>نتیجه‌ی جستجو</h1><p>' + fa(res.length) + ' مورد برای «' + esc(query) + '» پیدا شد.</p></div></div>';
    html += '<section class="mb-card">' + (res.length ? rowsHtml(res, true) : '<div class="mb-empty">چیزی پیدا نشد. کلمه‌ی ساده‌تری بنویسید، مثلاً «فید» یا «دیدگاه».</div>') + '</section>';
    return html;
  }
  function kv(k, v) { return '<div class="mb-kv"><span>' + esc(k) + '</span><b>' + esc(v) + '</b></div>'; }
  function overviewHtml() {
    var active = feats.filter(isOn);
    var html = '<div class="mb-head"><div class="mb-head-r"><h1>نمای کلی</h1><p>وضعیت امکان‌های فعال و اطلاعات سایت.</p></div></div>';
    html += '<div class="mb-stats">' +
      '<div class="mb-stat"><b>' + fa(active.length) + '</b><span>امکان فعال</span></div>' +
      '<div class="mb-stat"><b>' + fa(feats.length) + '</b><span>امکان موجود</span></div>' +
      '<div class="mb-stat"><b>' + fa(Math.round(active.length / feats.length * 100)) + '٪</b><span>درصد فعال‌سازی</span></div></div>';
    var prog = '';
    D.groups.forEach(function (g) {
      g.items.forEach(function (c) {
        var a = activeIn(c.id), t = totalIn(c.id);
        prog += '<div class="mb-prog"><span>' + esc(c.title) + '</span><span class="mb-bar"><i style="width:' + (t ? Math.round(a / t * 100) : 0) + '%"></i></span><small>' + a + '/' + t + '</small></div>';
      });
    });
    var issues = D.repo
      ? '<a class="mb-btn primary" href="' + esc(D.repo.replace(/\/$/, '')) + '/issues/new" target="_blank" rel="noopener noreferrer">' + icon('chat') + 'ثبت گزارش در گیت‌هاب</a>'
      : '<button class="mb-btn" disabled>' + icon('chat') + 'ثبت گزارش (به‌زودی)</button>';
    var st = D.stats || { totals: {}, days: [] };
    var T = st.totals || {};
    var labels = [['http', 'درخواست خروجی مسدودشده'], ['fw', 'درخواست مشکوک مسدودشده'], ['login', 'ورود ناموفقِ قفل‌شده'], ['spam', 'دیدگاه اسپم ردشده'], ['assets', 'فایل خارجی حذف‌شده']];
    var mx = Math.max(1, Math.max.apply(null, (st.days || []).map(function (d) { return d.n; }).concat([0])));
    var impact = '<section class="mb-card mb-impact"><div class="mb-card-h">' + icon('chart') + 'گزارش تأثیر (۳۰ روز اخیر)<small>رویدادهای مسدودشده در ۱۴ روز اخیر</small></div><div class="mb-card-b">' +
      '<div class="mb-imp-grid">' + labels.map(function (l) { return '<div class="mb-imp"><b>' + fa(T[l[0]] || 0) + '</b><span>' + l[1] + '</span></div>'; }).join('') + '</div>' +
      '<div class="mb-spark" role="img" aria-label="نمودار رویدادهای مسدودشده">' + (st.days || []).map(function (d) { return '<i data-tip="' + esc(d.d) + ': ' + fa(d.n) + '" style="height:' + Math.max(4, Math.round(d.n / mx * 56)) + 'px"' + (d.n ? '' : ' class="z"') + '></i>'; }).join('') + '</div></div></section>';
    var s = D.site;
    html += impact + '<div class="mb-two" style="margin-top:16px">' +

      '<section class="mb-card"><div class="mb-card-h">فعال بر اساس بخش</div><div style="padding:8px 0">' + prog + '</div></section>' +
      '<section class="mb-card"><div class="mb-card-h">تماس با ما</div><div class="mb-card-b">' +
      '<p class="mb-tool-d">اگر افزونه مشکلی داشت، امکانی خراب کار کرد یا پیشنهادی دارید، آن را در بخش Issues گیت‌هاب ثبت کنید. هر چه توضیح دقیق‌تر باشد، زودتر رفع می‌شود (نسخه‌ی وردپرس، نسخه‌ی افزونه و مراحل بروز مشکل را بنویسید).</p>' + issues + '</div></section></div>';
    html += '<div class="mb-two" style="margin-top:16px">' +
      '<section class="mb-card"><div class="mb-card-h">اطلاعات سایت</div>' +
      kv('نسخه‌ی وردپرس', s.wp) + kv('نسخه‌ی PHP', s.php) + kv('قالب فعال', s.theme) + kv('افزونه‌های فعال', fa(s.plugins)) +
      kv('ووکامرس', s.woo ? 'نصب و فعال' : 'نصب نیست') + kv('المنتور', s.elementor ? 'نصب و فعال' : 'نصب نیست') + '</section>' +
      '<section class="mb-card"><div class="mb-card-h">ابزارها</div><div class="mb-card-b">' +
      '<p class="mb-tool-d">«تنظیمات پیشنهادی» فقط امکان‌های کم‌خطر را روشن می‌کند. «بازنشانی» همه‌ی گزینه‌ها را به حالت اولیه برمی‌گرداند. با «دریافت» و «وارد کردن» می‌توانید تنظیمات را روی سایت دیگر ببرید.</p>' +
      '<div class="mb-tools"><button class="mb-btn primary" id="mb-rec">' + icon('wand') + 'تنظیمات پیشنهادی</button>' +
      '<button class="mb-btn" id="mb-export">' + icon('download') + 'برون‌بری تنظیمات</button>' +
      '<button class="mb-btn" id="mb-import">' + icon('upload') + 'درون‌ریزی تنظیمات</button>' +
      '<button class="mb-btn danger" id="mb-reset">' + icon('reset') + 'بازنشانی تنظیمات</button>' +
      '<input type="file" id="mb-file" accept="application/json,.json" hidden></div></div></section></div>';
    return html;
  }

  /* ───── رندر ───── */
  function theme() { return localStorage.getItem('mbTheme') === 'dark' ? 'dark' : 'light'; }
  function applyTheme() {
    var t = theme();
    root.setAttribute('data-theme', t);
    var b = document.getElementById('mb-theme');
    if (b) b.innerHTML = icon(t === 'dark' ? 'sun' : 'moon');
  }
  function mainHtml() {
    if (query.trim()) return searchHtml();
    if (route === 'overview') return overviewHtml();
    return categoryHtml(route);
  }
  function render(animate) {
    var focusQ = document.activeElement && document.activeElement.id === 'mb-q';
    var pos = focusQ ? document.activeElement.selectionStart : 0;
    var y = window.pageYOffset;
    root.innerHTML = '<div class="mb-main">' + topHtml() + '<div class="mb-main-in' + (animate ? ' mb-enter' : '') + '">' + mainHtml() + '</div></div>' + sideHtml();
    root.appendChild(layer);
    tipbox.hidden = true;
    applyTheme();
    if (!animate) window.scrollTo(0, y);
    if (focusQ) { var q = document.getElementById('mb-q'); q.focus(); q.setSelectionRange(pos, pos); }
  }
  function refreshChrome() {
    document.querySelectorAll('[data-cnt]').forEach(function (n) { n.textContent = fa(activeIn(n.getAttribute('data-cnt'))); });
    if (route === 'overview' && !query.trim()) render();
  }
  function go(r) {
    route = r; query = '';
    history.replaceState(null, '', '#' + r);
    var cur = root.querySelector('.mb-main-in');
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    function swap() { render(true); window.scrollTo(0, 0); }
    if (cur && !reduce) { cur.classList.add('mb-leave'); setTimeout(swap, 130); } else swap();
  }

  /* ───── رویدادها ───── */
  function changeFeature(f, newVal) {
    var turningOn = f.type === 'select' ? newVal !== f['default'] : newVal === 1;
    var msgs = turningOn ? warnings(f) : [];
    function commit() { save(f.id, newVal, function () { render(); }); }
    if (msgs.length) confirmBox('قبل از فعال‌سازی', msgs, 'متوجه شدم، فعال شود', commit);
    else commit();
  }

  root.addEventListener('click', function (e) {
    var t = e.target.closest('button');
    if (!t) return;
    if (t.hasAttribute('data-go')) return go(t.getAttribute('data-go'));
    if (t.id === 'mb-theme') {
      localStorage.setItem('mbTheme', theme() === 'dark' ? 'light' : 'dark');
      root.classList.add('mb-theming');
      applyTheme();
      return setTimeout(function () { root.classList.remove('mb-theming'); }, 450);
    }
    if (t.hasAttribute('data-sw')) {
      var f = byId[t.getAttribute('data-sw')];
      return changeFeature(f, isOn(f) ? 0 : 1);
    }
    if (t.hasAttribute('data-pick')) {
      var pk = t.getAttribute('data-pick');
      if (!window.wp || !window.wp.media) return toast('کتابخانه‌ی رسانه در دسترس نیست.', true);
      var frame = window.wp.media({ title: 'انتخاب تصویر', multiple: false, library: { type: 'image' }, button: { text: 'استفاده از این تصویر' } });
      frame.on('select', function () {
        var att = frame.state().get('selection').first().toJSON();
        save(pk, att.url, function () { render(); });
      });
      return frame.open();
    }
    if (t.hasAttribute('data-unpick')) return save(t.getAttribute('data-unpick'), '', function () { render(); });
    if (t.hasAttribute('data-step')) {
      var fid = t.getAttribute('data-for');
      var sdef = findDef(fid);
      var inp = root.querySelector('[data-num="' + fid + '"]');
      var cur0 = parseInt(inp.value, 10);
      if (isNaN(cur0)) cur0 = sdef['default'];
      var nv = Math.max(sdef.min, Math.min(sdef.max, cur0 + (+t.getAttribute('data-step')) * (sdef.step || 1)));
      inp.value = nv;
      clearTimeout(stepTimers[fid]);
      stepTimers[fid] = setTimeout(function () { save(fid, nv); }, 450);
      return;
    }
    if (t.hasAttribute('data-clear')) {
      var clid = t.getAttribute('data-clear');
      return confirmBox('پاک‌کردن فهرست', ['همه‌ی دامنه‌های این فهرست پاک می‌شوند.'], 'پاک شود', function () { save(clid, '', function () { render(); }); }, true);
    }
    if (t.hasAttribute('data-unhide')) {
      return post('mb_notice_show', { hash: t.getAttribute('data-unhide') }).then(function (d) {
        D.hiddenNotices = d.hidden || []; toast('اعلان دوباره نمایش داده می‌شود'); render();
      }).catch(function () { toast('انجام نشد.', true); });
    }
    if (t.id === 'mb-export') {
      var blob = new Blob([JSON.stringify({ mb_plugin: 'disable-bloat-manager', mb_version: D.version, exported: new Date().toISOString(), mb_settings: values }, null, 2)], { type: 'application/json' });
      var a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'mb-settings-' + new Date().toISOString().slice(0, 10) + '.json';
      document.body.appendChild(a); a.click(); a.remove();
      setTimeout(function () { URL.revokeObjectURL(a.href); }, 1000);
      return toast('فایل برون‌بری تنظیمات دریافت شد');
    }
    if (t.id === 'mb-import') { var fi = document.getElementById('mb-file'); if (fi) fi.click(); return; }
    if (t.hasAttribute('data-dd')) {
      var did = t.getAttribute('data-dd');
      ddOpen[did] = !ddOpen[did];
      return render();
    }
    if (t.hasAttribute('data-bulk')) {
      var cid = t.getAttribute('data-bulk');
      var list = bulkables(cid);
      var allOn = list.every(isOn);
      var go1 = function () {
        post('mb_bulk', { cat: cid, value: allOn ? 0 : 1 }).then(function (d) {
          values = d.values; toast(allOn ? 'همه خاموش شدند' : 'همه روشن شدند'); render();
        }).catch(function () { toast('اعمال نشد. دوباره تلاش کنید.', true); });
      };
      if (allOn) return go1();
      var off = list.filter(function (x) { return !isOn(x); });
      var caution = off.filter(function (x) { return x.badge === 'caution' || x.badge === 'danger'; }).length;
      return confirmBox('فعال‌سازی همه', [
        fa(off.length) + ' امکان در این بخش روشن می‌شود.',
        caution ? fa(caution) + ' مورد از آن‌ها «نیازمند بررسی» یا «حساسیت بالا» هستند و ممکن است ظاهر یا کارکرد سایت را تغییر بدهند. بعد از اعمال، سایت را یک بار ببینید.' : ''
      ], 'روشن شود', go1);
    }
    if (t.hasAttribute('data-preset')) {
      var pid = t.getAttribute('data-preset');
      var pf = findDef(pid);
      var cur = String(values[pid] || '').split('\n').filter(Boolean);
      pf.presets[+t.getAttribute('data-pi')].items.forEach(function (h) { if (cur.indexOf(h) < 0) cur.push(h); });
      return save(pid, cur.join('\n'), function () { render(); });
    }
    if (t.hasAttribute('data-add')) {
      var to = t.getAttribute('data-to');
      var hh = t.getAttribute('data-add');
      var lst = String(values[to] || '').split('\n').filter(Boolean);
      if (lst.indexOf(hh) < 0) lst.push(hh);
      return save(to, lst.join('\n'), function () {
        D.learned = (D.learned || []).filter(function (x) { return x.host !== hh; });
        post('mb_learned', { op: 'remove', host: hh }).catch(function () {});
        render();
      });
    }
    if (t.hasAttribute('data-learn-clear')) {
      return confirmBox('پاک‌کردن پیشنهادها', ['فهرست پیشنهادهای شناسایی‌شده خالی می‌شود و دوباره از نو یاد گرفته می‌شود.'], 'پاک شود', function () {
        post('mb_learned', { op: 'clear' }).then(function (d) { D.learned = d.learned || []; render(); }).catch(function () { toast('انجام نشد.', true); });
      });
    }
    if (t.id === 'mb-rec') {
      return confirmBox('تنظیمات پیشنهادی', ['امکان‌های کم‌خطر و پیشنهادی روشن می‌شوند.', 'گزینه‌هایی که الان روشن کرده‌اید خاموش نمی‌شوند.'], 'اعمال شود', function () {
        post('mb_recommended').then(function (d) { values = d.values; toast('تنظیمات پیشنهادی اعمال شد'); render(); })
          .catch(function () { toast('اعمال نشد. دوباره تلاش کنید.', true); });
      });
    }
    if (t.id === 'mb-reset') {
      return confirmBox('بازنشانی تنظیمات', ['همه‌ی گزینه‌ها به حالت اولیه برمی‌گردند.', 'این کار قابل بازگشت نیست.'], 'بازنشانی شود', function () {
        post('mb_reset').then(function (d) { values = d.values; toast('تنظیمات بازنشانی شد'); render(); })
          .catch(function () { toast('بازنشانی نشد. دوباره تلاش کنید.', true); });
      }, true);
    }
  });

  root.addEventListener('change', function (e) {
    var el = e.target;
    if (el.id === 'mb-file') {
      var file = el.files && el.files[0];
      if (!file) return;
      var rd = new FileReader();
      rd.onload = function () {
        var obj = null;
        try { obj = JSON.parse(rd.result); } catch (x) { obj = null; }
        el.value = '';
        if (!obj || !obj.mb_settings || typeof obj.mb_settings !== 'object') return toast('این فایل تنظیمات معتبر نیست.', true);
        var n = Object.keys(obj.mb_settings).length;
        confirmBox('درون‌ریزی تنظیمات', [fa(n) + ' گزینه از فایل خوانده شد.', 'تنظیمات فعلی با مقادیر این فایل جایگزین می‌شوند.'], 'درون‌ریزی شود', function () {
          post('mb_import', { data: rd.result }).then(function (d) { values = d.values; toast('تنظیمات درون‌ریزی شد'); render(); })
            .catch(function () { toast('درون‌ریزی انجام نشد.', true); });
        });
      };
      rd.readAsText(file);
      return;
    }
    if (el.hasAttribute('data-chk')) {
      var cid = el.getAttribute('data-chk');
      var arr = [].slice.call(root.querySelectorAll('[data-chk="' + cid + '"]:checked')).map(function (x) { return x.value; });
      var cf = byId[cid];
      var was = (values[cid] || []).length > 0;
      var refresh = function () { render(); };
      if (cf && !was && arr.length && warnings(cf).length) {
        el.checked = false;
        return confirmBox('قبل از فعال‌سازی', warnings(cf), 'متوجه شدم، فعال شود', function () { save(cid, arr, refresh); });
      }
      return save(cid, arr, refresh);
    }
    if (el.hasAttribute('data-tchk')) {
      var tid = el.getAttribute('data-tchk');
      var tl = [].slice.call(root.querySelectorAll('[data-tchk="' + tid + '"]:checked')).map(function (x) { return x.value; });
      return save(tid, tl);
    }
    if (el.hasAttribute('data-txt')) return save(el.getAttribute('data-txt'), el.value);
    if (el.hasAttribute('data-color')) return save(el.getAttribute('data-color'), el.value);
    if (el.hasAttribute('data-ta')) return save(el.getAttribute('data-ta'), el.value, function () { render(); });
    if (el.hasAttribute('data-sel')) {
      var sf = byId[el.getAttribute('data-sel')];
      var prev = values[sf.id];
      var val = el.value;
      var msgs = val !== sf['default'] ? warnings(sf) : [];
      if (msgs.length) {
        el.value = prev;
        return confirmBox('قبل از فعال‌سازی', msgs, 'متوجه شدم، فعال شود', function () { save(sf.id, val, function () { render(); }); });
      }
      return save(sf.id, val, function () { render(); });
    }
    if (el.hasAttribute('data-num')) {
      var id = el.getAttribute('data-num');
      var def = findDef(id);
      var n = parseInt(el.value, 10);
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
    if (top < 90) top = r.bottom + 9;
    tipbox.style.left = left + 'px';
    tipbox.style.top = top + 'px';
  }
  root.addEventListener('mouseover', function (e) {
    var tip = e.target.closest && e.target.closest('[data-tip]');
    if (tip) showTip(tip);
  });
  root.addEventListener('mouseout', function (e) {
    if (e.target.closest && e.target.closest('[data-tip]')) tipbox.hidden = true;
  });
  root.addEventListener('focusin', function (e) {
    var tip = e.target.closest && e.target.closest('[data-tip]');
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

  /* بررسی نسخه‌ی جدید در پس‌زمینه (اگر کش قدیمی بود)؛ هیچ‌وقت صفحه را معطل نمی‌کند */
  if (D.update && D.update.stale) {
    post('mb_update_check').then(function (d) {
      if (d && d.update) { D.update = d.update; var r = root.querySelector('.mb-top-r'); if (r) { var old = r.querySelector('.mb-upd'); if (old) old.remove(); r.insertAdjacentHTML('afterbegin', updateBtn()); } }
    }).catch(function () {});
  }

  var h = (location.hash || '').replace('#', '');
  if (h && catOf(h)) route = h;
  render();
})();
