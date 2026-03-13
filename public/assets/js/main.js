/**
 * ═══════════════════════════════════════════════════════════════
 *  CampusFlow — public/assets/js/main.js
 *  Script principal · v1.0
 *
 *  Conserve à 100% les fonctionnalités d'origine :
 *   1. Calcul de la marge bénéficiaire (#marge-display)
 *   2. Recherche AJAX temps réel (#search-input → api-search.php)
 *
 *  Nouvelles fonctionnalités ajoutées :
 *   3. Scroll progress bar
 *   4. Navbar shadow au scroll
 *   5. Révélation d'éléments au scroll (IntersectionObserver)
 *   6. Horloge en direct (chips de navbar/dashboard)
 *   7. Toast notifications
 *   8. Animations de tableaux dynamiques
 *   9. Confirmation de suppression stylisée
 *  10. Auto-dismiss des alertes Bootstrap
 * ═══════════════════════════════════════════════════════════════
 */

document.addEventListener('DOMContentLoaded', function () {

    /* ════════════════════════════════════════════════════════════
       1. CALCUL MARGE BÉNÉFICIAIRE
       Conservé de l'original — id="marge-display" inchangé
    ════════════════════════════════════════════════════════════ */
    const prixAchatInput = document.getElementById('prix_achat');
    const prixVenteInput = document.getElementById('prix_vente');
    const margeDisplay = document.getElementById('marge-display');

    function calculerMarge() {
        const prixAchat = parseFloat(prixAchatInput.value) || 0;
        const prixVente = parseFloat(prixVenteInput.value) || 0;

        if (prixAchat > 0) {
            const marge = ((prixVente - prixAchat) / prixAchat) * 100;
            margeDisplay.textContent = marge.toFixed(2);
        } else {
            margeDisplay.textContent = '0';
        }

        // Couleur dynamique selon la marge
        if (margeDisplay) {
            const val = parseFloat(margeDisplay.textContent);
            if (val >= 30) margeDisplay.style.color = 'var(--c-success, #10b981)';
            else if (val >= 10) margeDisplay.style.color = 'var(--c-ocean,   #1a4fd4)';
            else margeDisplay.style.color = 'var(--c-amber,   #e8a020)';
        }
    }

    if (prixAchatInput && prixVenteInput) {
        prixAchatInput.addEventListener('input', calculerMarge);
        prixVenteInput.addEventListener('input', calculerMarge);
        calculerMarge(); // calcul initial si mode édition
    }


    /* ════════════════════════════════════════════════════════════
       2. RECHERCHE AJAX TEMPS RÉEL
       Conservée de l'original — id="search-input",
       id="product-table-body", endpoint api-search.php inchangés
    ════════════════════════════════════════════════════════════ */
    const searchInput = document.getElementById('search-input');
    const productTableBody = document.getElementById('product-table-body');

    // Détection rôle admin (présence bouton "Modifier" dans la page)
    const isAdmin = document.querySelector('a[href*="form-produit.php?action=edit"]') !== null;

    // Debounce pour limiter les requêtes
    function debounce(fn, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    function buildStudentRow(etudiant) {
        const enAlerte = etudiant.quantite_en_stock < etudiant.seuil_alerte;
        const initials = (etudiant.nom_produit || 'ET').substring(0, 2).toUpperCase();
        const id = String(etudiant.id || '0').padStart(4, '0');
        const frais = Number(etudiant.prix_vente).toLocaleString('fr-FR');
        const filiere = etudiant.nom_categorie || 'Non définie';

        const statusBadge = enAlerte
            ? '<span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> Places limitées</span>'
            : '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Inscrit</span>';

        const actionsBtns = isAdmin
            ? `<a href="form-produit.php?action=edit&id=${etudiant.id}" class="btn btn-sm btn-primary">
           <i class="fas fa-edit"></i> Modifier
         </a>
         <a href="traitement-produit.php?action=delete&id=${etudiant.id}"
            class="btn btn-sm btn-danger"
            onclick="return cfConfirm('Supprimer cet étudiant ?', this.href)">
           <i class="fas fa-trash-alt"></i>
         </a>`
            : '';

        const tr = document.createElement('tr');
        if (enAlerte) tr.classList.add('table-danger');
        tr.innerHTML = `
      <td>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#1a4fd4,#0d8fd4);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#fff;flex-shrink:0">
            ${initials}
          </div>
          <div>
            <div style="font-weight:600;color:var(--c-text)">${escHtml(etudiant.nom_produit)}</div>
            <div style="font-size:.72rem;color:var(--c-muted);font-family:var(--font-mono)">#${id}</div>
          </div>
        </div>
      </td>
      <td><span style="background:var(--c-ocean-dim);color:var(--c-ocean);padding:3px 9px;border-radius:6px;font-size:.75rem;font-weight:600;font-family:var(--font-mono)">${escHtml(filiere)}</span></td>
      <td><strong style="color:var(--c-text)">${frais}</strong> <small style="color:var(--c-muted)">FCFA</small></td>
      <td>
        <span style="font-weight:700;color:var(--c-ocean)">${etudiant.quantite_en_stock}</span>
        <span style="font-size:.75rem;color:var(--c-muted)"> / ${etudiant.seuil_alerte} min.</span>
      </td>
      <td>${statusBadge}</td>
      ${isAdmin ? `<td><div style="display:flex;gap:7px">${actionsBtns}</div></td>` : ''}
    `;

        // Animation d'entrée
        tr.style.opacity = '0';
        tr.style.transform = 'translateY(8px)';
        tr.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
        requestAnimationFrame(() => {
            tr.style.opacity = '1';
            tr.style.transform = 'none';
        });

        return tr;
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    function showSearchLoading() {
        if (!productTableBody) return;
        productTableBody.innerHTML = `
      <tr>
        <td colspan="6" style="text-align:center;padding:32px;color:var(--c-muted)">
          <div style="display:flex;align-items:center;justify-content:center;gap:10px">
            <span class="cf-spinner"></span>
            <span style="font-size:.875rem;font-family:var(--font-mono)">Recherche en cours…</span>
          </div>
        </td>
      </tr>`;
    }

    function showSearchEmpty(query) {
        if (!productTableBody) return;
        productTableBody.innerHTML = `
      <tr>
        <td colspan="6">
          <div style="text-align:center;padding:40px 20px;color:var(--c-muted)">
            <div style="width:48px;height:48px;border-radius:14px;background:var(--c-ocean-dim);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--c-ocean)">
              <i class="fas fa-search"></i>
            </div>
            <p style="font-size:.875rem;margin-bottom:0">
              Aucun étudiant trouvé pour "<strong style="color:var(--c-text)">${escHtml(query)}</strong>"
            </p>
          </div>
        </td>
      </tr>`;
    }

    if (searchInput && productTableBody) {
        searchInput.addEventListener('keyup', debounce(function () {
            const query = this.value.trim();

            if (query.length === 0) {
                // Rechargement complet si champ vidé
                location.reload();
                return;
            }

            showSearchLoading();

            // Endpoint api-search.php conservé identique à l'original
            fetch(`api-search.php?search=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.json();
                })
                .then(data => {
                    productTableBody.innerHTML = '';

                    if (data.length === 0) {
                        showSearchEmpty(query);
                        return;
                    }

                    // Mise à jour compteur (si présent)
                    const countEl = document.querySelector('.table-count strong');
                    if (countEl) countEl.textContent = data.length;

                    data.forEach((etudiant, i) => {
                        const row = buildStudentRow(etudiant);
                        row.style.transitionDelay = `${i * 30}ms`;
                        productTableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('CampusFlow Search Error:', error);
                    productTableBody.innerHTML = `
            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--c-danger)">
              <i class="fas fa-exclamation-triangle"></i>
              Erreur lors de la recherche. Veuillez réessayer.
            </td></tr>`;
                });
        }, 300));

        // Icône de recherche dans le champ si present
        const wrap = searchInput.closest('.search-wrap');
        if (!wrap) {
            // Wrapper auto si absent du HTML
            const w = document.createElement('div');
            w.className = 'search-wrap';
            w.style.position = 'relative';
            searchInput.parentNode.insertBefore(w, searchInput);
            w.appendChild(searchInput);
            const ico = document.createElement('i');
            ico.className = 'fas fa-search search-icon';
            ico.style.cssText = 'position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--c-muted);font-size:.78rem;pointer-events:none;';
            w.insertBefore(ico, searchInput);
        }
    }


    /* ════════════════════════════════════════════════════════════
       3. SCROLL PROGRESS BAR
    ════════════════════════════════════════════════════════════ */
    let progressBar = document.querySelector('.scroll-progress');
    if (!progressBar) {
        progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress';
        document.body.prepend(progressBar);
    }

    function updateScrollProgress() {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        progressBar.style.width = pct + '%';
    }

    window.addEventListener('scroll', updateScrollProgress, { passive: true });


    /* ════════════════════════════════════════════════════════════
       4. NAVBAR SHADOW AU SCROLL
    ════════════════════════════════════════════════════════════ */
    const navbar = document.querySelector('.navbar-cf, .navbar');
    if (navbar) {
        const onScroll = () => {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }


    /* ════════════════════════════════════════════════════════════
       5. RÉVÉLATION AU SCROLL (IntersectionObserver)
    ════════════════════════════════════════════════════════════ */
    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        // Auto-observation des cartes et sections sans classe .reveal
        document.querySelectorAll('.card-cf, .kpi-card, .prof-card, .hist-filter-card').forEach(el => {
            if (!el.classList.contains('reveal') && !el.classList.contains('anim-in')) {
                el.classList.add('reveal');
                revealObserver.observe(el);
            }
        });
    }


    /* ════════════════════════════════════════════════════════════
       6. HORLOGE EN DIRECT
       Met à jour tous les éléments avec id="dashClock" ou class="cf-clock"
    ════════════════════════════════════════════════════════════ */
    function updateClocks() {
        const now = new Date();
        const time = now.toLocaleTimeString('fr-FR');
        document.querySelectorAll('#dashClock, .cf-clock').forEach(el => {
            el.textContent = time;
        });
    }

    if (document.querySelector('#dashClock, .cf-clock')) {
        updateClocks();
        setInterval(updateClocks, 1000);
    }


    /* ════════════════════════════════════════════════════════════
       7. TOAST NOTIFICATIONS
       Usage : cfToast('Message', 'success' | 'danger' | 'info')
    ════════════════════════════════════════════════════════════ */
    window.cfToast = function (message, type = 'info', duration = 4000) {
        let container = document.querySelector('.cf-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'cf-toast-container';
            container.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none';
            document.body.appendChild(container);
        }

        const colors = {
            success: { bg: 'rgba(16,185,129,0.12)', border: 'rgba(16,185,129,0.3)', icon: '#10b981', ico: 'check-circle' },
            danger: { bg: 'rgba(220,38,38,0.10)', border: 'rgba(220,38,38,0.3)', icon: '#dc2626', ico: 'exclamation-circle' },
            info: { bg: 'rgba(26,79,212,0.10)', border: 'rgba(26,79,212,0.25)', icon: '#1a4fd4', ico: 'info-circle' },
            warning: { bg: 'rgba(232,160,32,0.10)', border: 'rgba(232,160,32,0.3)', icon: '#e8a020', ico: 'exclamation-triangle' },
        };
        const c = colors[type] || colors.info;

        const toast = document.createElement('div');
        toast.style.cssText = `
      display:flex;align-items:center;gap:10px;
      background:${c.bg};border:1.5px solid ${c.border};
      border-radius:12px;padding:12px 16px;
      backdrop-filter:blur(12px);
      box-shadow:0 8px 32px rgba(0,0,0,0.12);
      font-family:var(--font,'Outfit',sans-serif);
      font-size:.875rem;color:var(--c-text,#0d1730);
      pointer-events:all;min-width:240px;max-width:360px;
      opacity:0;transform:translateY(12px);
      transition:opacity 0.3s ease,transform 0.3s ease;
    `;
        toast.innerHTML = `
      <i class="fas fa-${c.ico}" style="color:${c.icon};font-size:.85rem;flex-shrink:0"></i>
      <span style="flex:1">${message}</span>
    `;

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'none';
        });

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(6px)';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    };


    /* ════════════════════════════════════════════════════════════
       8. ANIMATIONS BARRES DE PROGRESSION
       (barres dans le dashboard top cours)
    ════════════════════════════════════════════════════════════ */
    function animateBars() {
        document.querySelectorAll('.cours-bar-fill').forEach(bar => {
            const target = bar.dataset.width || bar.style.width;
            bar.style.width = '0%';
            bar.style.transition = 'width 1s cubic-bezier(0.16,1,0.3,1)';
            setTimeout(() => { bar.style.width = target; }, 200);
        });
    }
    animateBars();


    /* ════════════════════════════════════════════════════════════
       9. CONFIRMATION DE SUPPRESSION STYLISÉE
       Remplace les confirm() natifs du navigateur
    ════════════════════════════════════════════════════════════ */
    window.cfConfirm = function (message, href) {
        // Crée une modale légère non-bloquante
        const overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(5,14,36,0.55);backdrop-filter:blur(4px);z-index:10000;display:flex;align-items:center;justify-content:center;animation:fade-in .2s ease';
        overlay.innerHTML = `
      <div style="background:var(--c-surface,#fff);border:1.5px solid var(--c-border,rgba(26,79,212,.1));border-radius:20px;padding:28px 28px 22px;max-width:380px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);text-align:center;animation:card-in .3s cubic-bezier(.16,1,.3,1)">
        <div style="width:52px;height:52px;border-radius:14px;background:rgba(220,38,38,.1);border:1.5px solid rgba(220,38,38,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.3rem;color:#dc2626">
          <i class="fas fa-trash-alt"></i>
        </div>
        <h3 style="font-size:1rem;font-weight:700;color:var(--c-text,#0d1730);margin-bottom:8px">Confirmer la suppression</h3>
        <p style="font-size:.855rem;color:var(--c-muted,#6b7eab);margin-bottom:22px;line-height:1.5">${message}</p>
        <div style="display:flex;gap:10px;justify-content:center">
          <button id="cfCancelBtn" style="flex:1;padding:10px 18px;border-radius:10px;border:1.5px solid var(--c-border,rgba(26,79,212,.1));background:var(--c-ocean-dim,rgba(26,79,212,.08));color:var(--c-ocean,#1a4fd4);font-weight:600;font-size:.875rem;cursor:pointer;font-family:inherit">
            Annuler
          </button>
          <button id="cfConfirmBtn" style="flex:1;padding:10px 18px;border-radius:10px;border:none;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;font-weight:700;font-size:.875rem;cursor:pointer;font-family:inherit;box-shadow:0 3px 14px rgba(220,38,38,.35)">
            <i class="fas fa-trash-alt"></i> Supprimer
          </button>
        </div>
      </div>`;

        document.body.appendChild(overlay);

        overlay.querySelector('#cfCancelBtn').addEventListener('click', () => overlay.remove());
        overlay.querySelector('#cfConfirmBtn').addEventListener('click', () => {
            window.location.href = href;
        });
        overlay.addEventListener('click', e => { if (e.target === overlay) overlay.remove(); });

        return false; // empêche le comportement natif du lien
    };

    // Intercepter les liens de suppression existants
    document.querySelectorAll('a[href*="action=delete"]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            cfConfirm('Cette action est irréversible. L\'entrée sera définitivement supprimée.', this.href);
        });
    });


    /* ════════════════════════════════════════════════════════════
       10. AUTO-DISMISS DES ALERTES
       Les alertes Bootstrap disparaissent après 5 secondes
    ════════════════════════════════════════════════════════════ */
    document.querySelectorAll('.alert.alert-success, .alert-cf-success').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease, max-height 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });


    /* ════════════════════════════════════════════════════════════
       11. HIGHLIGHT LIGNE TABLEAU AU CLIC
    ════════════════════════════════════════════════════════════ */
    document.querySelectorAll('.cf-table tbody tr, #students-table tbody tr').forEach(row => {
        row.addEventListener('click', function (e) {
            // Ne pas déclencher si l'utilisateur clique sur un bouton/lien
            if (e.target.closest('a, button')) return;
            document.querySelectorAll('.cf-table tbody tr.selected, #students-table tbody tr.selected')
                .forEach(r => r.classList.remove('selected'));
            this.classList.toggle('selected');
        });
    });


    /* ════════════════════════════════════════════════════════════
       12. COMPTEUR ANIMÉ (KPI cards)
    ════════════════════════════════════════════════════════════ */
    function animateCounter(el, target, duration = 1200) {
        const start = performance.now();
        const initial = parseInt(el.textContent.replace(/\s/g, '')) || 0;

        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
            const value = Math.round(initial + (target - initial) * ease);
            el.textContent = value.toLocaleString('fr-FR');
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    if ('IntersectionObserver' in window) {
        const kpiObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.dataset.target || el.textContent.replace(/\s/g, '')) || 0;
                    if (target > 0) animateCounter(el, target);
                    kpiObserver.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.kpi-value, .qs-value').forEach(el => {
            const val = parseInt(el.textContent.replace(/\s/g, ''));
            if (val > 0) {
                el.dataset.target = val;
                el.textContent = '0';
                kpiObserver.observe(el);
            }
        });
    }


    /* ════════════════════════════════════════════════════════════
       13. FORMULAIRE — LOADING STATE AU SUBMIT
    ════════════════════════════════════════════════════════════ */
    document.querySelectorAll('form:not(#loginForm)').forEach(form => {
        form.addEventListener('submit', function () {
            const btn = this.querySelector('button[type="submit"]');
            if (btn && this.checkValidity()) {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="cf-spinner"></span> Enregistrement…';
                btn.disabled = true;
                // Sécurité : réactiver après 8 sec si pas de navigation
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                }, 8000);
            }
        });
    });

}); // fin DOMContentLoaded