<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/dexie/dist/dexie.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-indigo-600 text-white p-4 shadow-lg flex justify-between items-center">
            <h1 class="text-xl font-bold">Pharmacy POS</h1>
            <div id="connection-status" class="px-3 py-1 bg-green-500 rounded-full text-xs font-semibold">Online</div>
        </header>

        <main class="flex-1 flex overflow-hidden">
            <!-- Left Side: POS -->
            <div class="flex-1 flex flex-col p-4 overflow-y-auto">
                <!-- Barcode Input -->
                <div class="mb-4">
                    <input type="text" id="barcode-input" placeholder="Scan Barcode (PRCODE)..." 
                        class="w-full p-4 text-2xl border-2 border-indigo-300 rounded-lg focus:outline-none focus:border-indigo-600 shadow-sm"
                        autofocus>
                </div>

                <!-- Product List -->
                <div class="flex-1 bg-white rounded-lg shadow overflow-hidden flex flex-col">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items" class="bg-white divide-y divide-gray-200">
                            <!-- Items will be injected here -->
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary -->
                <div class="mt-4 p-6 bg-white rounded-lg shadow flex justify-between items-center">
                    <div class="text-3xl font-bold text-gray-800">Total: <span id="cart-total">$0.00</span></div>
                    <button id="checkout-btn" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg text-xl font-bold transition-colors">
                        Checkout
                    </button>
                </div>
            </div>

            <!-- Right Side: Recent / Scanner -->
            <div class="w-80 bg-gray-50 border-l border-gray-200 p-4">
                <div id="reader" class="mb-4 bg-black rounded-lg overflow-hidden shadow"></div>
                <h2 class="font-bold text-gray-700 mb-2 uppercase text-xs tracking-wider">Sync Status</h2>
                <div id="sync-list" class="space-y-2 text-sm">
                    <!-- Sync items -->
                </div>
            </div>
        </main>

        <!-- Product Update Modal -->
        <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 m-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800" id="modal-title">Edit Product</h2>
                    <button id="close-modal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                <form id="edit-form" class="space-y-4">
                    <input type="hidden" id="edit-barcode">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="edit-name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" id="edit-price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Batch #</label>
                            <input type="text" id="edit-batch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expiry</label>
                            <input type="date" id="edit-expiry" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Add Stock (Quantity)</label>
                        <input type="number" id="edit-stock" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">Save Locally</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/offline/db.js', 'resources/js/pos/scanner.js'])
    <script type="module">
        import { getProductByBarcode, saveProductLocally, queueSyncAction } from '/resources/js/offline/db.js';

        // Basic POS logic
        const barcodeInput = document.getElementById('barcode-input');
        const modal = document.getElementById('edit-modal');
        const closeModal = document.getElementById('close-modal');
        const editForm = document.getElementById('edit-form');
        
        // Handle Barcode Search
        barcodeInput.addEventListener('keydown', async (e) => {
            if (e.key === 'Enter') {
                const barcode = barcodeInput.value;
                barcodeInput.value = '';
                
                const product = await getProductByBarcode(barcode);
                if (product) {
                    openEditModal(product);
                } else {
                    // Try fetch from API, if fail, open new product form
                    try {
                        const response = await fetch(`/api/products/barcode/${barcode}`);
                        if (response.ok) {
                            const data = await response.json();
                            await saveProductLocally(data);
                            openEditModal(data);
                        } else {
                            openEditModal({ barcode: barcode, name: '', price: 0 });
                        }
                    } catch (err) {
                        openEditModal({ barcode: barcode, name: '', price: 0 });
                    }
                }
            }
        });

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

            // Save to Dexie
            await saveProductLocally(data);
            // Queue sync
            await queueSyncAction('PRODUCT_UPDATE', data);
            
            modal.classList.add('hidden');
            alert('Saved locally! Will sync when online.');
        };
    </script>
</body>
</html>
