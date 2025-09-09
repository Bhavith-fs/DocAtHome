// webrtc.js
let pc;
let localStream;
let roomCode;
let lastSignalId = 0;

async function startLocalMedia(){
  localStream = await navigator.mediaDevices.getUserMedia({video:true,audio:true});
  const v = document.getElementById('localVideo'); if(v) v.srcObject = localStream;
}

async function createPeer(isCaller){
  pc = new RTCPeerConnection({iceServers:[{urls:'stun:stun.l.google.com:19302'}]});
  localStream.getTracks().forEach(t=>pc.addTrack(t, localStream));
  pc.ontrack = e => { const rv = document.getElementById('remoteVideo'); if(rv) rv.srcObject = e.streams[0]; };
  pc.onicecandidate = e => {
    if(e.candidate){
      fetch('/docathome/public/api/api_signal.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({room: roomCode, type:'candidate', payload: e.candidate})
      });
    }
  };

  if(isCaller){
    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);
    await fetch('/docathome/public/api/api_signal.php', {
      method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({room: roomCode, type:'offer', payload: offer})
    });
  } else {
    pollSignals();
  }
}

async function pollSignals(){
  const res = await fetch(`/docathome/public/api/api_signal.php?room=${roomCode}&after_id=${lastSignalId}`);
  const data = await res.json();
  if(data.signals){
    for(const s of data.signals){
      lastSignalId = Math.max(lastSignalId, s.id);
      if(s.type === 'offer' && !pc){
        await startLocalMedia(); await createPeer(false);
        await pc.setRemoteDescription(s.payload);
        const answer = await pc.createAnswer();
        await pc.setLocalDescription(answer);
        await fetch('/docathome/public/api/api_signal.php', {
          method:'POST', headers:{'Content-Type':'application/json'},
          body: JSON.stringify({room: roomCode, type:'answer', payload: answer})
        });
      } else if(s.type === 'answer'){
        await pc.setRemoteDescription(s.payload);
      } else if(s.type === 'candidate'){
        try { await pc.addIceCandidate(s.payload); } catch(e){ console.warn(e); }
      }
    }
  }
  setTimeout(pollSignals, 1500);
}

// Hook call button on page
document.addEventListener('click', (e)=>{
  if(e.target && e.target.id === 'startCall'){
    (async ()=>{
      roomCode = window.currentAppointmentId ? 'appt_'+window.currentAppointmentId : prompt('Enter room code:');
      if(!roomCode) return alert('room required');
      await startLocalMedia();
      await createPeer(true);
      pollSignals();
    })();
  }
});
