function renderCartSidebar() {
    const box =document.getElementById('cart-items');
    if(!box) return;

    const cart = getCart();
    box.innerHTML = cart.length
        ? cart.map(i => `
            <div class="card border-0 rounded-4 p-2 mx-2 my-1" data-id="${i.id}">
                <div class="menu-list d-flex flex-shrink-0 overflow-hidden align-items-center">
                    <div class="img-card bg-light rounded-4 d-flex justify-content-center align-items-center flex-shrink-0 border-0 mx-2">
                        <img class="object-fit-contain p-2" src="${i.image}" alt="${i.name}">
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <div class="mt-2">
                            <h3>${i.name}</h3>
                            <p>${rupiahFormater(i.price)}</p>
                        </div>
                        <div class="quantity d-flex align-self-end justify-content-center align-items-center rounded-pill px-2 gap-2 mb-2">
                            <button class="btn btn-sm btn-white bg-white rounded-circle shadow-sm fw-bold border-0" data-action="minus">-</button>
                            <span class="fw-bold text-dark p-2">${i.qty}</span>
                            <button class="btn btn-sm btn-white bg-white rounded-circle shadow-sm fw-bold border-0" data-action="plus">+</button>
                        </div>
                    </div>
                </div>
            </div>`).join('')
        : '<p class="text-center text-muted small mt-4">Cart masih kosong</p>';

        const t = calcTotals(cart);
        setText('cart-subtotal', rupiahFormater(t.subtotal));
        setText('cart-tax', rupiahFormater(t.tax));
        setText('cart-discount', rupiahFormater(t.discount));
        setText('cart-total', rupiahFormater(t.total));
}

function initCart() {
    const cartBtn = document.getElementById('cart-btn');
    const cartSidebar = document.getElementById('cart-sidebar');

    if (!cartBtn || !cartSidebar) return;

    cartBtn.addEventListener('click', () => {
        cartSidebar.classList.toggle('open');
        document.body.classList.toggle('cart-open');
    });
}

function initOrderType() {
    const btns = document.querySelectorAll('.order-type .btn');
    const nameInput = document.querySelector('#cart-sidebar .customer-name');
    const saved = getOrderInfo();

    if (nameInput && saved.customerName) nameInput.value = saved.customerName;
    btns.forEach(b => b.classList.toggle('active', b.dataset.type === saved.orderType));

    if (nameInput) {
        nameInput.addEventListener('input', () => {
            saveOrderInfo({ customerName: nameInput.value.trim() });
        });
    }

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            btns.forEach(b => b.classList.toggle('active'));
            if (!document.querySelector('.order-type .btn.active') && btns[0]) {
                btn.classList.add('active');
                saveOrderInfo({ orderType: btn.dataset.type });
            }
        });
    });
}

function initCartItems() {
    const box = document.getElementById('cart-items');
    if (!box) return;

    box.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action]');
        const card = e.target.closest('[data-id]');
        if (!btn || !card) return;
        changeQty(card.dataset.id, btn.dataset.action === 'plus' ? 1 : -1);
    });
}

function goToOrderPage() {
    const hero = document.getElementById('welcome');
    const cartBtn = document.getElementById('cart-btn');
    if (!hero) return;

    hero.classList.add('fade-out');

    setTimeout(() => {
        hero.style.display = 'none';
        document.body.classList.remove('locked');
        document.documentElement.classList.remove('locked');
        window.scrollTo(0, 0);
        if (cartBtn) {
            cartBtn.classList.remove('is-hidden');
        }
    }, 400);
}

function backToWelcome() {
    const hero = document.getElementById('welcome');
    const cartBtn = document.getElementById('cart-btn');
    if (!hero) return;

    hero.style.display = '';            // munculkan lagi (kembali ke d-flex)
    hero.classList.remove('fade-out');
    document.body.classList.add('locked');
    document.documentElement.classList.add('locked');
    cartBtn?.classList.add('is-hidden');
    window.scrollTo(0, 0);
}

function cancelOrder() {
    const hasItems = getCart().length > 0;
    if (hasItems && !confirm('Batalkan pesanan? Semua item di keranjang akan dihapus.')) return;

    clearCart();
    document.getElementById('cart-sidebar')?.classList.remove('open');

    const nameInput = document.querySelector('#cart-sidebar .customer-name');
    if (nameInput) nameInput.value = '';

    const search = document.querySelector('#orders input[type="text"]');
    if (search) search.value = '';
    document.querySelector('#category .category-card[data-category="all"]')?.click()

    backToWelcome();
}

const GoToOrderPage = goToOrderPage;

document.addEventListener('DOMContentLoaded', () => {
    initMenu();
    initDetailMenuModal();
    initCart();
    initCartItems();
    initOrderType();
    initCategoryFilter();
    renderCartSidebar();
});