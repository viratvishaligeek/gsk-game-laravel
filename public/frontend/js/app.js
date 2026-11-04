document.querySelectorAll('.drop-btn').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var menu = btn.parentElement.querySelector('.drop-menu');
    if (!menu) return;
    var open = menu.style.display === 'block';
    document.querySelectorAll('.drop-menu').forEach(function (m) { m.style.display = 'none'; });
    menu.style.display = open ? 'none' : 'block';
  });
});
document.addEventListener('click', function (e) {
  if (!e.target.closest('.drop')) {
    document.querySelectorAll('.drop-menu').forEach(function (m) { m.style.display = 'none'; });
  }
});

(function () {
  var data = window.PB_LOAD;
  if (!data || !data.length) return;
  var activeGame = data[0].label;
  var activeRange = '01-30';
  var chart = document.getElementById('load-chart');
  if (!chart) return;

  function barColor(pct) {
    if (pct >= 2) return '#ff0033';
    if (pct >= 1.5) return '#ffe600';
    if (pct >= 1) return '#d100ff';
    return '#3388ff';
  }
  function rangeNums(numbers, from, to) {
    if (to === 100) {
      return numbers.filter(function (n) {
        var v = parseInt(n.num, 10);
        return (v >= 81 && v <= 99) || n.num === '00';
      });
    }
    return numbers.filter(function (n) {
      var v = parseInt(n.num, 10);
      return v >= from && v <= to;
    });
  }
  function render() {
    var game = data.find(function (g) { return g.label === activeGame; }) || data[0];
    var from = 1, to = 30;
    if (activeRange === '30-60') { from = 30; to = 60; }
    if (activeRange === '60-80') { from = 60; to = 80; }
    if (activeRange === '81-00') { from = 81; to = 100; }
    var entries = rangeNums(game.numbers, from, to);
    var maxPct = 0.01;
    entries.forEach(function (e) { if (e.pct > maxPct) maxPct = e.pct; });
    chart.innerHTML = '';
    entries.forEach(function (e) {
      var h = maxPct > 0 ? Math.max((e.pct / maxPct) * 160, e.pct > 0 ? 6 : 0) : 0;
      var col = barColor(e.pct);
      var el = document.createElement('div');
      el.className = 'load-bar';
      el.innerHTML =
        '<span class="pct" style="color:' + (e.pct > 0 ? col : 'transparent') + '">' + (e.pct > 0 ? e.pct.toFixed(1) + '%' : '') + '</span>' +
        '<div class="col" style="height:' + h + 'px;background:' + (e.pct > 0 ? col : 'transparent') + '"></div>' +
        '<div class="base"></div>' +
        '<span class="lbl" style="color:' + (e.pct >= 1 ? col : 'rgba(180,200,255,.7)') + '">' + e.num + '</span>';
      chart.appendChild(el);
    });
  }
  document.querySelectorAll('[data-load-tab]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      activeGame = btn.getAttribute('data-load-tab');
      document.querySelectorAll('[data-load-tab]').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      render();
    });
  });
  document.querySelectorAll('[data-load-range]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      activeRange = btn.getAttribute('data-load-range');
      document.querySelectorAll('[data-load-range]').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      render();
    });
  });
  render();
})();

document.querySelectorAll('[data-copy]').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var val = btn.getAttribute('data-copy') || '';
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(val);
    } else {
      var el = document.createElement('textarea');
      el.value = val;
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
    }
    var old = btn.textContent;
    btn.textContent = 'Copied';
    setTimeout(function () { btn.textContent = old; }, 1600);
  });
});

(function () {
  var modal = document.getElementById('wa-modal');
  if (!modal) return;
  function open() { modal.classList.add('open'); }
  function close() { modal.classList.remove('open'); }
  document.querySelectorAll('[data-wa-open]').forEach(function (el) {
    el.addEventListener('click', open);
  });
  document.querySelectorAll('[data-wa-close]').forEach(function (el) {
    el.addEventListener('click', close);
  });
  modal.addEventListener('click', function (e) {
    if (e.target === modal) close();
  });
})();

document.querySelectorAll('[data-prev-toggle]').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var table = document.querySelector('.prev-table');
    if (!table) return;
    var open = table.classList.toggle('is-open');
    btn.textContent = open ? 'SHOW LESS' : 'SHOW ALL 14 DAYS';
  });
});

