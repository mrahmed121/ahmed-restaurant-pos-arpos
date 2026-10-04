import { useEffect, useState } from 'react';
import api from '../../../api/client';

const STATUS_COLORS = { pending: 'bg-slate-700', preparing: 'bg-amber-500/20 text-amber-300 border-amber-500/40', ready: 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' };

export default function KitchenPage() {
  const [tickets, setTickets] = useState([]);
  const [loading, setLoading] = useState(true);

  const load = () => api.get('/kitchen/tickets?per_page=50').then((r) => setTickets(r.data.data || [])).finally(() => setLoading(false));
  useEffect(() => { load(); const t = setInterval(load, 15000); return () => clearInterval(t); }, []);

  const advance = async (item) => {
    const next = item.kot_status === 'pending' ? 'preparing' : item.kot_status === 'preparing' ? 'ready' : 'served';
    await api.put(`/kitchen/tickets/${item.id}`, { kot_status: next });
    load();
  };

  if (loading) return <div className="h-64 animate-pulse rounded-lg bg-slate-800/50" />;

  return (
    <div className="space-y-4">
      <h2 className="text-xl font-bold text-slate-100">Kitchen Display — Live Tickets</h2>
      {tickets.length === 0 ? (
        <div className="rounded-lg border border-dashed border-slate-700 bg-slate-900/50 px-6 py-12 text-center">
          <div className="text-4xl">🍳</div>
          <h3 className="mt-2 font-semibold text-slate-200">No active tickets</h3>
          <p className="text-sm text-slate-400">New orders will appear here automatically.</p>
        </div>
      ) : (
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          {tickets.map((t) => (
            <div key={t.id} className={`rounded-lg border p-4 ${STATUS_COLORS[t.kot_status] || 'bg-slate-900 border-slate-700'}`}>
              <div className="flex justify-between">
                <span className="font-bold text-slate-100">{t.order?.order_number}</span>
                <span className="text-xs uppercase text-slate-400">{t.kot_status}</span>
              </div>
              <div className="mt-2 text-lg font-semibold text-slate-100">{t.quantity}× {t.name}</div>
              {t.notes && <p className="mt-1 text-sm text-amber-300">Note: {t.notes}</p>}
              <button onClick={() => advance(t)} className="mt-3 w-full rounded-lg bg-slate-800 py-2 text-sm font-medium text-slate-200 hover:bg-slate-700">
                Mark {t.kot_status === 'pending' ? 'Preparing' : t.kot_status === 'preparing' ? 'Ready' : 'Served'}
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
