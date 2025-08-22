async function api(action, data) {
  const form = new FormData();
  form.append('action', action);
  if (data) Object.entries(data).forEach(([k,v]) => form.append(k, v));
  const res = await fetch('api.php', { method: 'POST', body: form });
  return await res.json();
}
const loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const out = await api('login', { username, password });
    if (out.ok) {
      alert("ورود موفق");
      refreshTicker();
    } else {
      alert(out.error);
    }
  });
}
async function refreshTicker() {
  const out = await fetch('api.php?action=get_ticker').then(r=>r.json());
  if (out.ok) {
    console.log(out.data);
  }
}