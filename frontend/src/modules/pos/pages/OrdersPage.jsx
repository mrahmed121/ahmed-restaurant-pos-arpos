import { useEffect, useState } from 'react';
import api from '../../../api/client';
export default function OrdersPage() {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  useEffect(() => { api.get('/orders?per_page=30').then((r) => setOrders(r.data.data || [])).finally(() => setLoading(false)); }, []);
  if (loading) return <div className="h-64 animate-pulse rounded-lg bg-slate-800/50" />;
  return (
    <div className="space-y-4">
      <h2 className="text-xl font-bold text-slate-100">Orders</h2>
      {orders.length === 0 ? <div className="rounded-lg border border-dashed border-slate-700 p-12 text-center text-slate-400">No orders yet.</div> : (
        <div className="overflow-x-auto rounded-lg border border-slate-800">
          <table className="w-full text-sm">
            <thead><tr className="bg-slate-900 text-left text-slate-400">
              <th className="px-4 py-3">Order #</th><th className="px-4 py-3">Type</th><th className="px-4 py-3">Status</th><th className="px-4 py-3 text-right">Total</th>
            </tr></thead>
            <tbody>{orders.map((o) => (
              <tr key={o.id} className="border-t border-slate-800 text-slate-200">
                <td className="px-4 py-3 font-medium">{o.order_number}</td>
                <td className="px-4 py-3">{o.type}</td>
                <td className="px-4 py-3"><span className="rounded-full bg-slate-800 px-2 py-1 text-xs">{o.status}</span></td>
                <td className="px-4 py-3 text-right font-bold text-amber-400">Rs {o.total}</td>
              </tr>))}
            </tbody>
          </table>
        </div>)}
    </div>
  );
}
