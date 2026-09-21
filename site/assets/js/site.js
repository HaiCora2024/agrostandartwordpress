(function(){
  var y = document.getElementById('as-year');
  if (y) y.textContent = new Date().getFullYear();

  var burger = document.getElementById('as-burger');
  var navList = document.getElementById('as-nav-list');
  if (burger && navList) {
    burger.addEventListener('click', function(){
      var open = navList.classList.toggle('open');
      burger.setAttribute('aria-expanded', String(open));
    });
    navList.addEventListener('click', function(e){
      if (e.target.tagName === 'A') {
        navList.classList.remove('open');
        burger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  var form = document.getElementById('as-lead-form');
  if (form) {
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var f = new FormData(form);
      var lines = [
        'Имя: ' + (f.get('name') || ''),
        'Хозяйство: ' + (f.get('farm') || ''),
        'Вид животных: ' + (f.get('animal') || ''),
        'Поголовье: ' + (f.get('heads') || ''),
        'Телефон/e-mail: ' + (f.get('contact') || ''),
        'Чем кормите сейчас: ' + (f.get('feed') || '')
      ];
      function fallbackMailto(){
        var body = encodeURIComponent(lines.join('\n'));
        var subject = encodeURIComponent('Заявка на расчёт рациона — сайт agrostandart.by');
        window.location.href = 'mailto:minsk@agrostandart.by?subject=' + subject + '&body=' + body;
      }
      if (window.asLead && window.asLead.ajaxUrl) {
        var btn = form.querySelector('button[type="submit"]');
        if (btn) btn.disabled = true;
        var fd = new FormData(form);
        fd.append('action', 'as_lead_submit');
        fd.append('nonce', window.asLead.nonce);
        fetch(window.asLead.ajaxUrl, { method: 'POST', body: fd })
          .then(function(r){ return r.json(); })
          .then(function(res){
            if (res && res.success) {
              form.innerHTML = '<p class="form-sent">Спасибо! Заявка отправлена, мы свяжемся с вами в ближайшее время.</p>';
            } else {
              fallbackMailto();
            }
          })
          .catch(fallbackMailto)
          .finally(function(){ if (btn) btn.disabled = false; });
      } else {
        fallbackMailto();
      }
    });
  }
})();