// (function () {
//   var SCHEDULE = [
//     { name: 'DISAWAR', time: '05:15 AM', minutes: 5 * 60 + 15, slug: 'desawar' },
//     { name: 'DELHI BAZAR', time: '03:15 PM', minutes: 15 * 60 + 15, slug: 'delhi-bazar' },
//     { name: 'SHREE GANESH', time: '04:45 PM', minutes: 16 * 60 + 45, slug: 'shri-ganesh' },
//     { name: 'FARIDABAD', time: '06:15 PM', minutes: 18 * 60 + 15, slug: 'faridabad' },
//     { name: 'GHAZIABAD', time: '10:00 PM', minutes: 22 * 60, slug: 'ghaziabad' },
//     { name: 'GALI', time: '11:50 PM', minutes: 23 * 60 + 50, slug: 'gali' }
//   ];
//   var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
//   var CLOCK = '<svg class="lrb-clock" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>';

//   function pad(n) { return String(n).padStart(2, '0'); }
//   function istNow() { return new Date(Date.now() + 5.5 * 60 * 60 * 1000); }
//   function todayIST() {
//     var n = istNow();
//     return n.getUTCFullYear() + '-' + pad(n.getUTCMonth() + 1) + '-' + pad(n.getUTCDate());
//   }
//   function nowMinutesIST() {
//     var n = istNow();
//     return n.getUTCHours() * 60 + n.getUTCMinutes();
//   }
//   function istDatetimeLabel() {
//     var n = istNow();
//     var h = n.getUTCHours();
//     var ampm = h >= 12 ? 'PM' : 'AM';
//     h = h % 12 || 12;
//     return n.getUTCDate() + ' ' + MONTHS[n.getUTCMonth()] + ' ' + n.getUTCFullYear() + ' | ' + h + ':' + pad(n.getUTCMinutes()) + ' ' + ampm;
//   }
//   function nowClock() {
//     var n = istNow();
//     return pad(n.getUTCHours()) + ':' + pad(n.getUTCMinutes()) + ':' + pad(n.getUTCSeconds());
//   }
//   function parseTimeMinutes(resultTime) {
//     var m = String(resultTime || '').trim().match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
//     if (!m) return null;
//     var hours = parseInt(m[1], 10);
//     var minutes = parseInt(m[2], 10);
//     var mer = m[3].toUpperCase();
//     if (mer === 'PM' && hours !== 12) hours += 12;
//     if (mer === 'AM' && hours === 12) hours = 0;
//     return hours * 60 + minutes;
//   }
//   function isMarketClosed(resultTime) {
//     var mins = parseTimeMinutes(resultTime);
//     if (mins === null) return false;
//     var now = nowMinutesIST();
//     var marketMinutes = mins + 30;
//     var overnightMarket = mins < 6 * 60;
//     var overnightNow = now < 6 * 60;
//     if (overnightMarket) return overnightNow && now >= marketMinutes;
//     if (overnightNow) return true;
//     return now >= marketMinutes;
//   }
//   function sameSlug(a, b) {
//     if (a === b) return true;
//     return (a === 'desawar' || a === 'disawar') && (b === 'desawar' || b === 'disawar');
//   }
//   function findGame(games, slug) {
//     for (var i = 0; i < games.length; i++) {
//       if (sameSlug(games[i].slug, slug)) return games[i];
//     }
//     return null;
//   }
//   function cardState(game) {
//     if ((game.status || '') === 'Declared' && game.winningNumber) return 'declared';
//     if (game.slug === 'gali') return 'awaited';
//     if (isMarketClosed(game.resultTime || '')) return 'closed';
//     return 'awaited';
//   }
//   function liveFor(games, slug) {
//     var g = findGame(games, slug);
//     if (g && g.status === 'Declared' && g.winningNumber) return g;
//     return null;
//   }
//   function pillState(games) {
//     var now = nowMinutesIST();
//     var nextByTime = SCHEDULE[0];
//     for (var i = 0; i < SCHEDULE.length; i++) {
//       if (SCHEDULE[i].minutes > now) { nextByTime = SCHEDULE[i]; break; }
//     }
//     var liveGame = liveFor(games, nextByTime.slug);
//     var nextUpcoming = null;
//     for (i = 0; i < SCHEDULE.length; i++) {
//       if (!liveFor(games, SCHEDULE[i].slug) && SCHEDULE[i].minutes > now) { nextUpcoming = SCHEDULE[i]; break; }
//     }
//     if (!nextUpcoming) {
//       for (i = 0; i < SCHEDULE.length; i++) {
//         if (!liveFor(games, SCHEDULE[i].slug)) { nextUpcoming = SCHEDULE[i]; break; }
//       }
//     }
//     var isLive = !!liveGame;
//     return {
//       isLive: isLive,
//       labelName: isLive ? nextByTime.name : ((nextUpcoming && nextUpcoming.name) || nextByTime.name),
//       labelExtra: isLive ? liveGame.winningNumber : ((nextUpcoming && nextUpcoming.time) || nextByTime.time)
//     };
//   }
//   function liveUpcoming(games) {
//     var now = nowMinutesIST();
//     var latestDeclared = null;
//     for (var i = SCHEDULE.length - 1; i >= 0; i--) {
//       if (liveFor(games, SCHEDULE[i].slug)) { latestDeclared = SCHEDULE[i]; break; }
//     }
//     var upcoming = null;
//     for (i = 0; i < SCHEDULE.length; i++) {
//       if (!liveFor(games, SCHEDULE[i].slug) && SCHEDULE[i].minutes > now) { upcoming = SCHEDULE[i]; break; }
//     }
//     if (!upcoming) {
//       for (i = 0; i < SCHEDULE.length; i++) {
//         if (!liveFor(games, SCHEDULE[i].slug)) { upcoming = SCHEDULE[i]; break; }
//       }
//     }
//     var liveSlot = latestDeclared || SCHEDULE[0];
//     var liveGame = liveFor(games, liveSlot.slug);
//     return { liveSlot: liveSlot, liveGame: liveGame, upcoming: upcoming, declared: !!liveGame };
//   }
//   function pop(el) {
//     if (!el) return;
//     el.classList.remove('num-pop');
//     void el.offsetWidth;
//     el.classList.add('num-pop');
//   }
//   function setText(el, text) {
//     if (el && el.textContent !== text) el.textContent = text;
//   }
//   function updateTicker(games) {
//     var track = document.querySelector('[data-ticker]');
//     if (!track) return;
//     games.forEach(function (g) {
//       track.querySelectorAll('.ticker-item[data-slug="' + g.slug + '"]').forEach(function (item) {
//         var num = item.querySelector('.ticker-num, .ticker-wait');
//         if (!num) return;
//         if (g.winningNumber) {
//           if (num.classList.contains('ticker-wait') || num.textContent !== String(g.winningNumber)) {
//             num.className = 'ticker-num';
//             num.textContent = String(g.winningNumber);
//             pop(num);
//           }
//         } else if (num.classList.contains('ticker-num') || num.textContent !== g.resultTime) {
//           num.className = 'ticker-wait';
//           num.textContent = g.resultTime;
//         }
//       });
//     });
//   }
//   function updateHero(games) {
//     var dateEl = document.querySelector('[data-live-date]');
//     if (dateEl) setText(dateEl, istDatetimeLabel());
//     var pill = document.querySelector('[data-hero-pill]');
//     if (pill) {
//       var p = pillState(games);
//       setText(pill.querySelector('[data-pill-kind]'), p.isLive ? 'Live Result:' : 'Upcoming Result:');
//       setText(pill.querySelector('[data-pill-name]'), p.labelName);
//       setText(pill.querySelector('[data-pill-extra]'), '— ' + p.labelExtra);
//     }
//     games.forEach(function (g) {
//       var card = document.querySelector('.hero-card[data-slug="' + g.slug + '"]');
//       if (!card) return;
//       var has = g.status === 'Declared' && g.winningNumber;
//       var num = card.querySelector('.num');
//       var next = has ? String(g.winningNumber) : '**';
//       if (num && num.textContent !== next) {
//         num.textContent = next;
//         pop(num);
//       }
//       card.classList.toggle('has', !!has);
//       card.classList.toggle('wait', !has);
//     });
//   }
//   function updateLiveBox(games) {
//     var box = document.querySelector('[data-live-box]');
//     if (!box) return;
//     var lu = liveUpcoming(games);
//     var html = '';
//     if (lu.declared && lu.liveGame) {
//       html += '<div class="gname">' + lu.liveSlot.name + '</div>';
//       html += '<div class="gnum">' + lu.liveGame.winningNumber + '</div>';
//       html += '<div class="sub">Live Result</div>';
//       if (lu.upcoming && lu.upcoming.slug !== lu.liveSlot.slug) {
//         html += '<div class="upcoming">';
//         html += '<div class="gname" style="font-size:1.1rem">' + lu.upcoming.name + '</div>';
//         html += '<div class="sub" style="margin-top:6px"><span class="pulse"></span> Upcoming Result</div>';
//         html += '<div class="gname" style="color:#fff;margin-top:4px">' + lu.upcoming.time + '</div>';
//         html += '</div>';
//       }
//     } else {
//       var up = lu.upcoming || lu.liveSlot;
//       html += '<div class="gname">' + up.name + '</div>';
//       html += '<div class="sub" style="margin:10px 0"><span class="pulse"></span> Upcoming Result</div>';
//       html += '<div class="gname" style="color:#fff;font-size:1.8rem">' + up.time + '</div>';
//     }
//     if (box.getAttribute('data-html') !== html) {
//       box.innerHTML = html;
//       box.setAttribute('data-html', html);
//       var gnum = box.querySelector('.gnum');
//       if (gnum) pop(gnum);
//     }
//   }
//   function updateResultCards(games) {
//     games.forEach(function (g) {
//       var card = document.querySelector('.result-card[data-slug="' + g.slug + '"]');
//       if (!card) return;
//       var state = cardState(g);
//       var declared = state === 'declared';
//       var closed = state === 'closed';
//       card.classList.toggle('declared', declared);
//       var tag = card.querySelector('.tag');
//       if (tag) {
//         tag.className = 'tag ' + (declared ? 'ok' : (closed ? 'closed' : 'wait'));
//         tag.textContent = declared ? 'Declared' : (closed ? 'Closed' : 'Pending');
//       }
//       var big = card.querySelector('.big');
//       if (big) {
//         var next = declared ? String(g.winningNumber) : (closed ? '--' : '');
//         if (big.textContent !== next) {
//           big.textContent = next;
//           if (declared) pop(big);
//         }
//         big.classList.toggle('dim', !declared);
//       }
//       var meta = card.querySelector('.muted.small');
//       if (meta) {
//         var prefix = declared ? 'Result at' : (closed ? 'Closed at' : 'Expected at');
//         setText(meta, prefix + ' ' + g.resultTime);
//       }
//     });
//   }
//   function updateLrb(games, stamp) {
//     var updated = document.querySelector('[data-lrb-updated]');
//     if (updated) updated.innerHTML = CLOCK + ' Updated ' + stamp;
//     var grid = document.querySelector('.lrb-grid');
//     games.forEach(function (g) {
//       var card = document.querySelector('.lrb-card[data-slug="' + g.slug + '"]');
//       if (!card) return;
//       var state = cardState(g);
//       var declared = state === 'declared';
//       var closed = state === 'closed';
//       var prev = card.getAttribute('data-state');
//       card.setAttribute('data-state', state);
//       var badge = card.querySelector('.lrb-badge');
//       if (badge) {
//         badge.className = 'lrb-badge lrb-badge--' + state;
//         badge.innerHTML = (declared ? '<span class="lrb-dot"></span>' : '') + (declared ? 'Declared' : (closed ? 'Closed' : 'Pending'));
//       }
//       var body = card.querySelector('.lrb-body');
//       if (!body) return;
//       var num = body.querySelector('.lrb-num');
//       if (declared) {
//         if (!num) {
//           num = document.createElement('span');
//           num.className = 'lrb-num lrb-num--declared';
//           body.insertBefore(num, body.firstChild);
//         }
//         if (num.textContent !== String(g.winningNumber)) {
//           num.textContent = String(g.winningNumber);
//           pop(num);
//         }
//       } else if (num) {
//         num.remove();
//       }
//       var time = body.querySelector('.lrb-time');
//       if (time) {
//         var strong = time.querySelector('strong');
//         var prefix = declared ? 'Result at' : (closed ? 'Closed at' : 'Expected at');
//         time.innerHTML = CLOCK + ' ' + prefix + ' <strong>' + g.resultTime + '</strong>';
//       }
//       if (prev !== state && grid && state === 'declared') {
//         var mins = nowMinutesIST();
//         if (mins >= 0 && mins < 360 && (g.slug === 'desawar' || g.slug === 'disawar') && grid.firstElementChild !== card) {
//           grid.insertBefore(card, grid.firstElementChild);
//         }
//       }
//     });
//   }
//   function apply(games, stamp) {
//     updateTicker(games);
//     updateHero(games);
//     updateLiveBox(games);
//     updateResultCards(games);
//     updateLrb(games, stamp);
//   }
//   var fetching = false;
//   function fetchResults(manual) {
//     if (fetching) return;
//     fetching = true;
//     var btn = document.querySelector('[data-lrb-refresh]');
//     if (manual && btn) {
//       btn.classList.add('is-spin');
//       btn.disabled = true;
//     }
//     fetch('/api/results?date=' + todayIST() + '&_t=' + Date.now(), {
//       cache: 'no-store',
//       headers: { 'Cache-Control': 'no-cache', Pragma: 'no-cache' }
//     })
//       .then(function (res) { return res.json(); })
//       .then(function (json) {
//         apply(json.games || [], nowClock());
//       })
//       .catch(function () {})
//       .then(function () {
//         fetching = false;
//         if (btn) {
//           btn.classList.remove('is-spin');
//           btn.disabled = false;
//         }
//       });
//   }

