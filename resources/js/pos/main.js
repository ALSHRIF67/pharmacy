import { getProductByBarcode, saveProductLocally, queueSyncAction, db } from '../offline/db.js';

let cart = [];

document.addEventListener('DOMContentLoaded', () => {
    const barcodeInput = document.getElementById('barcode-input');
    const modal = document.getElementById('edit-modal');
    const closeModal = document.getElementById('close-modal');
    const editForm = document.getElementById('edit-form');
    const cartItemsTbody = document.getElementById('cart-items');
    const cartTotalSpan = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');

    // Initialize Scanner
    if (typeof Html5Qrcode !== 'undefined') {
        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = async (decodedText, decodedResult) => {
            barcodeInput.value = decodedText;
            handleBarcode(decodedText);
        };
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
            .catch(err => console.warn('Scanner error or not allowed:', err));
    }

    // Barcode Input Handler
    barcodeInput.addEventListener('keydown', async (e) => {
        if (e.key === 'Enter') {
            const barcode = barcodeInput.value;
            barcodeInput.value = '';
            handleBarcode(barcode);
        }
    });

    async function handleBarcode(barcode) {
        if (!barcode) return;
        
        let product = await getProductByBarcode(barcode);
        
        if (!product) {
            // Try fetching from API
            try {
                const response = await fetch(`/api/products/barcode/${barcode}`);
                if (response.ok) {
                    product = await response.json();
                    await saveProductLocally(product);
                }
            } catch (err) {
                console.error('API fetch failed:', err);
            }
        }

        if (product && product.id) {
            addToCart(product);
        } else if (product && product.barcode) {
            // Locally saved but maybe newly created
            addToCart(product);
        } else {
            // New product or not found, open modal
            openEditModal({ barcode: barcode, name: '', price: 0 });
        }
    }

    function addToCart(product) {
        const existingItem = cart.find(item => item.barcode === product.barcode);
        if (existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({
                ...product,
                qty: 1
            });
        }
        renderCart();
    }

    function renderCart() {
        cartItemsTbody.innerHTML = '';
        let total = 0;

        cart.forEach((item, index) => {
            const subtotal = item.price * item.qty;
            total += subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.name || 'Unknown'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        <button class="qty-btn bg-gray-200 px-2 rounded hover:bg-gray-300" data-index="${index}" data-action="dec">-</button>
                        <span>${item.qty}</span>
                        <button class="qty-btn bg-gray-200 px-2 rounded hover:bg-gray-300" data-index="${index}" data-action="inc">+</button>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$${parseFloat(item.price).toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$${subtotal.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button class="remove-btn text-red-600 hover:text-red-900" data-index="${index}">Delete</button>
                </td>
            `;
            cartItemsTbody.appendChild(tr);
        });

        cartTotalSpan.innerText = `$${total.toFixed(2)}`;
        
        // Attach listeners
        cartItemsTbody.querySelectorAll('.qty-btn').forEach(btn => {
            btn.onclick = (e) => {
                const index = e.target.dataset.index;
                const action = e.target.dataset.action;
                if (action === 'inc') cart[index].qty++;
                else if (action === 'dec' && cart[index].qty > 1) cart[index].qty--;
                renderCart();
            };
        });

        cartItemsTbody.querySelectorAll('.remove-btn').forEach(btn => {
            btn.onclick = (e) => {
                const index = e.target.dataset.index;
                cart.splice(index, 1);
                renderCart();
            };
        });
    }

    function openEditModal(product) {
        document.getElementById('edit-barcode').value = product.barcode;
        document.getElementById('edit-name').value = product.name || '';
        document.getElementById('edit-price').value = product.price || 0;
        document.getElementById('modal-title').innerText = product.id ? 'Edit Product' : 'New Product Found';
        modal.classList.remove('hidden');
    }

    closeModal.onclick = () => modal.classList.add('hidden');

    editForm.onsubmit = async (e) => {
        e.preventDefault();
        const data = {
            barcode: document.getElementById('edit-barcode').value,
            name: document.getElementById('edit-name').value,
            price: parseFloat(document.getElementById('edit-price').value),
            batch: document.getElementById('edit-batch').value,
            expiry: document.getElementById('edit-expiry').value,
            stock: parseFloat(document.getElementById('edit-stock').value)
        };

        await saveProductLocally(data);
        await queueSyncAction('PRODUCT_UPDATE', data);
        
        modal.classList.add('hidden');
        // If it was a new product, add it to cart now
        addToCart(data);
    };

    checkoutBtn.onclick = async () => {
        if (cart.length === 0) return alert('السلة فارغة!');

        const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

        // If online, try server checkout
        if (navigator.onLine) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const resp = await fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ items: cart, total_price: total })
                });

                if (!resp.ok) {
                    const err = await resp.json().catch(() => ({ message: 'Checkout failed' }));
                    return alert(err.message || 'Checkout failed');
                }

                const data = await resp.json();
                cart = [];
                renderCart();

                if (confirm('تمت عملية البيع بنجاح. هل تريد طباعة الفاتورة؟')) {
                    // open invoice view and trigger print
                    const win = window.open(`/pos/invoice/${data.id}?print=1`, '_blank');
                    if (!win) alert('فتح نافذة الطباعة محظور، الرجاء السماح بالنوافذ المنبثقة.');
                } else {
                    alert('تمت عملية البيع بنجاح');
                }

            } catch (err) {
                console.error('Checkout failed:', err);
                alert('فشل في إتمام البيع عبر الخادم؛ سيتم حفظ العملية محلياً.');
                // fallback to offline save
            }
        }

        // Offline fallback / queue
        try {
            const sale = {
                items: [...cart],
                total_price: total,
                created_at: new Date(),
                synched: 0
            };

            await db.sales.add(sale);
            await queueSyncAction('SALE_CREATE', sale);

            cart = [];
            renderCart();
        } catch (err) {
            console.error('Checkout failed:', err);
        }
    };

    // Auto-focus barcode input
    document.addEventListener('click', (e) => {
        if (!modal.contains(e.target)) {
            barcodeInput.focus();
        }
    });

    barcodeInput.focus();
});
