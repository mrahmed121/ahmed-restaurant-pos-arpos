import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import { AuthProvider } from './auth/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import Layout from './layout/Layout';
import Dashboard from './pages/Dashboard';
import Forbidden from './pages/Forbidden';
import Login from './pages/Login';
import NotFound from './pages/NotFound';
import PatientsPage from './modules/patients/pages/PatientsPage';
import AppointmentsPage from './modules/appointments/pages/AppointmentsPage';
import DoctorsPage from './modules/appointments/pages/DoctorsPage';
import PharmacyPage from './modules/pharmacy/pages/PharmacyPage';
export default function App() {
  return (
    <BrowserRouter><AuthProvider><Routes>
      <Route path="/login" element={<Login />} />
      <Route path="/403" element={<Forbidden />} />
      <Route element={<ProtectedRoute><Layout /></ProtectedRoute>}>
        <Route index element={<ProtectedRoute permission="dashboard.view"><Dashboard /></ProtectedRoute>} />
        <Route path="/patients" element={<ProtectedRoute permission="patients.view"><PatientsPage /></ProtectedRoute>} />
        <Route path="/appointments" element={<ProtectedRoute permission="appointments.view"><AppointmentsPage /></ProtectedRoute>} />
        <Route path="/doctors" element={<ProtectedRoute permission="doctors.view"><DoctorsPage /></ProtectedRoute>} />
        <Route path="/pharmacy" element={<ProtectedRoute permission="pharmacy.view"><PharmacyPage /></ProtectedRoute>} />
      </Route>
      <Route path="/404" element={<NotFound />} />
      <Route path="*" element={<Navigate to="/404" replace />} />
    </Routes></AuthProvider></BrowserRouter>
  );
}
