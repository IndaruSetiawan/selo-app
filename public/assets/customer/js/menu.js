const menuData = Array.isArray(window.MENU_DATA) ? window.MENU_DATA : [];

const menuCardTemplate = (item) => `
    <div class="menu-card p-3 text-start rounded-4 border-0"
            data-id="${escapeHtml(item.id)}"
            data-category="${escapeHtml(item.category)}"
            data-bs-toggle="modal"
            data-bs-target="#detailMenuModal">
        <div class="img-card d-flex p-4 justify-content-center align-items-center mb-3">
            <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}">
        </div>
        <h3 class="fw-bold mb-4 mt-2">${escapeHtml(item.name)}</h3>
        <small class="text-end d-block fw-semibold">${rupiahFormater(item.price)}</small>
    </div>
`;

function initMenu() {
    const menuEl = document.getElementById('menu');
    if(!menuEl) return;

    menuEl.innerHTML = menuData.map(menuCardTemplate).join('')
}

function initCategoryFilter() {
    const categoryCards = document.querySelectorAll('#category .category-card');
    const searchInput = document.querySelector('#orders input[type="text"]');
    if (!categoryCards.length) return;

    const counts = { all: menuData.length, dish: 0, snack: 0, drink: 0 };
    menuData.forEach(m => {
        if (counts[m.category] !== undefined) counts[m.category]++;
    });

    categoryCards.forEach(card => {
        const countEl = card.querySelector('small');
        const n = counts[card.dataset.category];
        if (countEl && n !== undefined) countEl.textContent = `${n} Items`;
    });

    function filterMenus() {
        const active = document.querySelector('#category .category-card.active');
        const selected = active ? active.dataset.category : 'all';
        const q = searchInput ? searchInput.value.toLowerCase().trim() : '';

        // ambil kartu saat filter dijalankan, bukan di awal
        document.querySelectorAll('#menu .menu-card').forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const okCat = selected === 'all' || card.dataset.category === selected;
            const okSearch = !q || title.includes(q);
            card.style.display = (okCat && okSearch) ? '' : 'none';
        });
    }

    categoryCards.forEach(card => {
        card.addEventListener('click', () => {
            categoryCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            filterMenus();
        });
    });

    searchInput?.addEventListener('input', filterMenus);
}

function initDetailMenuModal() {
    const detailMenuModal = document.getElementById('detailMenuModal');
    if (!detailMenuModal) return;

    let currentItem = null;
    let currentQty = 1;

    const modalTitle = document.getElementById('modal-title');
    const modalPrice = document.getElementById('modal-price');
    const modalBtnPrice = document.getElementById('modal-btn-price');
    const modalImg = document.getElementById('modal-img');
    const modalDesc = document.getElementById('modal-desc');
    const modalQty = document.getElementById('modal-qty');
    const minusBtn = document.getElementById('modal-qty-minus');
    const plusBtn = document.getElementById('modal-qty-plus');
    const addBtn = document.getElementById('modal-add-btn');
    const notesTextarea = detailMenuModal.querySelector('.notes textarea');

    const updateBtnPrice = () => {
        if (modalBtnPrice && currentItem) {
            modalBtnPrice.textContent = rupiahFormater(currentItem.price * currentQty);
        }
    };

    detailMenuModal.addEventListener('show.bs.modal', (event) => {
        const card = event.relatedTarget ? event.relatedTarget.closest('.menu-card') : null;
        if (!card) return;

        currentItem = menuData.find(m => m.id === card.dataset.id) || null;
        if (!currentItem) return;

        if (modalTitle) modalTitle.textContent = currentItem.name;
        if (modalPrice) modalPrice.textContent = rupiahFormater(currentItem.price);
        if (modalImg) modalImg.src = currentItem.image;
        if (modalDesc) modalDesc.textContent = currentItem.desc || '';

        currentQty = 1;
        if (modalQty) modalQty.textContent = currentQty;
        if (notesTextarea) notesTextarea.value = '';
        updateBtnPrice();
    });

    if (minusBtn) {
        minusBtn.addEventListener('click', () => {
            if (currentQty > 1) {
                currentQty--;
                modalQty.textContent = currentQty;
                updateBtnPrice();
            }
        });
    }

    if (plusBtn) {
        plusBtn.addEventListener('click', () => {
            currentQty++;
            modalQty.textContent = currentQty;
            updateBtnPrice();
        });
    }

    if (addBtn) {
        addBtn.addEventListener('click', () => {
            if (!currentItem) return;
            addToCart(currentItem, currentQty, notesTextarea?.value.trim() || '');
            bootstrap.Modal.getInstance(detailMenuModal)?.hide();
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initMenu();
    initCategoryFilter();
    initDetailMenuModal();
    initClock();
});
