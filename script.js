const $ = (s) => document.querySelector(s);
const modal = $('#uploadModal');
const form = $('#uploadForm');

function toast(message, ok=true) {
  const el=document.createElement('div');
  el.className=`toast ${ok?'toast-ok':'toast-error'}`;
  el.innerHTML=`<i class="fa-solid ${ok?'fa-circle-check':'fa-circle-exclamation'}"></i><span>${escapeHtml(message)}</span>`;
  $('#toastContainer').appendChild(el);
  setTimeout(()=>el.remove(),4500);
}
function escapeHtml(s){return String(s).replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
function openModal(){modal.classList.remove('hidden'); requestAnimationFrame(()=>modal.classList.add('show'));}
function closeModal(){modal.classList.remove('show');setTimeout(()=>modal.classList.add('hidden'),200);}
$('#openUploadBtn').addEventListener('click',openModal);
$('#closeModalBtn').addEventListener('click',closeModal);
$('#cancelUploadBtn').addEventListener('click',closeModal);
$('#modalBackdrop').addEventListener('click',closeModal);
$('#mobileMenuBtn').addEventListener('click',()=>$('#mobileMenu').classList.toggle('hidden'));
document.querySelectorAll('.mobile-link').forEach(a=>a.addEventListener('click',()=>$('#mobileMenu').classList.add('hidden')));

document.querySelectorAll('input[name="mediaType"]').forEach(r=>r.addEventListener('change',()=>{
  $('#mediaFile').accept=r.value==='music'?'audio/*':'video/*';
}));

form.addEventListener('submit',(e)=>{
  e.preventDefault();
  const fd=new FormData(form);
  fd.append('action','upload');
  const xhr=new XMLHttpRequest();
  xhr.open('POST','api.php?action=upload');
  $('#uploadProgressWrap').classList.remove('hidden');
  xhr.upload.onprogress=(ev)=>{
    if(ev.lengthComputable){
      const pct=Math.round(ev.loaded/ev.total*100);
      $('#uploadProgress').style.width=pct+'%';
      $('#uploadStatus').textContent=`Uploading… ${pct}%`;
    }
  };
  xhr.onload=()=>{
    try {
      const res=JSON.parse(xhr.responseText);
      if(!res.success) throw new Error(res.message);
      addCard(res.data.item);
      toast(res.message,true);
      form.reset();
      $('#uploadProgressWrap').classList.add('hidden');
      closeModal();
      updateCounts();
    } catch(err){ toast(err.message || 'Upload failed.',false); $('#uploadStatus').textContent='Upload failed.'; }
  };
  xhr.onerror=()=>toast('Network/server error during upload.',false);
  xhr.send(fd);
});

$('#contactForm').addEventListener('submit',async e=>{
  e.preventDefault();
  const fd=new FormData();
  fd.append('action','contact'); fd.append('name',$('#name').value); fd.append('email',$('#email').value); fd.append('message',$('#message').value);
  try{
    const r=await fetch('api.php?action=contact',{method:'POST',body:fd}); const j=await r.json();
    if(!j.success) throw new Error(j.message); toast(j.message); e.target.reset();
  }catch(err){toast(err.message||'Could not send message.',false);}
});

function addCard(item){
  const type=item.type;
  const grid=type==='music'?$('#musicGrid'):$('#videoGrid');
  const empty=type==='music'?$('#musicEmpty'):$('#videoEmpty');
  empty.classList.add('hidden');
  const article=document.createElement('article'); article.className='media-card';
  const title=escapeHtml(item.title), artist=escapeHtml(item.artist||'Galaxy Stream');
  if(type==='music'){
    const art=item.cover_url?`<img src="${escapeHtml(item.cover_url)}" class="media-cover" alt="">`:`<div class="media-cover cover-fallback"><i class="fa-solid fa-music"></i></div>`;
    article.innerHTML=`<div>${art}</div><div class="p-5"><h3 class="font-bold text-lg truncate">${title}</h3><p class="text-sm text-gray-400 mb-4">${artist} • Just now</p><audio controls preload="metadata" class="w-full"><source src="${escapeHtml(item.file_url)}"></audio><a class="download-link" href="${escapeHtml(item.file_url)}" download><i class="fa-solid fa-download"></i> Download</a></div>`;
    grid.prepend(article);
  } else {
    const poster=item.cover_url?` poster="${escapeHtml(item.cover_url)}"`:'';
    article.innerHTML=`<div class="video-wrap"><video controls preload="metadata"${poster}><source src="${escapeHtml(item.file_url)}"></video></div><div class="p-5"><h3 class="font-bold text-lg">${title}</h3><p class="text-sm text-gray-400">${artist} • Just now</p><a class="download-link" href="${escapeHtml(item.file_url)}" download><i class="fa-solid fa-download"></i> Download</a></div>`;
    grid.prepend(article);
  }
}
function updateCounts(){
  $('#musicCount').textContent=`${$('#musicGrid').children.length} tracks`;
  $('#videoCount').textContent=`${$('#videoGrid').children.length} videos`;
}
