import { useEffect, useState } from 'react';
import api from '../../../api/client';
export default function ReportsPage() {
  const [report, setReport] = useState(null);
  const [loading, setLoading] = useState(true);
  useEffect(() => { api.get('/reports/sales').then((r) => setReport(r.data.data)).finally(() => setLoading(false)); }, []);
  if (loading) return <div className="h-64 animate-pulse rounded-lg bg-slate-800/50" />;
  const s = report?.summary || {};
  return (
    <div className="space-y-6">
      <h2 className="text-xl font-bold text-slate-100">Sales Report (30 days)</h2>
      <div className="grid gap-4 md:grid-cols-3">
        {[['Revenue', `Rs ${(s.revenue || 0).toFixed(0)}`], ['Orders', s.orders || 0], ['Avg Order', `Rs ${(s.avg_order || 0).toFixed(0)}`]].map(([l, v]) => (
          <div key={l} className="rounded-lg border border-slate-800 bg-slate-900 p-5">
            <div className="text-sm text-slate-400">{l}</div>
            <div className="mt-1 text-2xl font-bold text-amber-400">{v}</div>
          </div>))}
      </div>
      <div className="rounded-lg border border-slate-800 bg-slate-900 p-5">
        <h3 className="mb-3 font-semibold text-slate-200">Top Items</h3>
        {(report?.top_items || []).length === 0 ? <p className="text-sm text-slate-500">No sales data yet.</p> : (
          <table className="w-full text-sm"><tbody>
            {report.top_items.map((t, i) => (
              <tr key={i} className="border-t border-slate-800 text-slate-200">
                <td className="py-2">{t.name}</td><td className="py-2 text-right">{t.qty} sold</td>
                <td className="py-2 text-right font-bold text-amber-400">Rs {t.revenue}</td>
              </tr>))}
          </tbody></table>)}
      </div>
    </div>
  );
}
