import Dexie from 'dexie';

export const db = new Dexie('PharmacyDB');

db.version(1).stores({
    products: '++id, barcode, name',
    batches: '++id, product_id, expiry_date',
    sales: '++id, total_price, synched',
    sync_queue: '++id, type, data'
});

export async function saveProductLocally(product) {
    return await db.products.put(product);
}

export async function getProductByBarcode(barcode) {
    return await db.products.where('barcode').equals(barcode).first();
}

export async function queueSyncAction(type, data) {
    return await db.sync_queue.add({ type, data, timestamp: new Date() });
}
