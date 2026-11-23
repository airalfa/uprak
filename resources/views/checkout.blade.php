@extends('layouts.app')

@section('title', 'Checkout - PT. Kusantara Kita')

@section('content')
    <div class="checkout-page">
        <div class="checkout-container">
            <div class="checkout-header">
                <h1 class="checkout-title">Checkout</h1>
                <p class="checkout-subtitle">Lengkapi data pemesanan Anda</p>
            </div>

            <form id="checkoutForm" class="checkout-grid">
                <!-- KOLOM KIRI: DATA PEMESAN, LAYANAN & RINGKASAN -->
                <div>
                    <div class="checkout-card">
                        <h2 class="checkout-section-title">Data Pemesan</h2>

                        <div class="checkout-field-group">
                            <div class="checkout-input-box">
                                <span class="checkout-input-icon"><i class="fa-solid fa-user"></i></span>
                                <div class="checkout-input-body">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="Budi Santoso"
                                        placeholder="Masukkan nama lengkap" required>
                                </div>
                            </div>

                            <div class="checkout-input-box">
                                <span class="checkout-input-icon"><i class="fa-solid fa-phone"></i></span>
                                <div class="checkout-input-body">
                                    <label for="nomor_hp">Nomor HP</label>
                                    <input type="tel" id="nomor_hp" name="nomor_hp" value="081234567890"
                                        placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>

                            <div class="checkout-input-box" id="alamatBox">
                                <span class="checkout-input-icon"><i class="fa-solid fa-location-dot"></i></span>
                                <div class="checkout-input-body">
                                    <label for="alamat_pengiriman">Alamat Pengiriman</label>
                                    <input type="text" id="alamat_pengiriman" name="alamat_pengiriman"
                                        value="Jl. Nusantara No. 123, Jakarta" placeholder="Masukkan alamat lengkap">
                                </div>
                            </div>
                        </div>

                        <div class="checkout-layanan-block">
                            <span class="checkout-layanan-label">Layanan</span>
                            <input type="hidden" id="layananInput" name="layanan" value="Delivery">
                            <div class="checkout-layanan-pills" role="group" aria-label="Pilih Layanan">
                                <button type="button" class="layanan-btn" data-layanan="Dine-in">
                                    <i class="fa-solid fa-utensils"></i> Dine-in
                                </button>
                                <button type="button" class="layanan-btn is-active" data-layanan="Delivery">
                                    <i class="fa-solid fa-motorcycle"></i> Delivery
                                </button>
                                <button type="button" class="layanan-btn" data-layanan="Katering">
                                    <i class="fa-solid fa-box-open"></i> Katering
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h2 class="checkout-section-title">Ringkasan Pesanan</h2>
                        <div id="checkoutItemsList" class="checkout-items-list">
                            <p class="checkout-item-sub">Memuat keranjang...</p>
                        </div>

                        <div class="checkout-summary-line">
                            <span>Subtotal</span>
                            <strong id="summarySubtotal">Rp0</strong>
                        </div>
                        <div class="checkout-summary-line">
                            <span>Pajak Restoran (10%)</span>
                            <span id="summaryTax">Rp0</span>
                        </div>
                        <div class="checkout-summary-line" id="deliveryFeeRow">
                            <span>Ongkos Kirim</span>
                            <span id="summaryDelivery">Rp10.000</span>
                        </div>
                        <div class="checkout-summary-total">
                            <span>Total Tagihan</span>
                            <span id="summaryGrandTotal">Rp0</span>
                        </div>
                    </div>

                    <div class="checkout-back-wrap">
                        <a href="{{ route('katalog.index') }}" class="checkout-back-btn">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- KOLOM KANAN: METODE PEMBAYARAN & SIMULASI PAYLATER -->
                <div>
                    <div class="checkout-card">
                        <h2 class="checkout-section-title">Metode Pembayaran</h2>
                        <input type="hidden" id="paymentMethodInput" name="payment_method" value="paylater">

                        <!-- Opsi 1: Bayar Langsung -->
                        <div class="payment-option" data-payment="direct">
                            <div class="payment-option-head">
                                <span class="payment-radio-dot"></span>
                                <div>
                                    <div class="payment-option-title">Bayar Langsung</div>
                                    <div class="payment-option-desc">Tunai / QRIS / EDC</div>
                                </div>
                            </div>
                        </div>

                        <!-- Opsi 2: Kusantara PayLater (Hanya untuk Delivery & Katering) -->
                        <div class="payment-option is-selected" id="paylaterOptionCard" data-payment="paylater">
                            <div class="payment-option-head">
                                <span class="payment-radio-dot"></span>
                                <div>
                                    <div class="payment-option-title">Kusantara PayLater</div>
                                    <div class="payment-option-desc">Beli Sekarang Bayar Nanti (Delivery &amp; Katering)
                                    </div>
                                </div>
                            </div>

                            <div class="paylater-sim-box" id="paylaterSimBox">
                                <div class="paylater-sim-heading">Simulasi PayLater</div>

                                <div class="paylater-row">
                                    <label for="tenorSelect">Pilih Tenor:</label>
                                    <select id="tenorSelect" class="paylater-select">
                                        <option value="1" data-months="1" data-rate="0" data-admin="0"
                                            data-label="1 Bulan (0% — Bebas Bunga / Tanpa Riba)">
                                            1 Bulan (0% — Tanpa Riba)
                                        </option>
                                        <option value="3" data-months="3" data-rate="0.02" data-admin="0"
                                            data-label="3 Bulan (Bunga 2% flat/bln)">
                                            3 Bulan (Bunga 2% flat/bln)
                                        </option>
                                        <option value="6" data-months="6" data-rate="0.025" data-admin="0"
                                            data-label="6 Bulan (Bunga 2,5% flat/bln)">
                                            6 Bulan (Bunga 2,5% flat/bln)
                                        </option>
                                        <option value="9" data-months="9" data-rate="0.025" data-admin="0"
                                            data-label="9 Bulan (Bunga 2,5% flat/bln)">
                                            9 Bulan (Bunga 2,5% flat/bln)
                                        </option>
                                        <option value="12" data-months="12" data-rate="0.025" data-admin="0"
                                            data-label="12 Bulan (Bunga 2,5% flat/bln)">
                                            12 Bulan (Bunga 2,5% flat/bln)
                                        </option>
                                    </select>
                                </div>

                                <div class="paylater-installment-label">Estimasi Cicilan:</div>
                                <div class="paylater-installment-val" id="paylaterInstallmentText">Rp0 / bulan</div>
                                <div class="paylater-note" id="paylaterDetailNote">
                                    Total Bunga: Rp0 • Admin: Rp0 • Total Bayar: Rp0
                                </div>
                            </div>
                        </div>

                        <div class="checkout-item-sub" id="dineInPaylaterNote" style="display:none; margin-bottom:12px;">
                            * Layanan <strong>Dine-in</strong> menggunakan pembayaran langsung di kasir/meja (Kusantara
                            PayLater khusus <em>Delivery</em> &amp; <em>Katering</em>).
                        </div>

                        <button type="submit" class="checkout-submit-btn">
                            Bayar Sekarang <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI -->
    <div class="checkout-modal-backdrop" id="checkoutModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="checkout-modal-card">
            <h3 id="modalTitle">Ringkasan Transaksi Kusantara</h3>
            <div class="checkout-modal-detail" id="modalBodyText"></div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" id="closeModalBtn" class="checkout-back-btn">Tutup</button>
                <button type="button" id="confirmOrderBtn" class="checkout-submit-btn"
                    style="width:auto; margin-top:0; padding:10px 18px;">
                    Konfirmasi Pesanan
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const CART_KEY = 'kusantara_cart';

            function loadCart() {
                try {
                    return JSON.parse(localStorage.getItem(CART_KEY)) || {};
                } catch (e) {
                    return {};
                }
            }

            function rupiah(num) {
                return 'Rp' + Math.round(Number(num || 0)).toLocaleString('id-ID');
            }

            let currentLayanan = 'Delivery';
            let currentPayment = 'paylater';
            let grandTotal = 0;

            const paymentOptions = document.querySelectorAll('.payment-option');
            const paymentInput = document.getElementById('paymentMethodInput');
            const paylaterOptionCard = document.getElementById('paylaterOptionCard');
            const paylaterSimBox = document.getElementById('paylaterSimBox');
            const dineInPaylaterNote = document.getElementById('dineInPaylaterNote');

            function setPaymentMethod(method) {
                currentPayment = method;
                paymentInput.value = currentPayment;

                paymentOptions.forEach(function (o) {
                    o.classList.toggle('is-selected', o.getAttribute('data-payment') === currentPayment);
                });

                if (paylaterSimBox) {
                    paylaterSimBox.classList.toggle('is-hidden', currentPayment !== 'paylater');
                }
                updatePaylaterSimulation();
            }

            function syncPaylaterAvailabilityByLayanan() {
                const isPaylaterAllowed = (currentLayanan === 'Delivery' || currentLayanan === 'Katering');

                if (paylaterOptionCard) {
                    paylaterOptionCard.style.display = isPaylaterAllowed ? 'block' : 'none';
                }
                if (dineInPaylaterNote) {
                    dineInPaylaterNote.style.display = isPaylaterAllowed ? 'none' : 'block';
                }

                if (!isPaylaterAllowed) {
                    setPaymentMethod('direct');
                } else if (currentPayment !== 'paylater') {
                    setPaymentMethod('paylater');
                } else {
                    updatePaylaterSimulation();
                }
            }

            function renderOrderSummary() {
                const cart = loadCart();
                const ids = Object.keys(cart).filter(function (id) {
                    return cart[id] && Number(cart[id].qty || 0) > 0;
                });

                const listEl = document.getElementById('checkoutItemsList');
                const subtotalEl = document.getElementById('summarySubtotal');
                const taxEl = document.getElementById('summaryTax');
                const deliveryRowEl = document.getElementById('deliveryFeeRow');
                const deliveryEl = document.getElementById('summaryDelivery');
                const grandTotalEl = document.getElementById('summaryGrandTotal');

                let subtotal = 0;
                if (ids.length === 0) {
                    listEl.innerHTML =
                        '<div class="checkout-item-row">' +
                        '<div>' +
                        '<div class="checkout-item-name">Keranjang masih kosong</div>' +
                        '<div class="checkout-item-sub">Silakan pilih menu di katalog atau gunakan simulasi uprak</div>' +
                        '</div>' +
                        '<strong>Rp0</strong>' +
                        '</div>';
                } else {
                    listEl.innerHTML = ids.map(function (id) {
                        const it = cart[id];
                        const lineTotal = Number(it.qty) * Number(it.price || 0);
                        subtotal += lineTotal;
                        return (
                            '<div class="checkout-item-row">' +
                            '<div>' +
                            '<div class="checkout-item-name">' + it.qty + 'x ' + it.name + '</div>' +
                            '<div class="checkout-item-sub">@ ' + rupiah(it.price) + '</div>' +
                            '</div>' +
                            '<strong>' + rupiah(lineTotal) + '</strong>' +
                            '</div>'
                        );
                    }).join('');
                }

                const tax = subtotal > 0 ? Math.round(subtotal * 0.10) : 0;
                const deliveryFee = (subtotal > 0 && currentLayanan === 'Delivery') ? 10000 : 0;

                if (deliveryRowEl) {
                    deliveryRowEl.style.display = currentLayanan === 'Delivery' ? 'flex' : 'none';
                }

                grandTotal = subtotal + tax + deliveryFee;

                if (subtotalEl) subtotalEl.textContent = rupiah(subtotal);
                if (taxEl) taxEl.textContent = rupiah(tax);
                if (deliveryEl) deliveryEl.textContent = rupiah(deliveryFee);
                if (grandTotalEl) grandTotalEl.textContent = rupiah(grandTotal);

                updatePaylaterSimulation();
            }

            function updatePaylaterSimulation() {
                const selectEl = document.getElementById('tenorSelect');
                const instEl = document.getElementById('paylaterInstallmentText');
                const noteEl = document.getElementById('paylaterDetailNote');
                if (!selectEl || !instEl || !noteEl) return;

                const opt = selectEl.options[selectEl.selectedIndex];
                const months = parseInt(opt.getAttribute('data-months') || selectEl.value, 10) || 1;
                const monthlyRate = parseFloat(opt.getAttribute('data-rate') || '0') || 0;
                const adminFee = parseInt(opt.getAttribute('data-admin') || '0', 10) || 0;

                const basePrincipal = grandTotal > 0 ? grandTotal : 152000;
                const totalInterest = Math.round(basePrincipal * monthlyRate * months);
                const totalPaylater = basePrincipal + totalInterest + adminFee;
                const monthlyInstallment = Math.ceil(totalPaylater / months);

                instEl.textContent = rupiah(monthlyInstallment) + ' / bulan';

                if (monthlyRate === 0 && months === 1) {
                    noteEl.textContent =
                        'Tanpa Riba (0%) • Bunga: Rp0 • Admin: ' + rupiah(adminFee) +
                        ' • Total Bayar: ' + rupiah(totalPaylater);
                } else {
                    const ratePercent = (monthlyRate * 100).toLocaleString('id-ID');
                    noteEl.textContent =
                        'Bunga ' + ratePercent + '% × ' + months + ' bln = ' + rupiah(totalInterest) +
                        ' • Admin: ' + rupiah(adminFee) +
                        ' • Total Bayar: ' + rupiah(totalPaylater);
                }
            }

            // Toggle Layanan
            const layananBtns = document.querySelectorAll('.layanan-btn');
            const layananInput = document.getElementById('layananInput');
            const alamatBox = document.getElementById('alamatBox');

            layananBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    currentLayanan = btn.getAttribute('data-layanan');
                    layananInput.value = currentLayanan;

                    layananBtns.forEach(function (b) { b.classList.remove('is-active'); });
                    btn.classList.add('is-active');

                    if (alamatBox) {
                        alamatBox.style.display = currentLayanan === 'Dine-in' ? 'none' : 'flex';
                    }

                    syncPaylaterAvailabilityByLayanan();
                    renderOrderSummary();
                });
            });

            // Toggle Metode Pembayaran
            paymentOptions.forEach(function (opt) {
                opt.addEventListener('click', function () {
                    const selected = opt.getAttribute('data-payment');
                    if (selected === 'paylater' && currentLayanan === 'Dine-in') return;
                    setPaymentMethod(selected);
                });
            });

            const tenorSelect = document.getElementById('tenorSelect');
            if (tenorSelect) {
                tenorSelect.addEventListener('change', updatePaylaterSimulation);
            }

            // Submit & Modal Konfirmasi
            const formEl = document.getElementById('checkoutForm');
            const modalEl = document.getElementById('checkoutModal');
            const modalBodyEl = document.getElementById('modalBodyText');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const confirmOrderBtn = document.getElementById('confirmOrderBtn');

            if (formEl) {
                formEl.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const nama = document.getElementById('nama_lengkap').value.trim();
                    const hp = document.getElementById('nomor_hp').value.trim();
                    const alamat = document.getElementById('alamat_pengiriman').value.trim();
                    const tenorOpt = tenorSelect ? tenorSelect.options[tenorSelect.selectedIndex] : null;
                    const tenorLabel = tenorOpt ? tenorOpt.getAttribute('data-label') : '';
                    const isPaylaterActive = (currentPayment === 'paylater' && currentLayanan !== 'Dine-in');
                    const metodeLabel = isPaylaterActive
                        ? 'Kusantara PayLater — ' + tenorLabel + ' (' + document.getElementById('paylaterInstallmentText').textContent + ')'
                        : 'Bayar Langsung (Tunai / QRIS / EDC)';

                    modalBodyEl.innerHTML =
                        '<div><strong>Pemesan:</strong> ' + nama + ' (' + hp + ')</div>' +
                        (currentLayanan !== 'Dine-in' ? '<div><strong>Alamat:</strong> ' + alamat + '</div>' : '') +
                        '<div><strong>Layanan:</strong> ' + currentLayanan + '</div>' +
                        '<div><strong>Metode:</strong> ' + metodeLabel + '</div>' +
                        '<div><strong>Total Tagihan:</strong> ' + rupiah(grandTotal) + '</div>';

                    modalEl.classList.add('is-open');
                });
            }

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function () {
                    modalEl.classList.remove('is-open');
                });
            }

            if (confirmOrderBtn) {
                confirmOrderBtn.addEventListener('click', function () {
                    const cart = loadCart();
                    const ids = Object.keys(cart).filter(function (id) {
                        return cart[id] && Number(cart[id].qty || 0) > 0;
                    });

                    const itemsSummary = ids.length > 0
                        ? ids.map(function (id) {
                            const it = cart[id];
                            return it.qty + 'x ' + it.name + ' (' + rupiah(Number(it.qty) * Number(it.price || 0)) + ')';
                        }).join(', ')
                        : 'Paket Simulasi Checkout (' + currentLayanan + ')';

                    const nama = document.getElementById('nama_lengkap').value.trim();
                    const hp = document.getElementById('nomor_hp').value.trim();
                    const alamat = document.getElementById('alamat_pengiriman').value.trim();
                    const isPaylaterActive = (currentPayment === 'paylater' && currentLayanan !== 'Dine-in');

                    let paymentLabel = 'QRIS / Bayar Langsung';
                    if (isPaylaterActive) {
                        const tenorOpt = tenorSelect ? tenorSelect.options[tenorSelect.selectedIndex] : null;
                        const tenorLabel = tenorOpt ? tenorOpt.getAttribute('data-label') : '';
                        paymentLabel = 'PayLater (' + tenorLabel + ')';
                    }

                    // Samakan label layanan agar pas dengan filter admin (Dine-in, Takeaway, Delivery, Catering)
                    const normalizedService = currentLayanan === 'Katering' ? 'Catering' : currentLayanan;

                    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '{{ csrf_token() }}';

                    confirmOrderBtn.disabled = true;
                    confirmOrderBtn.textContent = 'Menyimpan...';

                    fetch("{{ route('pesanan.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            service: normalizedService,
                            customer: nama,
                            phone: hp,
                            items: itemsSummary,
                            payment: paymentLabel,
                            total: rupiah(grandTotal > 0 ? grandTotal : 152000),
                            note: normalizedService !== 'Dine-in' ? ('Alamat: ' + alamat) : 'Pesanan Dine-in'
                        })
                    })
                        .then(function (res) {
                            if (!res.ok) {
                                throw new Error('Gagal menyimpan pesanan ke server.');
                            }
                            return res.json();
                        })
                        .then(function (data) {
                            localStorage.removeItem(CART_KEY);
                            modalEl.classList.remove('is-open');
                            renderOrderSummary();
                            alert(data.message || ('Pesanan ' + (data.code || '') + ' berhasil dikirim ke admin!'));
                            window.location.href = "{{ route('katalog.index') }}";
                        })
                        .catch(function (err) {
                            alert('Terjadi kendala saat mengirim pesanan: ' + err.message);
                        })
                        .finally(function () {
                            confirmOrderBtn.disabled = false;
                            confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
                        });
                });
            }

            syncPaylaterAvailabilityByLayanan();
            renderOrderSummary();
        });
    </script>
@endpush