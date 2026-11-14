const CART_KEY = 'selo_cart';
const ORDER_KEY = 'selo_order';
const TAX_RATE = 0.11;

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, c => (
    { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
));

const rupiahFormater = (n) => "Rp " + Number(n).toLocaleString("id-ID", { maximumFractionDigits: 0 });

const setText = (id, text) => {
    const el = document.getElementById(id);
    if(el) el.textContent = text;
};

function getCart() {
    try {return JSON.parse(localStorage.getItem(CART_KEY)) || []; }
    catch {return [];}
}

function getOrderInfo() {
    try { return JSON.parse(localStorage.getItem(ORDER_KEY)) || {}; }
    catch { return {}; }
}

function saveOrderInfo(patch) {
    localStorage.setItem(ORDER_KEY, JSON.stringify({ ...getOrderInfo(), ...patch }));
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    renderALL();
}

function clearCart() {
    localStorage.removeItem(CART_KEY);
    localStorage.removeItem(ORDER_KEY);
    localStorage.removeItem('selo_order_key');
    renderALL();
}

function calcTotals(cart = getCart()) {
    const subtotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
    const tax = Math.round(subtotal * TAX_RATE);
    const discount = 0;
    return{subtotal, tax, discount, total: subtotal + tax - discount};
}

function addToCart(item, qty = 1, notes = '') {
    const cart = getCart();
    const found = cart.find(i => i.id === item.id);
    if (found) {
        found.qty += qty;
        if(notes) found.notes = notes;
    } else {
        cart.push({ id: item.id, name: item.name, price: item.price, image: item.image, qty, notes });
    }
    saveCart(cart);
}

function changeQty(id, delta) {
    let cart = getCart();
    const item = cart.find(i => i.id === id);
    if(!item) return;
    item.qty += delta;
    if(item.qty <= 0) cart = cart.filter(i => i.id !== id);
    saveCart(cart);
}

function renderOrderSummary() {
    const info = getOrderInfo();
    setText('summary-customer', info.customerName || '-');
    setText('summary-order-type', info.orderType === 'take-away' ? 'Take Away'
                            : info.orderType === 'dine-in' ? 'Dine In' : '-');

    const list = document.getElementById('summary-items');
    if(!list) return;

    const cart = getCart();
    list.innerHTML = cart.map(i => `
        <li class="d-flex justify-content-between">
            <span>${escapeHtml(i.name)} x${i.qty}</span>
            <span>${rupiahFormater(i.price * i.qty)}</span>
        </li>`).join('');

    const t = calcTotals(cart);
    setText('summary-subtotal', rupiahFormater(t.subtotal));
    setText('summary-tax', rupiahFormater(t.tax));
    setText('summary-discount', rupiahFormater(t.discount));
    setText('summary-total', rupiahFormater(t.total));
};

function renderALL() {
    if (typeof renderCartSidebar === 'function') renderCartSidebar();
    renderOrderSummary();
}

function initClock() {
    const dateEl = document.getElementById('live-date');
    const timeEl = document.getElementById('live-time');
    if (!dateEl && !timeEl) return;

    const tz = 'Asia/Jakarta';

    const dateFmt = new Intl.DateTimeFormat('en-US', {
        timeZone: tz, weekday: 'short', month: 'short', day: '2-digit', year: 'numeric',
    });
    const timeFmt = new Intl.DateTimeFormat('en-US', {
        timeZone: tz, hour: '2-digit', minute: '2-digit', hour12: true,
    });

    function tick() {
        const now = new Date();
        if (dateEl) dateEl.textContent = dateFmt.format(now).replace(/,(?=\s\d{4}$)/, '');
        if (timeEl) timeEl.textContent = timeFmt.format(now);
    }

    tick();
    setInterval(tick, 1000);
}
