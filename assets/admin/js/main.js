/* =============================================================
   SYS ADMIN — main.js
   Admin panel JavaScript — sidebar, select2, alerts, forms
   English (LTR) & Arabic (RTL) aware
   ============================================================= */

(function () {
  'use strict';

  /* ─── Helpers ─────────────────────────────────────────── */
  const isRTL = () => document.documentElement.dir === 'rtl';
  const $ = (sel, ctx) => (ctx || document).querySelector(sel);
  const $$ = (sel, ctx) => Array.from((ctx || document).querySelectorAll(sel));
  const body = document.body;

  /* ─────────────────────────────────────────────────────────
     1. SIDEBAR / PUSHMENU
     ───────────────────────────────────────────────────────── */
  function injectOverlay() {
    if ($('.sidebar-overlay')) return;
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.addEventListener('click', closeSidebar);
    ($('.wrapper') || body).appendChild(overlay);
  }

  function toggleSidebar() {
    const mobile = window.innerWidth < 992;
    if (mobile) {
      body.classList.toggle('sidebar-open');
      body.classList.remove('sidebar-collapse');
    } else {
      body.classList.toggle('sidebar-collapse');
      body.classList.remove('sidebar-open');
      try {
        localStorage.setItem(
          'sidebarCollapsed',
          body.classList.contains('sidebar-collapse') ? '1' : '0'
        );
      } catch (_) {}
    }
  }

  function closeSidebar() {
    body.classList.remove('sidebar-open');
  }

  function restoreSidebarState() {
    if (window.innerWidth < 992) return;
    try {
      if (localStorage.getItem('sidebarCollapsed') === '1') {
        body.classList.add('hold-transition', 'sidebar-collapse');
      }
    } catch (_) {}
  }

  function initSidebar() {
    injectOverlay();

    $$('[data-widget="pushmenu"]').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        toggleSidebar();
      });
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 992) {
        body.classList.remove('sidebar-open');
      }
    });
  }

  /* ─────────────────────────────────────────────────────────
     2. SELECT2
     ───────────────────────────────────────────────────────── */
  function initSelect2() {
    if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.select2 === 'undefined') return;
    const jq = window.jQuery;

    jq('select.form-control:not(.no-select2)').each(function () {
      jq(this).select2({
        width: '100%',
        dir: isRTL() ? 'rtl' : 'ltr',
        dropdownAutoWidth: false,
      });
    });
  }

  /* ─────────────────────────────────────────────────────────
     3. ALERT TOAST AUTO-DISMISS
     ───────────────────────────────────────────────────────── */
  function initAlerts() {
    $$('.alert-toast').forEach(toast => {
      setTimeout(() => {
        toast.style.transition = 'opacity .35s ease, transform .35s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-8px)';
        setTimeout(() => { if (toast.parentNode) toast.remove(); }, 380);
      }, 5000);
    });
  }

  /* ─────────────────────────────────────────────────────────
     4. RADIO VISUAL SELECTORS (type-option / status-option)
     ───────────────────────────────────────────────────────── */
  function initRadioSelectors() {
    // content_type radios → .type-option
    $$('input[name="content_type"]').forEach(radio => {
      radio.addEventListener('change', () => {
        $$('.type-option').forEach(el => el.classList.remove('selected'));
        radio.closest('.type-option')?.classList.add('selected');
      });
    });

    // status radios → .status-option  (only the radio-card style ones)
    $$('.status-option input[type="radio"]').forEach(radio => {
      radio.addEventListener('change', () => {
        $$('.status-option').forEach(el => el.classList.remove('selected'));
        radio.closest('.status-option')?.classList.add('selected');
      });
    });
  }

  /* ─────────────────────────────────────────────────────────
     5. PHOTO UPLOAD PREVIEW
     ───────────────────────────────────────────────────────── */
  function initPhotoUpload() {
    const input = document.getElementById('photo');
    if (!input) return;

    input.addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const placeholder = document.getElementById('photoPlaceholder');
        const preview     = document.getElementById('photoPreview');
        if (placeholder) placeholder.style.display = 'none';
        if (preview) { preview.src = e.target.result; preview.style.display = 'block'; }
      };
      reader.readAsDataURL(file);
    });
  }

  /* ─────────────────────────────────────────────────────────
     6. SUBSCRIPTION REMAINING CALCULATOR
     ───────────────────────────────────────────────────────── */
  function initRemainingCalc() {
    const totalEl = document.getElementById('totalAmount');
    const paidEl  = document.getElementById('paidAmount');
    if (!totalEl || !paidEl) return;

    function calc() {
      const total     = parseFloat(totalEl.value) || 0;
      const paid      = parseFloat(paidEl.value)  || 0;
      const remaining = Math.max(0, total - paid);
      const pct       = total > 0 ? Math.min(100, Math.round((paid / total) * 100)) : 0;

      const display = document.getElementById('remainingDisplay');
      const bar     = document.getElementById('progressBar');

      if (display) {
        display.textContent = remaining.toLocaleString() + ' د.أ';
        display.style.color = remaining > 0 ? 'var(--danger)' : 'var(--success)';
      }
      if (bar) {
        bar.style.width = pct + '%';
        bar.style.background =
          pct >= 100 ? 'var(--success)' :
          pct >= 50  ? 'var(--warning)' :
                       'var(--danger)';
      }
    }

    totalEl.addEventListener('input', calc);
    paidEl.addEventListener('input', calc);
    calc(); // run on load for edit pages
  }

  /* ─────────────────────────────────────────────────────────
     7. FORM SUBMIT PROTECTION (loading state on button)
     ───────────────────────────────────────────────────────── */
  function initFormProtection() {
    $$('form').forEach(form => {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (!submitBtn) return;

      // Skip single-purpose delete forms (they already use onsubmit=confirm)
      const isDeleteForm = form.querySelector('[name="_method"]')?.value === 'DELETE';
      if (isDeleteForm) return;

      form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        const icon = submitBtn.querySelector('i.fas, i.far, i.fab');
        if (icon) icon.className = 'fas fa-spinner fa-spin';
      });
    });
  }

  /* ─────────────────────────────────────────────────────────
     8. BOOTSTRAP DROPDOWN — RTL position fix
     ───────────────────────────────────────────────────────── */
  function initDropdownFix() {
    if (!isRTL() || typeof window.jQuery === 'undefined') return;
    // Bootstrap 4 dropdowns handle rtl via dir attribute; no extra action needed
  }

  /* ─────────────────────────────────────────────────────────
     INIT — runs after DOM is ready
     ───────────────────────────────────────────────────────── */
  function init() {
    restoreSidebarState();
    initSidebar();
    initAlerts();
    initRadioSelectors();
    initPhotoUpload();
    initRemainingCalc();
    initFormProtection();
    // Remove hold-transition class after layout settles
    setTimeout(() => body.classList.remove('hold-transition'), 50);
  }

  /* Wait for jQuery (loaded after this script in the layout) then init Select2 */
  function waitForJQuery(attempts) {
    if (typeof window.jQuery !== 'undefined') {
      // jQuery is ready — wire up Select2 after DOM is ready
      window.jQuery(function () { initSelect2(); });
      return;
    }
    if (attempts > 0) {
      setTimeout(() => waitForJQuery(attempts - 1), 80);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Select2 depends on jQuery which loads after this file
  waitForJQuery(20);

})();
