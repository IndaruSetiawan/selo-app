<main class="pt-2 container-fluid">
        <div class="row g-0 min-vh-100">
            <section class="col-lg-8 bg-light p-4">
                <header class="mb-3">
                    <h1 class="brand">Our UMKM</h1>
                    <h2 class="h5">Payment Methods</h2>
                </header>
                <!-- Payment Methods -->
                <fieldset class="mx-3">
                        <legend class="small">Bank Transfer</legend>
                        <label class="payment-card d-flex align-items-center p-3 bg-white rounded mb-2">
                            <input type="radio" name="payment" data-name="BCA Virtual Account" data-method="bca-va" class="form-check-input me-3">
                            <span class="icon-box me-3"><i class="bi bi-bank" ></i></span>
                            <span>
                                <strong class="d-block">BCA Virtual Account</strong>
                                <small class="text-muted">Transfer to Virtual Account Number</small>
                            </span>
                        </label>
                        <label class="payment-card d-flex align-items-center p-3 bg-white rounded mb-2">
                            <input type="radio" name="payment" data-name="Mandiri Virtual Account" data-method="mandiri-va" class="form-check-input me-3">
                            <span class="icon-box me-3"><i class="bi bi-bank" ></i></span>
                            <span>
                                <strong class="d-block">Mandiri Virtual Account</strong>
                                <small class="text-muted">Transfer to Virtual Account Number</small>
                            </span>
                        </label>
                </fieldset>
                <fieldset class="mx-3">
                        <legend class="small">E-Wallet</legend>
                        <label class="payment-card d-flex align-items-center p-3 bg-white rounded mb-2">
                            <input type="radio" name="payment" data-name="ShopeePay" data-method="shopeepay" class="form-check-input me-3">
                            <span class="icon-box me-3"><i class="bi bi-wallet" ></i></span>
                            <span>
                                <strong class="d-block">ShopeePay</strong>
                                <small class="text-muted">Telephone Number</small>
                            </span>
                        </label>
                        <label class="payment-card d-flex align-items-center p-3 bg-white rounded mb-2">
                            <input type="radio" name="payment" data-name="Gopay" data-method="gopay" class="form-check-input me-3">
                            <span class="icon-box me-3"><i class="bi bi-wallet" ></i></span>
                            <span>
                                <strong class="d-block">Gopay</strong>
                                <small class="text-muted">Telephone Number</small>
                            </span>
                        </label>
                        <label class="payment-card d-flex align-items-center p-3 bg-white rounded mb-2">
                            <input type="radio" name="payment" data-name="Dana" data-method="dana" class="form-check-input me-3">
                            <span class="icon-box me-3"><i class="bi bi-wallet" ></i></span>
                            <span>
                                <strong class="d-block">Dana</strong>
                                <small class="text-muted">Telephone Number</small>
                            </span>
                        </label>
                </fieldset>
                <fieldset class="mx-3">
                        <legend class="small">Other</legend>
                        <label class="payment-card d-flex align-items-center p-3 bg-white rounded mb-2">
                            <input type="radio" name="payment" data-name="QRIS" data-method="qris" class="form-check-input me-3">
                            <span class="icon-box me-3"><i class="bi bi-qr-code-scan" ></i></span>
                            <span>
                                <strong class="d-block">QRIS</strong>
                                <small class="text-muted">Quick Response Indonesia Standard</small>
                            </span>
                        </label>
                        <label class="payment-card d-flex align-items-center p-3 bg-white rounded mb-2">
                            <input type="radio" name="payment" data-name="Cash" data-method="cash" class="form-check-input me-3">
                            <span class="icon-box me-3"><i class="bi bi-cash-coin" ></i></span>
                            <span>
                                <strong class="d-block">Cash</strong>
                                <small class="text-muted">Pay in cashier</small>
                            </span>
                        </label>
                </fieldset>
            </section>
            <!-- Order Summary -->
            <aside class="position-fixed vh-100 top-0 end-0 col-lg-4 text-white d-flex flex-column p-0" id="order-summary">
                <div class="p-4 flex-grow-1">
                    <h2 class="text-center">Order Summary</h2>
                    <dl class="small mb-3">
                        <div class="d-flex">
                            <dt class="fw-normal me-1">Customer Name :</dt>
                            <dd class="mb-0" id="summary-customer"></dd>
                        </div>
                        <div class="d-flex">
                            <dt class="fw-normal me-1">Order Type :</dt>
                            <dd class="mb-0" id="summary-order-type"></dd>
                        </div>
                        <div class="d-flex">
                            <dt class="fw-normal me-1">Order Id :</dt>
                            <dd class="mb-0" id="summary-order-id"></dd>
                        </div>
                        <div class="d-flex">
                            <dt class="fw-normal me-1">Order Date :</dt>
                            <dd class="mb-0" id="summary-order-date"></dd>
                        </div>
                    </dl>
                    <ul class="list-unstyled small border-top border-light-subtle py-2" id="summary-items">
                    </ul>
                </div>
                <div class="p-4">
                    <dl class="small mb-0 border-top py-2">
                        <div class="d-flex justify-content-between">
                            <dt class="fw-normal">Subtotal</dt>
                            <dd class="mb-0" id="summary-subtotal">Rp 0</dd>
                        </div>
                        <div class="d-flex justify-content-between">
                            <dt class="fw-normal">Tax (PPN 11%)</dt>
                            <dd class="mb-0" id="summary-tax">Rp 0</dd>
                        </div>
                        <div class="d-flex justify-content-between">
                            <dt class="fw-normal">Discount</dt>
                            <dd class="mb-0" id="summary-discount" >Rp 0</dd>
                        </div>
                        <div class="d-flex justify-content-between fs-6 border-top pt-2 mt-2">
                            <dt>TOTAL</dt>
                            <dd class="fw-semi-bold mb-0" id="summary-total">Rp 0</dd>
                        </div>
                    </dl>
                </div>
                <button class="btn btn-light rounded-0 py-3 text-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">Pay Now</button>
            </aside>
        </div>
    </main>
    <!-- Confirm Payment -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 p-2 shadow-lg">
                <div class="modal-header border-0 pb-0 position-relative">
                    <h5 class="modal-title fw-bold text-dark w-100 text-center">Confirm Payment</h5>
                </div>
                <div class="modal-body">
                        <h2 class="fw-bold mb-3 text-center" id="pay-total"></h2>
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-top border-bottom">
                        <p class="text-muted small mb-1">Pay with</p>
                        <span class="d-flex align-items-center gap-2">
                            <span class="icon-box icon-box-sm" id="pay-icon" ></span>
                            <strong id="pay-method"></strong>
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light w-100 rounded-2" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-light w-100 rounded-2" id="btn-confirm-pay">Yes, pay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Success Payment -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 p-3 shadow-lg text-center">
                <div class="modal-body">
                    <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-check-lg fs-1 rounded-circle"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Payment Successful</h5>
                    <p class="text-muted small mb-3">Thank you, your order is being processed</p>
                    <h4 class="fw-bold text-primary mb-4" id="success-total">Rp 66.000</h4>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary rounded-2" id="btn-view-receipt">View Receipt</button>
                        <a href="\" class="btn btn-light rounded-2">Done</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="waitingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 p-3 shadow-lg text-center">
            <div class="modal-body">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <h5 class="fw-bold mb-1">Waiting for cashier</h5>
                <p class="text-muted small mb-1">Please pay <strong id="waiting-total"></strong> at the cashier</p>
                <p class="text-muted small mb-1">Method: <span id="waiting-method"></span></p>
                <p class="text-muted small mb-0">Order <span id="waiting-code"></span></p>
            </div>
        </div>
    </div>
</div>