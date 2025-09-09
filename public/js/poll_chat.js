// poll_chat.js
let lastId = 0;
function appendMessage(m){
  const box = document.getElementById('chatBox');
  const div = document.createElement('div');
  div.className = (m.from_user == currentUserId) ? 'text-right text-sm my-1' : 'text-left text-sm my-1';
  div.innerHTML = `<div class="inline-block p-2 rounded bg-gray-100">${m.message}</div>`;
  box.appendChild(div);
  box.scrollTop = box.scrollHeight;
}

async function pollMessages() {
  if(!window.currentAppointmentId) return;
  try {
    const res = await fetch(`/docathome/public/api/api_chat.php?appointment_id=${window.currentAppointmentId}&after_id=${lastId}`);
    const data = await res.json();
    if(data.messages){
      data.messages.forEach(m=>{
        appendMessage(m);
        lastId = Math.max(lastId, m.id);
      });
    }
  } catch(e){ console.error(e); }
}
setInterval(pollMessages, 1500);

document.getElementById('sendBtn').addEventListener('click', async ()=>{
  const text = document.getElementById('msgInput').value.trim();
  if(!text) return;
  const payload = { appointment_id: window.currentAppointmentId, to_user: doctorId, message:text };
  const r = await fetch('/docathome/public/api/api_chat.php', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload)});
  const res = await r.json();
  if(res.success){ document.getElementById('msgInput').value=''; appendMessage(res.message); }
});
