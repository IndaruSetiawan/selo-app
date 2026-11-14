const ORDER_KEY_STORE = 'selo_order_key';

function uuidv4() {
    const b = crypto.getRandomValues(new Uint8Array(16));
    b[6] = (b[6] & 0x0f) | 0x40;
    b[8] = (b[8] & 0x3f) | 0x80;
    const h = [...b].map(x => x.toString(16).padStart(2, '0'));
    return `${h.slice(0, 4).join('')}-${h.slice(4, 6).join('')}-${h.slice(6, 8).join('')}-${h.slice(8, 10).join('')}-${h.slice(10).join('')}`;
}

async function apiPost(url, payload) {
    let res, data = null;
    try {
        res = await fetch(url, {
            method : 'POST',
            headers: {
                'Content-type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': csrfToken(),
            },
            body: JSON.stringify(payload),
        });
    } catch {
        throw new Error('Unable to connect to the server. Check your connection and try again');
    }
    try { data = await res.json();} catch { }
    if (!res.ok || !data || data.ok === false) {
        throw new Error(data?.message || 'An error occurred. Please try again.');
    }
    return data;
}

async function apiGet(url) {
    let res, data = null;
    try {
        res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    } catch {
        throw new Error('Unable to connect to the server. Check your connection and try again');
    }
    try { data = await res.json(); } catch { }
    if (!res.ok || !data || data.ok === false) {
        throw new Error(data?.message || 'An error occurred. Please try again.');
    }
    return data;
}

function orderKeyFor(signature) {
    try {
        const saved = JSON.parse(localStorage.getItem(ORDER_KEY_STORE));
        if (saved && saved.sig === signature && saved.key) return saved.key;
    } catch { }
    const key = uuidv4();
    localStorage.setItem(ORDER_KEY_STORE, JSON.stringify({ sig: signature, key}));
    return key;
}

async function placeOrder(btn) {
    const cart = getCart();
    const name = (document.querySelector('#cart-sidebar .customer-name')?.value || '');
    const type = document.querySelector('.order-type .btn.active')?.dataset.type || '';

    if (!cart.length) return alert ('The cart is empty.');
    if (!name) return alert('Please enter customers name first.');
    if (!type) return alert('Please select Dine In or Take Away.');

    const items = cart.map(i => ({id: Number(i.id), qty: Number(i.qty), notes: i.notes || '' }));
    const key = orderKeyFor(JSON.stringify([name, type, items]));

    btn.classList.add('disabled');
    btn.setAttribute('aria-disabled', 'true');
    try{
        const { order } = await apiPost('/order', { key, customer_name: name, order_type: type, items});
        saveOrderInfo({
            customerName: name,
            orderType: type,
            orderId: order.id,
            orderCode: order.code,
            orderDate: order.date,
            totals: order.totals,
        });
        window.location.href = '/payment';
    } catch (err) {
        alert(err.message);
        btn.classList.remove('disabled');
        btn.removeAttribute('aria-disabled');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('place-order-btn');
    if (!btn) return;
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        if(btn.classList.contains('disabled')) return;
        placeOrder(btn);
    });
});