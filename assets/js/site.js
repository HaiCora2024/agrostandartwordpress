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
      var body = encodeURIComponent(lines.join('\n'));
      var subject = encodeURIComponent('Заявка на расчёт рациона — сайт agrostandart.by');
      window.location.href = 'mailto:sales@agrostandart.by?subject=' + subject + '&body=' + body;
    });
  }
})();
