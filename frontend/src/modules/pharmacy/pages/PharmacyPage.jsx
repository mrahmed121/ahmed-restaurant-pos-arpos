import { useEffect, useState } from 'react';
import api from '../../../api/client';
export default function PharmacyPage() {
  const [meds, setMeds] = useState([]); const [loading, setLoading] = useState(true); const [lowOnly, setLowOnly] = useState(false);
  const load = (low) => { setLoading(true); api.get(`/medicines?per_page=50${low ? '&low_stock=1' : ''}`).then((r) => setMeds(r.data.data || [])).finally(() => setLoading(false)); };
  useEffect(() => { load(false); }, []);
  if (loading) return <div className="h-64 animate-pulse rounded-lg bg-slate-800/50" />;
  return (
    <div className="space-y-4">
      <div className="flex justify-between"><h2 className="text-xl font-bold text-slate-100">Pharmacy</h2>
        <button onClick={() => { setLowOnly(!lowOnly); load(!lowOnly); }} className="rounded-lg bg-slate-800 px-4 py-2 text-sm text-slate-200">Low stock {lowOnly ? '✓' : ''}</button></div>
      <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
        {meds.map((m) => (
          <div key={m.id} className="rounded-lg border border-slate-800 bg-slate-900 p-4">
            <div className="font-medium text-slate-100">💊 {m.name}</div>
            <div className="text-xs text-slate-500">{m.generic_name} · {m.unit}</div>
            <div className="mt-2 flex justify-between text-sm">
              <span className={parseFloat(m.stock_quantity) <= parseFloat(m.reorder_level) ? 'text-red-400 font-bold' : 'text-slate-400'}>Stock: {m.stock_quantity}</span>
              <span className="font-bold text-teal-400">Rs {m.unit_price}</span>
            </div>
          </div>))}
      </div></div>
  );
}
