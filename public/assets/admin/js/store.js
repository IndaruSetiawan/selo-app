const $ = id => document.getElementById(id);
const rows = () => document.querySelectorAll('#menu-body tr');

/* Search */
function filterRows() {
    const search = $('search');
    const q = search ? search.value.trim().toLowerCase() : '';
    let shown = 0;

    rows().forEach(tr => {
        const match = (tr.dataset.search || '').includes(q);
        tr.style.display = match ? '' : 'none';
        if (match) shown++;
    });

    const empty = $('empty');
    if (empty && rows().length) {
        empty.classList.toggle('d-none', shown > 0);
    }
}

const searchInput = $('search');
if (searchInput) searchInput.addEventListener('input', filterRows);

/* Modal hanya ada kalau akun boleh mengubah menu */
const menuModalEl = $('menuModal');
const deleteModalEl = $('deleteModal');

if (menuModalEl && deleteModalEl) {
    const menuModal = bootstrap.Modal.getOrCreateInstance(menuModalEl);
    const deleteModal = bootstrap.Modal.getOrCreateInstance(deleteModalEl);
    const DEFAULT_HINT = 'JPG, PNG, or WEBP, max 2 MB';

    function openForm(data) {
        $('menu-form').reset();
        $('f-id').value = data ? data.id : '';          
        $('menuModalTitle').textContent = data ? 'Edit Menu' : 'Add Menu';
        $('f-submit').textContent = data ? 'Save' : 'Add Menu';
        $('f-picture-hint').textContent = data && data.img
            ? 'Current: ' + data.img + ' (choose a file to replace)'
            : DEFAULT_HINT;

        if (data) {
            $('f-name').value = data.name;
            $('f-category').value = data.category;
            $('f-desc').value = data.desc;
            $('f-cost').value = data.cost;
            $('f-price').value = data.price;
        }
        menuModal.show();
    }

    $('btn-add').addEventListener('click', () => openForm(null));

    $('menu-body').addEventListener('click', e => {
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;

        if (btn.dataset.action === 'edit') {
            openForm({
                id: btn.dataset.id, name: btn.dataset.name, category: btn.dataset.category,
                desc: btn.dataset.desc, cost: btn.dataset.cost, price: btn.dataset.price,
                img: btn.dataset.img,
            });
        } else {
            $('delete-id').value = btn.dataset.id;
            deleteModal.show();
        }
    });
}