<!-- Welcome -->
    <section id="welcome" class="welcome min-vh-100 p-4 mx-auto overflow-hidden d-flex justify-content-center align-items-center text-center">
        <div class="bg-circle circle1-top-right position-absolute"></div>
        <div class="bg-circle circle2-top-right position-absolute"></div>
        <div class="bg-circle circle3-mid position-absolute"></div>
        <div class="bg-circle circle4-mid position-absolute"></div>
        <div class="bg-circle circle5-bot-left position-absolute"></div>
        <div class="bg-circle circle6-bot-left position-absolute"></div>
        <div class="bg-circle circle7-bot-left position-absolute"></div>
        <div class="hero-text">
            <div class="mb-8">
                <h2 class="mb-2 g-0">Welcome to</h2>
                <h1 class="mt-2 g-0">Our UMKM</h1>
            </div>
            <button class="mt-10 btn d-block mx-auto btn-light rounded-pill py-2 shadow-sm" draggable="false" onclick="goToOrderPage()">Make an order</button>
        </div>
    </section>
    <!-- Orders -->
    <section id="orders" class="orders min-vh-100 pt-3 container-fluid position-relative ">
        <div>
            <!-- Header -->
                <div class="mt-4 d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">Our UMKM</h1>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="card-date d-inline-flex align-items-center px-4 py-2 rounded-pill fw-bold text-muted small">
                            <i class="bi bi-calendar2-week px-2"></i>
                            <span id="live-date">Mon, Jan 01 2026</span>
                        </div>
                        <div class="card-date d-inline-flex align-items-center px-4 py-2 rounded-pill fw-bold text-muted small">
                            <i class="bi bi-clock px-2"></i>
                            <span id="live-time">10:10 AM</span>
                        </div>
                        <button id="cancel-btn" class="btn cancel-btn d-inline-flex align-items-center px-4 py-2 rounded-pill fw-bold small">
                            Cancel Order
                        </button>
                    </div>
                </div>
            <!-- Categorys -->
                <div class="d-flex gap-3 mb-4" id="category">
                    <div class="category-card active p-3 text-start rounded-4 border-0" data-category="all">
                        <div class="icon rounded-circle d-flex justify-content-center align-items-center mb-4">
                            <img class="category-icon icon-default" src="<?= asset('icon/menus.png') ?>" alt="All Menu">
                            <img class="category-icon icon-active" src="<?= asset('icon/menu-active.png') ?>" alt="All Menu">
                        </div>
                        <h3 class="fw-bold mb-0 mt-2">All Menu</h3>
                        <small class="text-muted"></small>
                    </div>
                    <div class="category-card p-3 text-start rounded-4 border-0" data-category="dish">
                        <div class="icon rounded-circle d-flex justify-content-center align-items-center mb-4">
                            <img class="category-icon icon-default" src="<?= asset('icon/dish.png') ?>" alt="Dish">
                            <img class="category-icon icon-active" src="<?= asset('icon/dish-active.png') ?>" alt="Dish">
                        </div>
                        <h3 class="fw-bold mb-0 mt-2">Dish</h3>
                        <small class="text-muted"></small>
                    </div>
                    <div class="category-card p-3 text-start rounded-4 border-0" data-category="snack">
                        <div class="icon rounded-circle d-flex justify-content-center align-items-center mb-4">
                            <img class="category-icon icon-default" src="../../assets/icon/breads.png" alt="Snacks">
                            <img class="category-icon icon-active" src="../../assets/icon/bread-active.png" alt="Snacks">
                        </div>
                        <h3 class="fw-bold mb-0 mt-2">Snacks</h3>
                        <small class="text-muted"></small>
                    </div>
                    <div class="category-card p-3 text-start rounded-4 border-0" data-category="drink">
                        <div class="icon rounded-circle d-flex justify-content-center align-items-center mb-4">
                            <img class="category-icon icon-default" src="../../assets/icon/driks.png" alt="Drinks">
                            <img class="category-icon icon-active" src="../../assets/icon/drink-active.png" alt="Drinks">
                        </div>
                        <h3 class="fw-bold mb-0 mt-2">Drinks</h3>
                        <small class="text-muted"></small>
                    </div>
                </div>
            <!-- SearchBar -->
                <div class="mb-4 mt-4">
                    <input class="form-control form-control-lg rounded-pill border-0 shadow-sm px-4 py-3" type="text" placeholder="Search something on your mind...">
                </div>
            <!-- Menus -->
            <div class="container-fluid px-2">
                <div class="menu" id="menu"></div>
            </div>
        </div>
        <!-- Cart -->
            <aside class="position-fixed d-flex flex-column transitioin-all border-start vh-100 top-0 end-0" id="cart-sidebar">
                <div class="px-2 pt-3 pb-1 text-center flex-shrink-0">
                    <input class="customer-name form-control form-control-lg text-center fw-semi-bold rounded-4 border-0" type="text" placeholder="Customer Name">                    <div class="order-type d-flex p-2 pb-1 gap-2 justify-content-center align-items-center">
                        <button type="button" class="btn p-2 rounded-pill fw-semi-bold border-1" data-type="dine-in">Dine In</button>
                        <button type="button" class="btn p-2 rounded-pill fw-semi-bold border-1" data-type="take-away">Take Away</button>
                    </div>
                </div>
                    <div class="cart-items flex-grow-1 overflow-y-auto" id="cart-items">
                    </div>
                <div class="footer bg-white pt-2 flex-shrink-0">
                    <div class="px-4 pb-2 pos">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-dark">Subtotal</span>
                            <span class="fw-bold text-dark" id="cart-subtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Tax (PPN 11%)</small>
                            <small class="text-muted" id="cart-tax">Rp 0</small>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <small class="text-success">Discount</small>
                            <small class="text-success" id="cart-discount">Rp 0</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold mb-0">TOTAL</h5>
                            <h5 class="fw-bold mb-0" id="cart-total">Rp 0</h5>
                        </div>
                    </div>
                    <a href="/payment" id="place-order-btn" class="btn btn-primary w-100 py-3 rounded-0 fw-bold fs-6 border-0">Place Order</a>
                </div>
            </aside>
        <button class="cart-btn is-hidden rounded-circle d-inline-flex align-items-center justify-content-center position-fixed bottom-0 end-0 m-4" id="cart-btn">
            <i class="bi bi-bag-check"></i>
        </button>
    </section>
    <!-- Details Menu -->
    <div class="modal fade" id="detailMenuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 p-2 shadow-lg">
                <div class="modal-header border-0 pb-0 position-relative">
                    <h5 class="modal-title fw-bold text-dark w-100 text-center">Detail Menu</h5>
                    <button type="button" class="btn-close bg-danger-subtle rounded-circle p-2 position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="image p-3 rounded-4 d-flex justify-content-center align-items-center mb-3" style="height: 180px;">
                        <img id="modal-img" src="/./assets/croissant.png" class="img-fluid object-fit-contain h-100" alt="Details Menu">
                    </div>
                    <h6 class="fw-bold text-dark mb-1 fs-5" id="modal-title"></h6>
                    <p class="text-muted small mb-2" id="modal-desc" style="font-size: 0.8rem;">
                    </p>
                    <h6 class="price fw-bold mb-3 fs-5" id="modal-price">Rp 0</h6>
                    <div class="notes mb-3">
                        <textarea class="form-control border-0 rounded-4 text-muted small p-2" rows="2" placeholder="Add notes to your order ..."></textarea>
                    </div>
                    <div class="quantity d-flex justify-content-between align-items-center rounded-pill px-3 py-1 mb-3">
                        <button class="btn btn-sm bg-white rounded-circle shadow-sm fw-bold border-0" id="modal-qty-minus">-</button>
                        <span class="fw-bold text-dark" id="modal-qty">1</span>
                        <button class="btn btn-sm bg-white rounded-circle shadow-sm fw-bold border-0" id="modal-qty-plus">+</button>
                    </div>
                    <button class="btn w-100 rounded-4 py-2.5 fw-semi-bold shadow-sm" id="modal-add-btn">
                        Add to Cart (<span id="modal-btn-price" id="modal-btn-price">Rp 0</span>)
                    </button>
                </div>
            </div>
        </div>
    </div>