document.addEventListener('DOMContentLoaded',function(){
  const tabs=document.querySelectorAll('.bm-tab');
  const panels=document.querySelectorAll('.bm-panel');
  function activate(id){
    tabs.forEach(t=>t.classList.toggle('active',t.dataset.tab===id));
    panels.forEach(p=>p.classList.toggle('active',p.id==='tab-'+id));
    try{localStorage.setItem('bm_tab',id);}catch(e){}
    if(location.hash!=='#'+id) history.replaceState(null,'','#'+id);
  }
  tabs.forEach(t=>t.addEventListener('click',()=>activate(t.dataset.tab)));
  let initial=(location.hash||'').replace('#','');
  if(!initial) try{initial=localStorage.getItem('bm_tab')}catch(e){}
  if(initial && document.getElementById('tab-'+initial)) activate(initial); else activate('frontend');
  window.addEventListener('hashchange',()=>{const h=location.hash.replace('#',''); if(document.getElementById('tab-'+h)) activate(h);});
  // toggle card highlight
  document.querySelectorAll('.bm-switch input').forEach(function(cb){
    cb.addEventListener('change',function(){ var card=this.closest('.bm-item')||this.closest('.bm-witem'); if(card) card.classList.toggle('on', this.checked); });
  });
});