//   var dateEl = document.querySelector('[data-live-date]');
//   if (dateEl) {
//     setText(dateEl, istDatetimeLabel());
//     setInterval(function () { setText(dateEl, istDatetimeLabel()); }, 30 * 1000);
//   }
//   var refreshBtn = document.querySelector('[data-lrb-refresh]');
//   if (refreshBtn) {
//     refreshBtn.addEventListener('click', function () { fetchResults(true); });
//   }
//   if (document.querySelector('[data-ticker], [data-hero-pill], [data-live-box], [data-lrb-refresh]')) {
//     fetchResults(false);
//     setInterval(function () { fetchResults(false); }, 10 * 1000);
//   }
// })();

(function () {
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.documentElement.classList.remove('js-reveal');
    return;
  }

  function mark(el, opts) {
    if (!el || el.hasAttribute('data-reveal')) return;
    el.setAttribute('data-reveal', '1');
    el.style.setProperty('--reveal-x', (opts.x || 0) + 'px');
    el.style.setProperty('--reveal-y', (opts.y == null ? 20 : opts.y) + 'px');
    el.style.setProperty('--reveal-dur', (opts.dur == null ? 0.5 : opts.dur) + 's');
    el.style.setProperty('--reveal-delay', (opts.delay || 0) + 's');
  }
  function markList(nodes, base) {
    Array.prototype.forEach.call(nodes, function (el, i) {
      mark(el, {
        x: base.x || 0,
        y: base.y,
        dur: base.dur,
        delay: (base.delay || 0) + i * (base.stagger || 0)
      });
    });
  }

  markList(document.querySelectorAll('.result-card'), { y: 20, dur: 0.35, stagger: 0.06 });
  markList(document.querySelectorAll('.lrb-card'), { y: 12, dur: 0.25, stagger: 0.04 });
  markList(document.querySelectorAll('.section .center, .lrb-heading'), { y: 20, dur: 0.5 });
  markList(document.querySelectorAll('.how-step'), { x: -20, y: 0, dur: 0.4, stagger: 0.1 });
  markList(document.querySelectorAll('.why-card'), { y: 20, dur: 0.5, stagger: 0.08 });
  markList(document.querySelectorAll('.seo-game'), { y: 20, dur: 0.4, stagger: 0.05 });
  markList(document.querySelectorAll('.market-link'), { y: 16, dur: 0.35, stagger: 0.04 });
  markList(document.querySelectorAll('.game-chart-card'), { y: 28, dur: 0.5, stagger: 0.08 });
  markList(document.querySelectorAll('.hist-strip'), { y: 20, dur: 0.4, stagger: 0.06 });
  markList(document.querySelectorAll('.rec-card'), { y: 12, dur: 0.3, stagger: 0.03 });
  markList(document.querySelectorAll('.chart-table'), { y: 24, dur: 0.6 });
  markList(document.querySelectorAll('.wa-ad-card, .ad-banner-card, .family-wrap, .load-box, .prev-wrap, .month-nav, .blog-card, .faq, .stat-row, .filters, .prev-toggle'), { y: 24, dur: 0.5 });

  var nodes = document.querySelectorAll('[data-reveal]');
  if (!nodes.length) return;
  function revealEl(el) {
    el.classList.add('reveal-run');
  }
  if (!('IntersectionObserver' in window)) {
    Array.prototype.forEach.call(nodes, revealEl);
    return;
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      revealEl(entry.target);
      io.unobserve(entry.target);
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -8% 0px' });
  Array.prototype.forEach.call(nodes, function (el) { io.observe(el); });
})();
