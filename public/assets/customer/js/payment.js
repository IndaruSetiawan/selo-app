function renderServerOrder(info) {
    setText('summary-order-id', info.orderCode || '-');
    setText('summary-order-date', info.orderDate || '-');
    if (info.totals) {
        setText('summary-subtotal', rupiahFormater(info.totals.subtotal));
        setText('summary-tax', rupiahFormater(info.totals.tax));
        setText('summary-discount', rupiahFormater(info.totals.discount));
        setText('summary-total', rupiahFormater(info.totals.total));
    }
}

function waitForPayment(orderId, onPaid, onCanceled) {
    const timer = setInterval(async () => {
        try {
            const res = await apiGet('/order/status?id=' + encodeURIComponent(orderId));
            if (res.state === 'paid') {
                clearInterval(timer);
                onPaid(res);
            } else if (res.state === 'canceled') {
                clearInterval(timer);
                onCanceled();
            }
        } catch {
            // koneksi putus sebentar: coba lagi pada tick berikutnya
        }
    }, 3000);
}

function initPayment() {
    const confirmEl = document.getElementById('paymentModal');
    const successEl = document.getElementById('successModal');
    const waitingEl = document.getElementById('waitingModal');
    const confirmBtn = document.getElementById('btn-confirm-pay');
    if (!confirmEl || !successEl || !waitingEl || !confirmBtn) return;

    const confirmModal = bootstrap.Modal.getOrCreateInstance(confirmEl);
    const successModal = bootstrap.Modal.getOrCreateInstance(successEl);
    const waitingModal = bootstrap.Modal.getOrCreateInstance(waitingEl);

    function showSuccess(amount) {
        setText('success-total', rupiahFormater(amount));
        successModal.show();
        clearCart();
        localStorage.removeItem(ORDER_KEY_STORE);
    }

    confirmEl.addEventListener('show.bs.modal', () => {
        const selected = document.querySelector('input[name="payment"]:checked');
        const iconBox = document.getElementById('pay-icon');

        if (selected) {
            const sourceIcon = selected.closest('.payment-card').querySelector('.icon-box');

            document.getElementById('pay-method').textContent = selected.dataset.name;
            iconBox.innerHTML = sourceIcon.innerHTML;
            iconBox.classList.remove('d-none');
        } else {
            document.getElementById('pay-method').textContent = '-';
            iconBox.innerHTML = '';
            iconBox.classList.add('d-none');
        }
        const info = getOrderInfo();
        setText('pay-total', rupiahFormater(info.totals?.total ?? calcTotals().total));
    });

    confirmBtn.addEventListener('click', async () => {
        const selected = document.querySelector('input[name="payment"]:checked');
        const info = getOrderInfo();

        if (!selected) {
            confirmModal.hide();
            return alert('Choose payment methods first.');
        }
        if (!info.orderId) return;

        confirmBtn.disabled = true;
        try {
            const res = await apiPost('/order/pay', {
                id_order: info.orderId,
                method: selected.dataset.method,
            });

            confirmEl.addEventListener('hidden.bs.modal', () => {
                if (res.state === 'paid') {
                    return showSuccess(res.paid_amount);
                }

                setText('waiting-total', rupiahFormater(res.paid_amount));
                setText('waiting-method', selected.dataset.name);
                setText('waiting-code', info.orderCode || '');
                waitingModal.show();

                waitForPayment(info.orderId,
                    (r) => {
                        waitingEl.addEventListener('hidden.bs.modal', () => showSuccess(r.paid_amount), { once: true });
                        waitingModal.hide();
                    },
                    () => {
                        waitingModal.hide();
                        alert('This order has been canceled.');
                        window.location.replace('/');
                    }
                );
            }, { once: true });
            confirmModal.hide();
        } catch (err) {
            alert(err.message);
            confirmBtn.disabled = false;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const info = getOrderInfo();
    if (!info.orderId) {
        window.location.replace('/');
        return;
    }
    initPayment();
    renderOrderSummary();
    renderServerOrder(info);
});