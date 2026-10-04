import { useEffect, useState } from 'react';
import api from '../../../api/client';

export default function PosPage() {
  const [items, setItems] = useState([]);
  const [categories, setCategories] = useState([]);
  const [cart, setCart] = useState([]);
  const [activeCat, setActiveCat] = useState(null);
  const [branches, setBranches] = useState([]);
  const [branchId, setBranchId] = useState('');
  const [orderType, setOrderType] = useState('dine_in');
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [message, setMessage] = useState('');

  useEffect(() => {
    Promise.all([api.get('/menu/items?per_page=100'), api.get('/menu/categories'), api.get('/branches')])
      .then(([i, c, b]) => {
        setItems(i.data.data || []); setCategories(c.data.data || []);
        const bl = b.data.data || []; setBranches(bl);
        if (bl.length) setBranchId(bl[0].id);
      })
      .finally(() => setLoading(false));
  }, []);

  const filtered = activeCat ? items.filter((i) => i.category_id === activeCat) : items;
  const total = cart.reduce((s, c) => s + c.price * c.qty, 0);

  const addToCart = (item) => {
    setCart((prev) => {
      const ex = prev.find((p) => p.id === item.id);
      if (ex) return prev.map((p) => (p.id === item.id ? { ...p, qty: p.qty + 1 } : p));
      return [...prev, { id: item.id, name: item.name, price: parseFloat(item.price), qty: 1 }];
    });
  };

  const submitOrder = async () => {
    if (!cart.length || !branchId) return;
    setSubmitting(true); setMessage('');
    try {
      const res = await api.post('/orders', {
        branch_id: branchId, type: orderType,
        items: cart.map((c) => ({ menu_item_id: c.id, quantity: c.qty })),
      });
      setMessage(`Order ${res.data.data.order_number} created!`);
      setCart([]);
    } catch (e) {
      setMessage(e.response?.data?.message || 'Failed to create order.');
    } finally { setSubmitting(false); }
  };

  if (loading) return <div className="grid grid-cols-3 gap-4">{[1,2,3].map(i=><div key={i} className="h-32 animate-pulse rounded-lg bg-slate-800/50"/>)}</div>;

  return (
    <div className="grid gap-6 lg:grid-cols-3">
      <div className="space-y-4 lg:col-span-2">
        <div className="flex flex-wrap gap-2">
          <button onClick={() => setActiveCat(null)} className={`rounded-full px-4 py-1.5 text-sm ${!activeCat ? 'bg-amber-500 text-slate-950' : 'bg-slate-800 text-slate-300'}`}>All</button>
          {categories.map((c) => (
            <button key={c.id} onClick={() => setActiveCat(c.id)} className={`rounded-full px-4 py-1.5 text-sm ${activeCat === c.id ? 'bg-amber-500 text-slate-950' : 'bg-slate-800 text-slate-300'}`}>{c.name}</button>
          ))}
        </div>
        <div className="grid grid-cols-2 gap-3 md:grid-cols-3">
          {filtered.map((item) => (
            <button key={item.id} onClick={() => addToCart(item)} className="rounded-lg border border-slate-700 bg-slate-900 p-4 text-left transition hover:border-amber-500/50">
              <div className="font-medium text-slate-100">{item.name}</div>
              <div className="mt-1 text-sm font-bold text-amber-400">Rs {item.price}</div>
            </button>
          ))}
        </div>
      </div>
      <div className="rounded-lg border border-slate-800 bg-slate-900 p-5">
        <h3 className="mb-4 font-bold text-slate-100">Current Order</h3>
        <div className="mb-4 grid grid-cols-2 gap-2">
          <select value={branchId} onChange={(e) => setBranchId(e.target.value)} className="rounded-lg bg-slate-800 px-3 py-2 text-sm text-slate-200">
            {branches.map((b) => <option key={b.id} value={b.id}>{b.name}</option>)}
          </select>
          <select value={orderType} onChange={(e) => setOrderType(e.target.value)} className="rounded-lg bg-slate-800 px-3 py-2 text-sm text-slate-200">
            <option value="dine_in">Dine In</option><option value="takeaway">Takeaway</option><option value="delivery">Delivery</option>
          </select>
        </div>
        <div className="mb-4 max-h-64 space-y-2 overflow-y-auto">
          {cart.length === 0 ? <p className="text-sm text-slate-500">Cart is empty. Tap menu items to add.</p> :
            cart.map((c) => (
              <div key={c.id} className="flex items-center justify-between rounded bg-slate-800/50 px-3 py-2">
                <span className="text-sm text-slate-200">{c.name} × {c.qty}</span>
                <span className="text-sm font-bold text-amber-400">Rs {(c.price * c.qty).toFixed(0)}</span>
              </div>
            ))}
        </div>
        <div className="mb-4 flex justify-between border-t border-slate-700 pt-3">
          <span className="font-bold text-slate-200">Total</span>
          <span className="font-bold text-amber-400">Rs {total.toFixed(0)}</span>
        </div>
        {message && <p className="mb-3 text-sm text-emerald-400">{message}</p>}
        <button onClick={submitOrder} disabled={!cart.length || submitting} className="w-full rounded-lg bg-amber-500 py-2.5 font-bold text-slate-950 disabled:opacity-40">
          {submitting ? 'Placing...' : 'Place Order'}
        </button>
      </div>
    </div>
  );
}
