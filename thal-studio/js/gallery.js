(() => {
  const sectionsEl = document.getElementById('gallerySections');
  const autoZone = document.getElementById('autoDropzone');
  const autoInput = document.getElementById('autoFileInput');
  const newCategoryForm = document.getElementById('newCategoryForm');
  const newCategoryName = document.getElementById('newCategoryName');
  const categoryStatus = document.getElementById('categoryStatus');
  const progressWrap = document.getElementById('uploadProgressWrap');
  const progressFill = document.getElementById('uploadProgressFill');
  const progressLabel = document.getElementById('uploadProgressLabel');

  const esc = s => String(s ?? '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m]));
  const csrfHeaders = (extra = {}) => Object.assign({ 'X-CSRF-Token': window.THAL_CSRF_TOKEN || '' }, extra);

  function photoSrc(category, file) {
    return `../photos/${encodeURIComponent(category)}/${encodeURIComponent(file)}`;
  }

  function moveOptionsMarkup(category, allCategories) {
    const options = allCategories
      .filter(c => c !== category)
      .map(c => `<option value="${esc(c)}">${esc(c)}</option>`)
      .join('');
    return `<select class="gallery-thumb-move" aria-label="Déplacer vers"><option value="">Déplacer vers…</option>${options}</select>`;
  }

  function categoryMarkup(category, files, allCategories) {
    const thumbs = files.map(file => `
      <figure class="gallery-thumb" draggable="true" data-file="${esc(file)}">
        <img src="${photoSrc(category, file)}" alt="${esc(file)}" loading="lazy">
        <button type="button" class="gallery-thumb-delete" aria-label="Supprimer">×</button>
        <figcaption>${esc(file)}</figcaption>
        ${moveOptionsMarkup(category, allCategories)}
      </figure>`).join('');

    const empty = files.length ? '' : '<p class="empty-state gallery-empty">Aucune photo. Glisse-en ici ou utilise « Ajouter ici ».</p>';
    const deleteCatBtn = files.length ? '' : '<button type="button" class="button small danger gallery-delete-cat">Supprimer la catégorie</button>';

    return `
      <section class="gallery-category" data-category="${esc(category)}">
        <div class="gallery-category-head">
          <h3>${esc(category)} <span class="badge muted">${files.length}</span></h3>
          <div class="gallery-category-actions">
            <label class="button small">
              Ajouter ici
              <input type="file" class="gallery-cat-input" accept="image/*" multiple hidden>
            </label>
            ${deleteCatBtn}
          </div>
        </div>
        <div class="gallery-grid" data-category="${esc(category)}">
          ${thumbs}${empty}
        </div>
      </section>`;
  }

  function renderData(data) {
    const allCategories = Object.keys(data);
    sectionsEl.innerHTML = allCategories.map(cat => categoryMarkup(cat, data[cat] || [], allCategories)).join('');
    bindSections();
  }

  function setStatus(msg, isError) {
    if (!categoryStatus) return;
    categoryStatus.textContent = msg;
    categoryStatus.style.color = isError ? '#ff9c9c' : '';
  }

  function formatSize(bytes) {
    if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' Ko';
    return (bytes / (1024 * 1024)).toFixed(1) + ' Mo';
  }

  function showProgress(percent, label) {
    if (!progressWrap) return;
    progressWrap.hidden = false;
    progressFill.style.width = percent + '%';
    progressLabel.textContent = label;
  }

  function hideProgress() {
    if (!progressWrap) return;
    progressWrap.hidden = true;
    progressFill.style.width = '0%';
  }

  function uploadFiles(fileList, category) {
    if (!fileList || !fileList.length) return;
    const files = [...fileList];
    const totalBytes = files.reduce((sum, f) => sum + f.size, 0);
    const fd = new FormData();
    fd.append('csrf', window.THAL_CSRF_TOKEN || '');
    fd.append('category', category || '__auto__');
    files.forEach(f => fd.append('files[]', f));

    setStatus('Envoi en cours…', false);
    showProgress(0, `0% — 0 Ko / ${formatSize(totalBytes)} (${files.length} photo${files.length > 1 ? 's' : ''})`);

    return new Promise(resolve => {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'gallery_upload.php');

      xhr.upload.addEventListener('progress', e => {
        if (!e.lengthComputable) return;
        const percent = Math.round((e.loaded / e.total) * 100);
        showProgress(percent, `${percent}% — ${formatSize(e.loaded)} / ${formatSize(e.total)} (${files.length} photo${files.length > 1 ? 's' : ''})`);
      });

      xhr.addEventListener('load', () => {
        hideProgress();
        try {
          const result = JSON.parse(xhr.responseText);
          if (result.ok) {
            renderData(result.data);
            const failed = (result.results || []).filter(r => !r.ok);
            setStatus(failed.length ? `Envoyé avec ${failed.length} erreur(s).` : 'Photos ajoutées.', failed.length > 0);
          } else {
            setStatus(result.error || 'Échec de l’envoi.', true);
          }
        } catch (e) {
          setStatus('Réponse du serveur invalide.', true);
        }
        resolve();
      });

      xhr.addEventListener('error', () => {
        hideProgress();
        setStatus('Erreur réseau pendant l’envoi.', true);
        resolve();
      });

      xhr.send(fd);
    });
  }

  async function deletePhoto(category, file) {
    if (!confirm(`Supprimer « ${file} » ?`)) return;
    try {
      const res = await fetch('gallery_delete.php', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ category, file })
      });
      const result = await res.json();
      if (result.ok) renderData(result.data);
      else setStatus(result.error || 'Suppression impossible.', true);
    } catch (e) {
      setStatus('Erreur réseau pendant la suppression.', true);
    }
  }

  async function movePhoto(file, fromCategory, toCategory, targetIndex) {
    try {
      const res = await fetch('gallery_reorder.php', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ file, fromCategory, toCategory, targetIndex })
      });
      const result = await res.json();
      if (result.ok) renderData(result.data);
      else setStatus(result.error || 'Déplacement impossible.', true);
    } catch (e) {
      setStatus('Erreur réseau pendant le déplacement.', true);
    }
  }

  async function createCategory(name) {
    try {
      const res = await fetch('gallery_category.php', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ action: 'create', name })
      });
      const result = await res.json();
      if (result.ok) {
        renderData(result.data);
        setStatus(`Catégorie « ${name} » créée.`, false);
        newCategoryName.value = '';
      } else {
        setStatus(result.error || 'Création impossible.', true);
      }
    } catch (e) {
      setStatus('Erreur réseau pendant la création.', true);
    }
  }

  async function deleteCategory(name) {
    if (!confirm(`Supprimer la catégorie « ${name} » ?`)) return;
    try {
      const res = await fetch('gallery_category.php', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ action: 'delete', name })
      });
      const result = await res.json();
      if (result.ok) renderData(result.data);
      else setStatus(result.error || 'Suppression impossible.', true);
    } catch (e) {
      setStatus('Erreur réseau pendant la suppression.', true);
    }
  }

  function insertionIndex(grid, clientY, clientX) {
    const thumbs = [...grid.querySelectorAll('.gallery-thumb:not(.dragging)')];
    for (let i = 0; i < thumbs.length; i++) {
      const r = thumbs[i].getBoundingClientRect();
      const centerY = r.top + r.height / 2;
      if (clientY < centerY - r.height / 3) return i;
      if (clientY < centerY + r.height / 3 && clientX < r.left + r.width / 2) return i;
    }
    return thumbs.length;
  }

  function bindSections() {
    sectionsEl.querySelectorAll('.gallery-thumb-delete').forEach(btn => {
      btn.addEventListener('click', () => {
        const thumb = btn.closest('.gallery-thumb');
        const grid = btn.closest('.gallery-grid');
        deletePhoto(grid.dataset.category, thumb.dataset.file);
      });
    });

    sectionsEl.querySelectorAll('.gallery-delete-cat').forEach(btn => {
      btn.addEventListener('click', () => {
        const section = btn.closest('.gallery-category');
        deleteCategory(section.dataset.category);
      });
    });

    sectionsEl.querySelectorAll('.gallery-cat-input').forEach(input => {
      input.addEventListener('change', () => {
        const section = input.closest('.gallery-category');
        uploadFiles(input.files, section.dataset.category);
        input.value = '';
      });
    });

    sectionsEl.querySelectorAll('.gallery-thumb-move').forEach(select => {
      select.addEventListener('click', e => e.stopPropagation());
      select.addEventListener('change', () => {
        const toCategory = select.value;
        if (!toCategory) return;
        const thumb = select.closest('.gallery-thumb');
        const grid = select.closest('.gallery-grid');
        const targetGrid = sectionsEl.querySelector(`.gallery-grid[data-category="${CSS.escape(toCategory)}"]`);
        const targetIndex = targetGrid ? targetGrid.querySelectorAll('.gallery-thumb').length : 0;
        movePhoto(thumb.dataset.file, grid.dataset.category, toCategory, targetIndex);
      });
    });

    sectionsEl.querySelectorAll('.gallery-thumb').forEach(thumb => {
      thumb.addEventListener('dragstart', e => {
        thumb.classList.add('dragging');
        const grid = thumb.closest('.gallery-grid');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('application/x-thal-photo', JSON.stringify({
          file: thumb.dataset.file,
          fromCategory: grid.dataset.category
        }));
      });
      thumb.addEventListener('dragend', () => thumb.classList.remove('dragging'));
    });

    sectionsEl.querySelectorAll('.gallery-grid').forEach(grid => {
      grid.addEventListener('dragover', e => {
        e.preventDefault();
        e.dataTransfer.dropEffect = e.dataTransfer.types.includes('Files') ? 'copy' : 'move';
        grid.classList.add('dragover');
      });
      grid.addEventListener('dragleave', () => grid.classList.remove('dragover'));
      grid.addEventListener('drop', e => {
        e.preventDefault();
        grid.classList.remove('dragover');

        if (e.dataTransfer.files && e.dataTransfer.files.length) {
          uploadFiles(e.dataTransfer.files, grid.dataset.category);
          return;
        }

        const raw = e.dataTransfer.getData('application/x-thal-photo');
        if (!raw) return;
        const payload = JSON.parse(raw);
        const targetIndex = insertionIndex(grid, e.clientY, e.clientX);
        movePhoto(payload.file, payload.fromCategory, grid.dataset.category, targetIndex);
      });
    });
  }

  if (autoZone) {
    autoZone.addEventListener('dragover', e => { e.preventDefault(); autoZone.classList.add('dragover'); });
    autoZone.addEventListener('dragleave', () => autoZone.classList.remove('dragover'));
    autoZone.addEventListener('drop', e => {
      e.preventDefault();
      autoZone.classList.remove('dragover');
      if (e.dataTransfer.files && e.dataTransfer.files.length) {
        uploadFiles(e.dataTransfer.files, '__auto__');
      }
    });
  }

  if (autoInput) {
    autoInput.addEventListener('change', () => {
      uploadFiles(autoInput.files, '__auto__');
      autoInput.value = '';
    });
  }

  if (newCategoryForm) {
    newCategoryForm.addEventListener('submit', e => {
      e.preventDefault();
      const name = newCategoryName.value.trim();
      if (name) createCategory(name);
    });
  }

  bindSections();
})();
